<?php
/*
Plugin Name: Post and Page Views Counter
Author: Counter Software
Author URI: http://countersoftware.com/
Version: 1.0.3
*/


// this plugin needs to be initialized AFTER the Contact Form 7 plugin.
function contact_form_7_save_my_leads_fields() 
{
	global $pagenow;
	if(!function_exists('wpcf7_add_shortcode')) {
		if($pagenow != 'plugins.php') { return; }
		add_action('admin_notices', 'cftagitfieldserror');

		function cftagitfieldserror() {
			$out = '<div class="error update-message" id="messages"><p>error</p></div>';
			echo $out;
		}
	}
}
add_action('plugins_loaded', 'contact_form_7_save_my_leads_fields', 10); 


