<?php
/**
 * Enqueue Scripts Functions
 *
 * @package WPAS_ATTENDEEINFO
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('admin_enqueue_scripts', 'wpas_attendeeinfo_load_admin_enq');
/**
 * Enqueue style and js for each admin entity pages and settings
 *
 * @since WPAS 4.0
 * @param string $hook
 *
 */
function wpas_attendeeinfo_load_admin_enq($hook) {
	global $typenow;
	$dir_url = WPAS_ATTENDEEINFO_PLUGIN_URL;
	do_action('emd_ext_admin_enq', 'wpas_attendeeinfo', $hook);
	if ($hook == 'edit-tags.php') {
		return;
	}
	if (isset($_GET['page']) && in_array($_GET['page'], Array(
		'wpas_attendeeinfo',
		'wpas_attendeeinfo_notify',
		'wpas_attendeeinfo_settings'
	))) {
		wp_enqueue_script('accordion');
		wp_enqueue_style('codemirror-css', $dir_url . 'assets/ext/codemirror/codemirror.css');
		wp_enqueue_script('codemirror-js', $dir_url . 'assets/ext/codemirror/codemirror.js', array() , '', true);
		wp_enqueue_script('codemirror-css-js', $dir_url . 'assets/ext/codemirror/css.js', array() , '', true);
		return;
	} else if (isset($_GET['page']) && in_array($_GET['page'], Array(
		'wpas_attendeeinfo_store',
		'wpas_attendeeinfo_designs',
		'wpas_attendeeinfo_support'
	))) {
		wp_enqueue_style('admin-tabs', $dir_url . 'assets/css/admin-store.css');
		return;
	}
	if (in_array($typenow, Array(
		'emd_contact',
		'emd_attendee'
	))) {
		$theme_changer_enq = 1;
		$sing_enq = 0;
		$tab_enq = 0;
		if ($hook == 'post.php' || $hook == 'post-new.php') {
			$unique_vars['msg'] = __('Please enter a unique value.', 'wpas_attendeeinfo');
			$unique_vars['reqtxt'] = __('required', 'wpas_attendeeinfo');
			$unique_vars['app_name'] = 'wpas_attendeeinfo';
			$ent_list = get_option('wpas_attendeeinfo_ent_list');
			if (!empty($ent_list[$typenow])) {
				$unique_vars['keys'] = $ent_list[$typenow]['unique_keys'];
				if (!empty($ent_list[$typenow]['req_blt'])) {
					$unique_vars['req_blt_tax'] = $ent_list[$typenow]['req_blt'];
				}
			}
			$tax_list = get_option('wpas_attendeeinfo_tax_list');
			if (!empty($tax_list[$typenow])) {
				foreach ($tax_list[$typenow] as $txn_name => $txn_val) {
					if ($txn_val['required'] == 1) {
						$unique_vars['req_blt_tax'][$txn_name] = Array(
							'hier' => $txn_val['hier'],
							'type' => $txn_val['type'],
							'label' => $txn_val['label'] . ' ' . __('Taxonomy', 'wpas_attendeeinfo')
						);
					}
				}
			}
			wp_enqueue_script('unique_validate-js', $dir_url . 'assets/js/unique_validate.js', array(
				'jquery',
				'jquery-validate'
			) , WPAS_ATTENDEEINFO_VERSION, true);
			wp_localize_script("unique_validate-js", 'unique_vars', $unique_vars);
		} elseif ($hook == 'edit.php') {
			wp_enqueue_style('wpas_attendeeinfo-allview-css', WPAS_ATTENDEEINFO_PLUGIN_URL . '/assets/css/allview.css');
		}
		switch ($typenow) {
			case 'emd_contact':
			break;
			case 'emd_attendee':
			break;
		}
	}
}
add_action('wp_enqueue_scripts', 'wpas_attendeeinfo_frontend_scripts');
/**
 * Enqueue style and js for each frontend entity pages and components
 *
 * @since WPAS 4.0
 *
 */
function wpas_attendeeinfo_frontend_scripts() {
	$dir_url = WPAS_ATTENDEEINFO_PLUGIN_URL;
	wp_register_style('wpas_attendeeinfo-allview-css', $dir_url . '/assets/css/allview.css');
	$grid_vars = Array();
	$local_vars['ajax_url'] = admin_url('admin-ajax.php');
	$wpas_shc_list = get_option('wpas_attendeeinfo_shc_list');
	wp_register_style("wpas_attendeeinfo-default-single-css", WPAS_ATTENDEEINFO_PLUGIN_URL . 'assets/css/wpas_attendeeinfo-default-single.css');
	if (is_single() && get_post_type() == 'emd_contact') {
		wp_enqueue_style("wpas_attendeeinfo-default-single-css");
		wpas_attendeeinfo_enq_custom_css();
	}
	if (is_single() && get_post_type() == 'emd_attendee') {
		wp_enqueue_style("wpas_attendeeinfo-default-single-css");
		wpas_attendeeinfo_enq_custom_css();
	}
}
/**
 * Enqueue custom css if set in settings tool tab
 *
 * @since WPAS 5.3
 *
 */
function wpas_attendeeinfo_enq_custom_css() {
	$tools = get_option('wpas_attendeeinfo_tools');
	if (!empty($tools['custom_css'])) {
		$url = home_url();
		if (is_ssl()) {
			$url = home_url('/', 'https');
		}
		wp_enqueue_style('wpas_attendeeinfo-custom', add_query_arg(array(
			'wpas_attendeeinfo-css' => 1
		) , $url));
	}
}
/**
 * If app custom css query var is set, print custom css
 */
function wpas_attendeeinfo_print_css() {
	// Only print CSS if this is a stylesheet request
	if (!isset($_GET['wpas_attendeeinfo-css']) || intval($_GET['wpas_attendeeinfo-css']) !== 1) {
		return;
	}
	ob_start();
	header('Content-type: text/css');
	$tools = get_option('wpas_attendeeinfo_tools');
	$raw_content = isset($tools['custom_css']) ? $tools['custom_css'] : '';
	$content = wp_kses($raw_content, array(
		'\'',
		'\"'
	));
	$content = str_replace('&gt;', '>', $content);
	echo $content; //xss okay
	die();
}
add_action('plugins_loaded', 'wpas_attendeeinfo_print_css');
/**
 * Enqueue if allview css is not enqueued
 *
 * @since WPAS 4.5
 *
 */
function wpas_attendeeinfo_enq_allview() {
	if (!wp_style_is('wpas_attendeeinfo-allview-css', 'enqueued')) {
		wp_enqueue_style('wpas_attendeeinfo-allview-css');
	}
}
