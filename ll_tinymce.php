<?php
/*
Plugin Name: Limelight TINY Plugin
Description: Stuff
Version: 0.0.1
Plugin URI: http://www.limelightnetworks.com/
Author: Limelight
Author URI: http://www.limelightnetworks.com/
*/

<?php

/**
 * @title TinyMCE V3 Button Integration (for Wp2.5)
 * @author Alex Rabe
 */

class add_ll_button {

	var $pluginname = "ll_something";

	function add_ll_button()  {
		// Modify the version when tinyMCE plugins are changed.
		add_filter('tiny_mce_version', array (&$this, 'change_tinymce_version') );

		// init process for button control
		add_action('init', array (&$this, 'addbuttons') );
	}

	function addbuttons() {

		// Don't bother doing this stuff if the current user lacks permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;

		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {

		// add the button for wp2.5 in a new way
			add_filter("mce_external_plugins", array (&$this, "add_tinymce_plugin" ), 5);
			add_filter('mce_buttons', array (&$this, 'register_button' ), 5);
		}
	}

	// used to insert button in wordpress 2.5x editor
	function register_button($buttons) {

		array_push($buttons, "separator", $this->pluginname );

		return $buttons;
	}

	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function add_tinymce_plugin($plugin_array) {

		$plugin_array[$this->pluginname] =  "http://ec2-174-129-76-127.compute-1.amazonaws.com/wordpress/wp-content/plugins/limelight/tinymce.js";

		return $plugin_array;
	}

	function change_tinymce_version($version) {
		return ++$version;
	}

}

// Call it now
$ll_tinymce_button = new add_ll_button ();

?>

