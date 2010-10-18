<?php
/*
Plugin Name: Limelight Networks
Description: Integrates your video content into Wordpress.
Version: 1.0.0
Plugin URI: http://www.limelightnetworks.com/
Author: Limelight
Author URI: http://www.limelightnetworks.com/
*/


//////////////////////////////////////////////
// Menu Options
//////////////////////////////////////////////
add_action( 'admin_menu' , 'limelight_settings_menu' );

function limelight_settings_menu() {
	// Add a menu item to the 'Settings' menu
	add_options_page( 'Limelight Networks Options' , 'Limelight Networks' , 'manage_options' , 'limelight_video' , 'limelight_video_options' );
}

function limelight_video_options() {
	if ( !current_user_can( 'manage_options' ) )	{
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	// Load the admin content
	include( 'limelight_admin.php' );
}



//////////////////////////////////////////////
// Embed Code Filter
//////////////////////////////////////////////

// "[limelight mediaId=MEDIA_ID]"
// "[limelight channelId=CHANNEL_ID]"
// "[limelight channelId=CHANNEL_ID 210 175]"
// "[limelight channelId=CHANNEL_ID&someFlashVar=SOME_FLASHVAR 210 175]"

define( 'LIMELIGHT_WIDTH' , 480 ); // default width
define( 'LIMELIGHT_HEIGHT' , 411 ); // default height
define( 'LIMELIGHT_REGEXP' , "/\[limelight ([[:print:]]+)\]/");
define( 'LIMELIGHT_TARGET' , '<object width="###WIDTH###" height="###HEIGHT###" id="delve_player440704o" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param name="movie" value="http://assets.delvenetworks.com/player/loader.swf"/><param name="wmode" value="window"/><param name="allowScriptAccess" value="always"/><param name="allowFullScreen" value="true"/><param name="flashvars" value="###FLASHVARS###"/><embed src="http://assets.delvenetworks.com/player/loader.swf" name="delve_player440704e" wmode="window" width="###WIDTH###" height="###HEIGHT###" allowScriptAccess="always" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="###FLASHVARS###"></embed></object>' );

add_option( 'limelight_default_width' , LIMELIGHT_WIDTH );
add_option( 'limelight_default_height' , LIMELIGHT_HEIGHT );
add_option( 'limelight_additional_flashvars' , "" );

// Replace shortcode with embed code, swapping out placeholders in the embed template
// with defaults and parameters from shortcode.
function limelight_embed_generation_callback( $match ) {
	$tag_parts = explode(" ", rtrim($match[0] , ']' ));
	$flashvars = $tag_parts[1];
	$additional_flashvars = get_option( 'limelight_additional_flashvars' , '' );
	$output = LIMELIGHT_TARGET;
	$output = str_replace( '###FLASHVARS###' , "$additional_flashvars&$flashvars" , $output );

	if (array_key_exists(2, $tag_parts) && trim($tag_parts[2]) != "") {
		$width = $tag_parts[2];
	} else {
		$width = get_option( 'limelight_default_width' , LIMELIGHT_WIDTH );
	}
	$output = str_replace('###WIDTH###', $width, $output);

	if (array_key_exists(3, $tag_parts) && trim($tag_parts[3]) != "") {
		$height = $tag_parts[3];
	} else {
		$height = get_option( 'limelight_default_height' , LIMELIGHT_HEIGHT );
	}
	$output = str_replace( '###HEIGHT###' , $height , $output );

	return ( $output );
}

function limelight_embed_generation( $content ) {
	return ( preg_replace_callback( LIMELIGHT_REGEXP , 'limelight_embed_generation_callback' , $content ) );
}

add_filter( 'the_content', 'limelight_embed_generation' );
add_filter( 'the_content_rss', 'limelight_embed_generation' );
add_filter( 'the_excerpt', 'limelight_embed_generation' );




//////////////////////////////////////////////////////////////////////////////////////
// Adding the actions and hooks for admin menu, and embed-code short code replacement.
//////////////////////////////////////////////////////////////////////////////////////

class add_limelight_button {

	var $pluginname = "limelight_networks";

	function add_limelight_button()	{
		// Modify the version when tinyMCE plugins are changed.
		add_filter( 'tiny_mce_version' , array (&$this, 'change_tinymce_version') );

		// init process for button control
		add_action( 'init', array (&$this, 'addbuttons') );
	}

	function addbuttons() {
		// Don't bother doing this stuff if the current user lacks permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;

		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
			// add the button for wp2.5 in a new way
			add_filter( 'mce_external_plugins', array (&$this, 'add_tinymce_plugin' ), 5 );
			add_filter( 'mce_buttons', array (&$this, 'register_button' ), 5 );
		}
	}

	// used to insert button in wordpress 2.5x editor
	function register_button( $buttons ) {
		array_push( $buttons, "separator", $this->pluginname );

		return $buttons;
	}

	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function add_tinymce_plugin( $plugin_array ) {
		$site_url = get_option( 'siteurl' );
		$plugin_array[$this->pluginname] = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'editor_plugin.js';
		return $plugin_array;
	}

	function change_tinymce_version( $version ) {
		return ++$version;
	}
}

// Call it now
$limelight_tinymce_button = new add_limelight_button();
?>
