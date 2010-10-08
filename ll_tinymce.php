<?php
/*
Plugin Name: Limelight TINY Plugin
Description: Stuff
Version: 0.0.1
Plugin URI: http://www.limelightnetworks.com/
Author: Limelight
Author URI: http://www.limelightnetworks.com/
*/

var $pluginname = "wordTube";

add_action('init', 'addbuttons');

function addbuttons() {
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_tinymce_plugin", 5);
		add_filter('mce_buttons', 'register_button', 5);
	}
}

function register_button($buttons) {
	array_push($buttons, "separator", $this->pluginname );
	return $buttons;
}

function add_tinymce_plugin($plugin_array) {
	$plugin_array[$this->pluginname] =  "http://foo.com/jah.js";
	return $plugin_array;
}

?>