<?php
/*
Plugin Name: Limelight Video Integration
Description: Integrates your video content into Wordpress.
Version: 0.0.1
Plugin URI: http://www.limelightnetworks.com/
Author: Limelight
Author URI: http://www.limelightnetworks.com/
*/


//////////////////////////////////////////////
// Menu Options
//////////////////////////////////////////////
add_action('admin_menu', 'll_settings_menu');

function ll_settings_menu() {
  add_options_page('Limelight Networks Options', 'Limelight Networks', 'manage_options', 'll_video', 'll_video_options');

}

function ll_video_options() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

  include('ll_admin.php');
}



//////////////////////////////////////////////
// Embed Code Filter
//////////////////////////////////////////////

// "[limelight mediaId=MEDIA_ID]"
// "[limelight channelId=CHANNEL_ID]"
// "[limelight channelId=CHANNEL_ID 210 175]"
// "[limelight channelId=CHANNEL_ID&someFlashVar=SOME_FLASHVAR 210 175]"

add_option('ll_default_width', 480);
add_option('ll_default_height', 411);
add_option('ll_additional_flashvars', "");

define("LIMELIGHT_WIDTH", 480); // default width
define("LIMELIGHT_HEIGHT", 411); // default height
define("LIMELIGHT_REGEXP", "/\[limelight ([[:print:]]+)\]/");
define("LIMELIGHT_TARGET",'<object width="###WIDTH###" height="###HEIGHT###" id="delve_player440704o" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param name="movie" value="http://assets.delvenetworks.com/player/loader.swf"/><param name="wmode" value="window"/><param name="allowScriptAccess" value="always"/><param name="allowFullScreen" value="true"/><param name="flashvars" value="###FLASHVARS###"/><embed src="http://assets.delvenetworks.com/player/loader.swf" name="delve_player440704e" wmode="window" width="###WIDTH###" height="###HEIGHT###" allowScriptAccess="always" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="###FLASHVARS###"></embed></object>');

function limelight_plugin_callback($match) {
  $tag_parts = explode(" ", rtrim($match[0], "]"));
  $flashvars = $tag_parts[1];
  $additional_flashvars = get_option('ll_additional_flashvars', "");
  $output = LIMELIGHT_TARGET;
  $output = str_replace("###FLASHVARS###", "$additional_flashvars&$flashvars", $output);

  if (array_key_exists(2, $tag_parts)) {
    $width = $tag_parts[2];
  } else {
    $width = get_option('ll_default_width', LIMELIGHT_WIDTH);
  }
  $output = str_replace("###WIDTH###", $width, $output);

  if (array_key_exists(3, $tag_parts)) {
    $height = $tag_parts[3];
  } else {
    $height = get_option('ll_default_height', LIMELIGHT_HEIGHT);
  }
  $output = str_replace("###HEIGHT###", $height, $output);

  return ($output);
}

function limelight_plugin($content) {
  return (preg_replace_callback(LIMELIGHT_REGEXP, 'limelight_plugin_callback', $content));
}

add_filter('the_content', 'limelight_plugin',1);
add_filter('the_content_rss', 'limelight_plugin');
add_filter('the_excerpt', 'limelight_plugin');
?>
