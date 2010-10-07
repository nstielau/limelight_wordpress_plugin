<?php
/*
Plugin Name: Limelight
Description: Integrates your video content into Wordpress.
Version: 0.0.1
Plugin URI: http://www.limelightnetworks.com/
Author: Limelight
Author URI: http://www.limelightnetworks.com/
*/

add_action('admin_menu', 'll_settings_menu');

function ll_settings_menu() {
  add_options_page('Limelight Video Options', 'Limelight Video', 'manage_options', 'll_video', 'll_video_options');

}

function ll_video_options() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

  include('ll_admin.php');
}
?>
