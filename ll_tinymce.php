<?php
/*
Plugin Name: Limelight TINY Plugin
Description: Stuff
Version: 0.0.1
Plugin URI: http://www.limelightnetworks.com/
Author: Limelight
Author URI: http://www.limelightnetworks.com/
*/

$ll_tinymce_pluginname = "ll_something";

add_action('init', 'add_ll_tinymce_buttons');

function add_ll_tinymce_buttons() {
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "ll_add_tinymce_plugin", 5);
		add_filter('mce_buttons', 'll_register_button', 5);
	}
}

function ll_register_button($buttons) {
	array_push($buttons, "separator", $ll_tinymce_pluginname );
	return $buttons;
}

function ll_add_tinymce_plugin($plugin_array) {
	$plugin_array[$ll_tinymce_pluginname] =  "http://ec2-174-129-76-127.compute-1.amazonaws.com/wordpress/wp-content/limelight/tinymce.js";
	return $plugin_array;
}

?>