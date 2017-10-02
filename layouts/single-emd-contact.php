<?php $ent_attrs = get_option('wpas_attendeeinfo_attr_list'); ?>
<div class="emd-container">
<?php
if (emd_is_item_visible('emd_contact_first_name', 'wpas_attendeeinfo', 'attribute')) {
	$emd_contact_first_name = emd_mb_meta('emd_contact_first_name');
	if (!empty($emd_contact_first_name)) { ?>
   <div id="emd-contact-emd-contact-first-name-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-first-name-key" class="emd-single-title">
<?php _e('First Name', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-first-name-val" class="emd-single-val">
<?php echo $emd_contact_first_name; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_contact_last_name', 'wpas_attendeeinfo', 'attribute')) {
	$emd_contact_last_name = emd_mb_meta('emd_contact_last_name');
	if (!empty($emd_contact_last_name)) { ?>
   <div id="emd-contact-emd-contact-last-name-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-last-name-key" class="emd-single-title">
<?php _e('Last Name', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-last-name-val" class="emd-single-val">
<?php echo $emd_contact_last_name; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_contact_email', 'wpas_attendeeinfo', 'attribute')) {
	$emd_contact_email = emd_mb_meta('emd_contact_email');
	if (!empty($emd_contact_email)) { ?>
   <div id="emd-contact-emd-contact-email-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-email-key" class="emd-single-title">
<?php _e('Email', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-email-val" class="emd-single-val">
<?php echo $emd_contact_email; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_contact_address', 'wpas_attendeeinfo', 'attribute')) {
	$emd_contact_address = emd_mb_meta('emd_contact_address');
	if (!empty($emd_contact_address)) { ?>
   <div id="emd-contact-emd-contact-address-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-address-key" class="emd-single-title">
<?php _e('Address', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-address-val" class="emd-single-val">
<?php echo $emd_contact_address; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_contact_city', 'wpas_attendeeinfo', 'attribute')) {
	$emd_contact_city = emd_mb_meta('emd_contact_city');
	if (!empty($emd_contact_city)) { ?>
   <div id="emd-contact-emd-contact-city-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-city-key" class="emd-single-title">
<?php _e('City', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-city-val" class="emd-single-val">
<?php echo $emd_contact_city; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_contact_zip', 'wpas_attendeeinfo', 'attribute')) {
	$emd_contact_zip = emd_mb_meta('emd_contact_zip');
	if (!empty($emd_contact_zip)) { ?>
   <div id="emd-contact-emd-contact-zip-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-zip-key" class="emd-single-title">
<?php _e('Zipcode', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-zip-val" class="emd-single-val">
<?php echo $emd_contact_zip; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
$emd_contact_state = emd_get_attr_val(str_replace("-", "_", 'wpas_attendeeinfo') , $post->ID, 'emd_contact', 'emd_contact_state');
if (!empty($emd_contact_state)) { ?>
   <div id="emd-contact-emd-contact-state-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-state-key" class="emd-single-title">
<?php _e('State', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-state-val" class="emd-single-val">
<?php echo $emd_contact_state; ?>
   </div>
   </div>
<?php
} ?>
<?php
if (emd_is_item_visible('emd_contact_phone', 'wpas_attendeeinfo', 'attribute')) {
	$emd_contact_phone = emd_mb_meta('emd_contact_phone');
	if (!empty($emd_contact_phone)) {
?>
<div id="emd-contact-emd-contact-phone-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-phone-key" class="emd-single-title">
<?php _e('Phone', 'wpas_attendeeinfo'); ?>
   </div>
<div id="emd-contact-emd-contact-phone-val" class="emd-single-val">
<?php if (is_array($emd_contact_phone)) {
			foreach ($emd_contact_phone as $my_clone) {
?>
<div class="phone1 phone3 phone3"><?php echo $my_clone; ?></div>
<?php
			}
		} else {
?><div class="phone1 phone3 phone3"><?php echo $emd_contact_phone; ?></div>
<?php
		}
?>
   </div>
   </div>
<?php
	}
}
?>
<?php
$emd_contact_callback_time = emd_get_attr_val(str_replace("-", "_", 'wpas_attendeeinfo') , $post->ID, 'emd_contact', 'emd_contact_callback_time');
if (!empty($emd_contact_callback_time)) { ?>
   <div id="emd-contact-emd-contact-callback-time-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-callback-time-key" class="emd-single-title">
<?php _e('Callback Time', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-callback-time-val" class="emd-single-val">
<?php echo $emd_contact_callback_time; ?>
   </div>
   </div>
<?php
} ?>
<?php
if (emd_is_item_visible('emd_contact_quote', 'wpas_attendeeinfo', 'attribute')) {
	$emd_contact_quote = emd_mb_meta('emd_contact_quote');
	if (!empty($emd_contact_quote)) { ?>
   <div id="emd-contact-emd-contact-quote-div" class="emd-single-div">
   <div id="emd-contact-emd-contact-quote-key" class="emd-single-title">
<?php _e('Quote', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-contact-emd-contact-quote-val" class="emd-single-val">
<?php echo $emd_contact_quote; ?>
   </div>
   </div>
<?php
	}
}
?>
</div><!--container-end-->