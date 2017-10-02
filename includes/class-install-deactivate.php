<?php
/**
 * Install and Deactivate Plugin Functions
 * @package WPAS_ATTENDEEINFO
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
if (!class_exists('Wpas_attendeeinfo_Install_Deactivate')):
	/**
	 * Wpas_attendeeinfo_Install_Deactivate Class
	 * @since WPAS 4.0
	 */
	class Wpas_attendeeinfo_Install_Deactivate {
		private $option_name;
		/**
		 * Hooks for install and deactivation and create options
		 * @since WPAS 4.0
		 */
		public function __construct() {
			$this->option_name = 'wpas_attendeeinfo';
			$curr_version = get_option($this->option_name . '_version', 1);
			$new_version = constant(strtoupper($this->option_name) . '_VERSION');
			if (version_compare($curr_version, $new_version, '<')) {
				$this->set_options();
				$this->set_roles_caps();
				if (!get_option($this->option_name . '_activation_date')) {
					$triggerdate = mktime(0, 0, 0, date('m') , date('d') + 7, date('Y'));
					add_option($this->option_name . '_activation_date', $triggerdate);
				}
				set_transient($this->option_name . '_activate_redirect', true, 30);
				do_action($this->option_name . '_upgrade', $new_version);
				update_option($this->option_name . '_version', $new_version);
			}
			register_activation_hook(WPAS_ATTENDEEINFO_PLUGIN_FILE, array(
				$this,
				'install'
			));
			register_deactivation_hook(WPAS_ATTENDEEINFO_PLUGIN_FILE, array(
				$this,
				'deactivate'
			));
			add_action('wp_head', array(
				$this,
				'version_in_header'
			));
			add_action('admin_init', array(
				$this,
				'register_settings'
			) , 0);
			add_action('init', array(
				$this,
				'init_extensions'
			) , 99);
		}
		public function version_in_header() {
			$version = constant(strtoupper($this->option_name) . '_VERSION');
			$name = constant(strtoupper($this->option_name) . '_NAME');
			echo '<meta name="generator" content="' . $name . ' v' . $version . ' - https://emdplugins.com" />' . "\n";
		}
		public function init_extensions() {
			do_action('emd_ext_init', $this->option_name);
		}
		/**
		 * Runs on plugin install to setup custom post types and taxonomies
		 * flushing rewrite rules, populates settings and options
		 * creates roles and assign capabilities
		 * @since WPAS 4.0
		 *
		 */
		public function install() {
			$this->set_options();
			Emd_Contact::register();
			Emd_Attendee::register();
			flush_rewrite_rules();
			$this->set_roles_caps();
			set_transient($this->option_name . '_activate_redirect', true, 30);
			do_action('emd_ext_install_hook', $this->option_name);
		}
		/**
		 * Runs on plugin deactivate to remove options, caps and roles
		 * flushing rewrite rules
		 * @since WPAS 4.0
		 *
		 */
		public function deactivate() {
			flush_rewrite_rules();
			$this->remove_caps_roles();
			$this->reset_options();
			do_action('emd_ext_deactivate', $this->option_name);
		}
		/**
		 * Register notification and/or license settings
		 * @since WPAS 4.0
		 *
		 */
		public function register_settings() {
			do_action('emd_ext_register', $this->option_name);
			if (!get_transient($this->option_name . '_activate_redirect')) {
				return;
			}
			// Delete the redirect transient.
			delete_transient($this->option_name . '_activate_redirect');
			$query_args = array(
				'page' => $this->option_name
			);
			wp_safe_redirect(add_query_arg($query_args, admin_url('admin.php')));
		}
		/**
		 * Sets caps and roles
		 *
		 * @since WPAS 4.0
		 *
		 */
		public function set_roles_caps() {
			global $wp_roles;
			if (class_exists('WP_Roles')) {
				if (!isset($wp_roles)) {
					$wp_roles = new WP_Roles();
				}
			}
			if (is_object($wp_roles)) {
				$this->set_reset_caps($wp_roles, 'add');
			}
		}
		/**
		 * Removes caps and roles
		 *
		 * @since WPAS 4.0
		 *
		 */
		public function remove_caps_roles() {
			global $wp_roles;
			if (class_exists('WP_Roles')) {
				if (!isset($wp_roles)) {
					$wp_roles = new WP_Roles();
				}
			}
			if (is_object($wp_roles)) {
				$this->set_reset_caps($wp_roles, 'remove');
			}
		}
		/**
		 * Set  capabilities
		 *
		 * @since WPAS 4.0
		 * @param object $wp_roles
		 * @param string $type
		 *
		 */
		public function set_reset_caps($wp_roles, $type) {
			$caps['enable'] = Array(
				'edit_emd_attendees' => Array(
					'administrator'
				) ,
				'delete_attendee' => Array(
					'administrator'
				) ,
				'manage_attendee' => Array(
					'administrator'
				) ,
				'export' => Array(
					'administrator'
				) ,
				'assign_attendee' => Array(
					'administrator'
				) ,
				'view_wpas_attendeeinfo_dashboard' => Array(
					'administrator'
				) ,
				'edit_attendee' => Array(
					'administrator'
				) ,
				'manage_operations_emd_attendees' => Array(
					'administrator'
				) ,
				'manage_operations_emd_contacts' => Array(
					'administrator'
				) ,
				'edit_emd_contacts' => Array(
					'administrator'
				) ,
			);
			$caps['enable'] = apply_filters('emd_ext_get_caps', $caps['enable'], $this->option_name);
			foreach ($caps as $stat => $role_caps) {
				foreach ($role_caps as $mycap => $roles) {
					foreach ($roles as $myrole) {
						if (($type == 'add' && $stat == 'enable') || ($stat == 'disable' && $type == 'remove')) {
							$wp_roles->add_cap($myrole, $mycap);
						} else if (($type == 'remove' && $stat == 'enable') || ($type == 'add' && $stat == 'disable')) {
							$wp_roles->remove_cap($myrole, $mycap);
						}
					}
				}
			}
		}
		/**
		 * Set app specific options
		 *
		 * @since WPAS 4.0
		 *
		 */
		private function set_options() {
			$access_views = Array();
			$ent_list = Array(
				'emd_contact' => Array(
					'label' => __('Contacts', 'wpas_attendeeinfo') ,
					'rewrite' => 'emd_contact',
					'archive_view' => 0,
					'sortable' => 0,
					'searchable' => 1,
					'unique_keys' => Array(
						'emd_contact_email'
					) ,
				) ,
				'emd_attendee' => Array(
					'label' => __('Attendees', 'wpas_attendeeinfo') ,
					'rewrite' => 'emd_attendee',
					'archive_view' => 0,
					'sortable' => 0,
					'searchable' => 1,
					'unique_keys' => Array(
						'emd_attendee_email'
					) ,
				) ,
			);
			update_option($this->option_name . '_ent_list', $ent_list);
			$shc_list['app'] = 'Woocommerce Attendee Information';
			$shc_list['has_gmap'] = 0;
			$shc_list['has_bs'] = 0;
			$shc_list['remove_vis'] = 1;
			if (!empty($shc_list)) {
				update_option($this->option_name . '_shc_list', $shc_list);
			}
			$attr_list['emd_contact']['emd_contact_first_name'] = Array(
				'label' => __('First Name', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_contact_info_emd_contact_0',
				'type' => 'char',
			);
			$attr_list['emd_contact']['emd_contact_last_name'] = Array(
				'label' => __('Last Name', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_contact_info_emd_contact_0',
				'type' => 'char',
			);
			$attr_list['emd_contact']['emd_contact_email'] = Array(
				'label' => __('Email', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_contact_info_emd_contact_0',
				'type' => 'char',
				'email' => true,
				'uniqueAttr' => true,
			);
			$attr_list['emd_contact']['emd_contact_address'] = Array(
				'label' => __('Address', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 0,
				'list_visible' => 1,
				'mid' => 'emd_contact_info_emd_contact_0',
				'type' => 'char',
			);
			$attr_list['emd_contact']['emd_contact_city'] = Array(
				'label' => __('City', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_contact_info_emd_contact_0',
				'type' => 'char',
			);
			$attr_list['emd_contact']['emd_contact_zip'] = Array(
				'label' => __('Zipcode', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 0,
				'list_visible' => 0,
				'mid' => 'emd_contact_info_emd_contact_0',
				'desc' => __('We only accept US based customers so you need to have a valid U.S. zip code.', 'wpas_attendeeinfo') ,
				'type' => 'char',
				'zipcodeUS' => true,
			);
			$attr_list['emd_contact']['emd_contact_state'] = Array(
				'label' => __('State', 'wpas_attendeeinfo') ,
				'display_type' => 'select',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_contact_info_emd_contact_0',
				'type' => 'char',
				'options' => array(
					'' => __('Please Select', 'wpas_attendeeinfo') ,
					'ak' => __('AK', 'wpas_attendeeinfo') ,
					'al' => __('AL', 'wpas_attendeeinfo') ,
					'ar' => __('AR', 'wpas_attendeeinfo') ,
					'az' => __('AZ', 'wpas_attendeeinfo') ,
					'ca' => __('CA', 'wpas_attendeeinfo') ,
					'co' => __('CO', 'wpas_attendeeinfo') ,
					'ct' => __('CT', 'wpas_attendeeinfo') ,
					'dc' => __('DC', 'wpas_attendeeinfo') ,
					'de' => __('DE', 'wpas_attendeeinfo') ,
					'fl' => __('FL', 'wpas_attendeeinfo') ,
					'ga' => __('GA', 'wpas_attendeeinfo') ,
					'hi' => __('HI', 'wpas_attendeeinfo') ,
					'ia' => __('IA', 'wpas_attendeeinfo') ,
					'id' => __('ID', 'wpas_attendeeinfo') ,
					'il' => __('IL', 'wpas_attendeeinfo') ,
					'in' => __('IN', 'wpas_attendeeinfo') ,
					'ks' => __('KS', 'wpas_attendeeinfo') ,
					'ky' => __('KY', 'wpas_attendeeinfo') ,
					'la' => __('LA', 'wpas_attendeeinfo') ,
					'ma' => __('MA', 'wpas_attendeeinfo') ,
					'md' => __('MD', 'wpas_attendeeinfo') ,
					'me' => __('ME', 'wpas_attendeeinfo') ,
					'mi' => __('MI', 'wpas_attendeeinfo') ,
					'mn' => __('MN', 'wpas_attendeeinfo') ,
					'mo' => __('MO', 'wpas_attendeeinfo') ,
					'ms' => __('MS', 'wpas_attendeeinfo') ,
					'mt' => __('MT', 'wpas_attendeeinfo') ,
					'nc' => __('NC', 'wpas_attendeeinfo') ,
					'nd' => __('ND', 'wpas_attendeeinfo') ,
					'ne' => __('NE', 'wpas_attendeeinfo') ,
					'nh' => __('NH', 'wpas_attendeeinfo') ,
					'nj' => __('NJ', 'wpas_attendeeinfo') ,
					'nm' => __('NM', 'wpas_attendeeinfo') ,
					'nv' => __('NV', 'wpas_attendeeinfo') ,
					'ny' => __('NY', 'wpas_attendeeinfo') ,
					'oh' => __('OH', 'wpas_attendeeinfo') ,
					'ok' => __('OK', 'wpas_attendeeinfo') ,
					'or' => __('OR', 'wpas_attendeeinfo') ,
					'pa' => __('PA', 'wpas_attendeeinfo') ,
					'ri' => __('RI', 'wpas_attendeeinfo') ,
					'sc' => __('SC', 'wpas_attendeeinfo') ,
					'sd' => __('SD', 'wpas_attendeeinfo') ,
					'tn' => __('TN', 'wpas_attendeeinfo') ,
					'tx' => __('TX', 'wpas_attendeeinfo') ,
					'ut' => __('UT', 'wpas_attendeeinfo') ,
					'va' => __('VA', 'wpas_attendeeinfo') ,
					'vt' => __('VT', 'wpas_attendeeinfo') ,
					'wa' => __('WA', 'wpas_attendeeinfo') ,
					'wi' => __('WI', 'wpas_attendeeinfo') ,
					'wv' => __('WV', 'wpas_attendeeinfo') ,
					'wy' => __('WY', 'wpas_attendeeinfo')
				) ,
			);
			$attr_list['emd_contact']['emd_contact_phone'] = Array(
				'label' => __('Phone', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 0,
				'mid' => 'emd_contact_info_emd_contact_0',
				'desc' => __('U.S. phone numbers only.', 'wpas_attendeeinfo') ,
				'type' => 'char',
				'phoneUS' => true,
				'clone' => true,
				'max_clone' => 3,
			);
			$attr_list['emd_contact']['emd_contact_callback_time'] = Array(
				'label' => __('Callback Time', 'wpas_attendeeinfo') ,
				'display_type' => 'select',
				'required' => 0,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_contact_info_emd_contact_0',
				'desc' => __('All calls are made before 9.pm in Eastern Time.', 'wpas_attendeeinfo') ,
				'type' => 'char',
				'options' => array(
					'' => __('Please Select', 'wpas_attendeeinfo') ,
					'morning' => __('Morning', 'wpas_attendeeinfo') ,
					'noon' => __('Noon', 'wpas_attendeeinfo') ,
					'evening' => __('Evening', 'wpas_attendeeinfo')
				) ,
				'std' => 'Noon',
			);
			$attr_list['emd_contact']['emd_contact_quote'] = Array(
				'label' => __('Quote', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 0,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_contact_info_emd_contact_0',
				'type' => 'decimal',
				'number' => true,
				'min' => 100,
			);
			$attr_list['emd_attendee']['emd_attendee_name'] = Array(
				'label' => __('Attendee Name', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 1,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_attendee_info_emd_attendee_0',
				'desc' => __('Name of the Attendee', 'wpas_attendeeinfo') ,
				'type' => 'char',
				'lettersonly' => true,
			);
			$attr_list['emd_attendee']['emd_job_title'] = Array(
				'label' => __('Job Title', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 1,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_attendee_info_emd_attendee_0',
				'desc' => __('Designation / Job Title of the Attendee', 'wpas_attendeeinfo') ,
				'type' => 'char',
				'letterswithbasicpunc' => true,
			);
			$attr_list['emd_attendee']['emd_attendee_company'] = Array(
				'label' => __('Company', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 1,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_attendee_info_emd_attendee_0',
				'desc' => __('Company where the attendee works', 'wpas_attendeeinfo') ,
				'type' => 'char',
				'lettersonly' => true,
			);
			$attr_list['emd_attendee']['emd_attendee_email'] = Array(
				'label' => __('Email', 'wpas_attendeeinfo') ,
				'display_type' => 'text',
				'required' => 1,
				'srequired' => 1,
				'filterable' => 1,
				'list_visible' => 1,
				'mid' => 'emd_attendee_info_emd_attendee_0',
				'desc' => __('Attendee Email', 'wpas_attendeeinfo') ,
				'type' => 'char',
				'email' => true,
				'uniqueAttr' => true,
			);
			$attr_list = apply_filters('emd_ext_attr_list', $attr_list, $this->option_name);
			if (!empty($attr_list)) {
				update_option($this->option_name . '_attr_list', $attr_list);
			}
			if (!empty($glob_forms_list)) {
				update_option($this->option_name . '_glob_forms_init_list', $glob_forms_list);
				if (get_option($this->option_name . '_glob_forms_list') === false) {
					update_option($this->option_name . '_glob_forms_list', $glob_forms_list);
				}
			}
			$tax_list['emd_attendee']['attendee'] = Array(
				'archive_view' => 0,
				'label' => __('Attendees', 'wpas_attendeeinfo') ,
				'default' => '',
				'type' => 'multi',
				'hier' => 0,
				'sortable' => 0,
				'list_visible' => 1,
				'required' => 1,
				'srequired' => 1,
				'rewrite' => 'attendee'
			);
			if (!empty($tax_list)) {
				update_option($this->option_name . '_tax_list', $tax_list);
			}
			//conf parameters for incoming email
			//conf parameters for inline entity
			//conf parameters for calendar
			//action to configure different extension conf parameters for this plugin
			do_action('emd_ext_set_conf', 'wpas_attendeeinfo');
		}
		/**
		 * Reset app specific options
		 *
		 * @since WPAS 4.0
		 *
		 */
		private function reset_options() {
			delete_option($this->option_name . '_shc_list');
			do_action('emd_ext_reset_conf', 'wpas_attendeeinfo');
		}
	}
endif;
return new Wpas_attendeeinfo_Install_Deactivate();
