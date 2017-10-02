<?php $ent_attrs = get_option('wpas_attendeeinfo_attr_list'); ?>
<div class="emd-container">
<?php
if (emd_is_item_visible('emd_attendee_name', 'wpas_attendeeinfo', 'attribute')) {
	$emd_attendee_name = emd_mb_meta('emd_attendee_name');
	if (!empty($emd_attendee_name)) { ?>
   <div id="emd-attendee-emd-attendee-name-div" class="emd-single-div">
   <div id="emd-attendee-emd-attendee-name-key" class="emd-single-title">
<?php _e('Attendee Name', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-attendee-emd-attendee-name-val" class="emd-single-val">
<?php echo $emd_attendee_name; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_job_title', 'wpas_attendeeinfo', 'attribute')) {
	$emd_job_title = emd_mb_meta('emd_job_title');
	if (!empty($emd_job_title)) { ?>
   <div id="emd-attendee-emd-job-title-div" class="emd-single-div">
   <div id="emd-attendee-emd-job-title-key" class="emd-single-title">
<?php _e('Job Title', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-attendee-emd-job-title-val" class="emd-single-val">
<?php echo $emd_job_title; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_attendee_company', 'wpas_attendeeinfo', 'attribute')) {
	$emd_attendee_company = emd_mb_meta('emd_attendee_company');
	if (!empty($emd_attendee_company)) { ?>
   <div id="emd-attendee-emd-attendee-company-div" class="emd-single-div">
   <div id="emd-attendee-emd-attendee-company-key" class="emd-single-title">
<?php _e('Company', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-attendee-emd-attendee-company-val" class="emd-single-val">
<?php echo $emd_attendee_company; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
if (emd_is_item_visible('emd_attendee_email', 'wpas_attendeeinfo', 'attribute')) {
	$emd_attendee_email = emd_mb_meta('emd_attendee_email');
	if (!empty($emd_attendee_email)) { ?>
   <div id="emd-attendee-emd-attendee-email-div" class="emd-single-div">
   <div id="emd-attendee-emd-attendee-email-key" class="emd-single-title">
<?php _e('Email', 'wpas_attendeeinfo'); ?>
   </div>
   <div id="emd-attendee-emd-attendee-email-val" class="emd-single-val">
<?php echo $emd_attendee_email; ?>
   </div>
   </div>
<?php
	}
}
?>
<?php
$taxlist = get_object_taxonomies(get_post_type() , 'objects');
foreach ($taxlist as $taxkey => $mytax) {
	$termlist = get_the_term_list(get_the_ID() , $taxkey, '', ' , ', '');
	if (!empty($termlist)) {
		if (emd_is_item_visible('tax_' . $taxkey, 'wpas_attendeeinfo', 'taxonomy')) { ?>
      <div id="emd-attendee-<?php echo esc_attr($taxkey); ?>-div" class="emd-single-div">
      <div id="emd-attendee-<?php echo esc_attr($taxkey); ?>-key" class="emd-single-title">
      <?php echo esc_html($mytax->labels->singular_name); ?>
      </div>
      <div id="emd-attendee-<?php echo esc_attr($taxkey); ?>-val" class="emd-single-val">
      <?php echo $termlist; ?>
      </div>
      </div>
   <?php
		}
	}
} ?>
</div><!--container-end-->