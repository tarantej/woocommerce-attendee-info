<?php
/**
 * Settings Glossary Functions
 *
 * @package WPAS_ATTENDEEINFO
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('wpas_attendeeinfo_settings_glossary', 'wpas_attendeeinfo_settings_glossary');
/**
 * Display glossary information
 * @since WPAS 4.0
 *
 * @return html
 */
function wpas_attendeeinfo_settings_glossary() {
	global $title;
?>
<div class="wrap">
<h2><?php echo $title; ?></h2>
<p><?php _e('Captures additional attendee information for events on checkout page. Customized checkout layout', 'wpas_attendeeinfo'); ?></p>
<p><?php _e('The below are the definitions of entities, attributes, and terms included in Woocommerce Attendee Information.', 'wpas_attendeeinfo'); ?></p>
<div id="glossary" class="accordion-container">
<ul class="outer-border">
<li id="emd_attendee" class="control-section accordion-section">
<h3 class="accordion-section-title hndle" tabindex="2"><?php _e('Attendees', 'wpas_attendeeinfo'); ?></h3>
<div class="accordion-section-content">
<div class="inside">
<table class="form-table"><p class"lead"><?php _e('', 'wpas_attendeeinfo'); ?></p><tr>
<th><?php _e('Attendee Name', 'wpas_attendeeinfo'); ?></th>
<td><?php _e('Name of the Attendee Attendee Name is a required field. Attendee Name is filterable in the admin area. Attendee Name does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Job Title', 'wpas_attendeeinfo'); ?></th>
<td><?php _e('Designation / Job Title of the Attendee Job Title is a required field. Job Title is filterable in the admin area. Job Title does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Company', 'wpas_attendeeinfo'); ?></th>
<td><?php _e('Company where the attendee works Company is a required field. Company is filterable in the admin area. Company does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Email', 'wpas_attendeeinfo'); ?></th>
<td><?php _e('Attendee Email Email is a required field. Being a unique identifier, it uniquely distinguishes each instance of Attendee entity. Email is filterable in the admin area. Email does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Attendee', 'wpas_attendeeinfo'); ?></th>

<td><?php _e(' Attendee accepts multiple values like tags', 'wpas_attendeeinfo'); ?>. <?php _e('Attendee does not have a default value', 'wpas_attendeeinfo'); ?>.<?php _e('Attendee is a required field therefore must be assigned to a value', 'wpas_attendeeinfo'); ?>.<div class="taxdef-block"><p><?php _e('There are no preset values for <b>Attendee:</b>', 'wpas_attendeeinfo'); ?></p></div></td>
</tr>
</table>
</div>
</div>
</li><li id="emd_contact" class="control-section accordion-section">
<h3 class="accordion-section-title hndle" tabindex="1"><?php _e('Contacts', 'wpas_attendeeinfo'); ?></h3>
<div class="accordion-section-content">
<div class="inside">
<table class="form-table"><p class"lead"><?php _e('', 'wpas_attendeeinfo'); ?></p><tr>
<th><?php _e('First Name', 'wpas_attendeeinfo'); ?></th>
<td><?php _e(' First Name is a required field. First Name is filterable in the admin area. First Name does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Last Name', 'wpas_attendeeinfo'); ?></th>
<td><?php _e(' Last Name is a required field. Last Name is filterable in the admin area. Last Name does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Email', 'wpas_attendeeinfo'); ?></th>
<td><?php _e(' Email is a required field. Being a unique identifier, it uniquely distinguishes each instance of Contact entity. Email is filterable in the admin area. Email does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Address', 'wpas_attendeeinfo'); ?></th>
<td><?php _e(' Address does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('City', 'wpas_attendeeinfo'); ?></th>
<td><?php _e(' City is filterable in the admin area. City does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Zipcode', 'wpas_attendeeinfo'); ?></th>
<td><?php _e('We only accept US based customers so you need to have a valid U.S. zip code. Zipcode is a required field. Zipcode does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('State', 'wpas_attendeeinfo'); ?></th>
<td><?php _e(' State is filterable in the admin area. State does not have a default value. State is displayed as a dropdown and has predefined values of: ak, al, ar, az, ca, co, ct, dc, de, fl, ga, hi, ia, id, il, in, ks, ky, la, ma, md, me, mi, mn, mo, ms, mt, nc, nd, ne, nh, nj, nm, nv, ny, oh, ok, or, pa, ri, sc, sd, tn, tx, ut, va, vt, wa, wi, wv, wy.', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Phone', 'wpas_attendeeinfo'); ?></th>
<td><?php _e('U.S. phone numbers only. Phone is a required field. Phone is filterable in the admin area. Phone does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Callback Time', 'wpas_attendeeinfo'); ?></th>
<td><?php _e('All calls are made before 9.pm in Eastern Time. Callback Time is filterable in the admin area. Callback Time has a default value of <b>\'Noon\'</b>.Callback Time is displayed as a dropdown and has predefined values of: morning, noon, evening.', 'wpas_attendeeinfo'); ?></td>
</tr><tr>
<th><?php _e('Quote', 'wpas_attendeeinfo'); ?></th>
<td><?php _e(' Quote is a required field. Quote is filterable in the admin area. Quote does not have a default value. ', 'wpas_attendeeinfo'); ?></td>
</tr></table>
</div>
</div>
</li>
</ul>
</div>
</div>
<?php
}
