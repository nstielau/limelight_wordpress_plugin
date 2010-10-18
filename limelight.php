<?php
/*
Plugin Name: Limelight Video Integration
Description: Integrates your video content into Wordpress.
Version: 1.0.0
Plugin URI: http://www.limelightnetworks.com/
Author: Limelight
Author URI: http://www.limelightnetworks.com/
*/


//////////////////////////////////////////////
// Menu Options
//////////////////////////////////////////////
add_action( 'admin_menu' , 'll_settings_menu' );

function ll_settings_menu() {
	add_options_page( 'Limelight Networks Options' , 'Limelight Networks' , 'manage_options' , 'll_video' , 'll_video_options' );
}

function ll_video_options() {
	if ( !current_user_can( 'manage_options' ) )	{
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

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

add_option( 'll_default_width' , LIMELIGHT_WIDTH );
add_option( 'll_default_height' , LIMELIGHT_HEIGHT );
add_option( 'll_additional_flashvars' , "" );

function limelight_plugin_callback( $match ) {
	$tag_parts = explode(" ", rtrim($match[0] , ']' ));
	$flashvars = $tag_parts[1];
	$additional_flashvars = get_option( 'll_additional_flashvars' , '' );
	$output = LIMELIGHT_TARGET;
	$output = str_replace( '###FLASHVARS###' , "$additional_flashvars&$flashvars" , $output );

	if (array_key_exists(2, $tag_parts) && trim($tag_parts[2]) != "") {
		$width = $tag_parts[2];
	} else {
		$width = get_option( 'll_default_width' , LIMELIGHT_WIDTH );
	}
	$output = str_replace('###WIDTH###', $width, $output);

	if (array_key_exists(3, $tag_parts) && trim($tag_parts[3]) != "") {
		$height = $tag_parts[3];
	} else {
		$height = get_option( 'll_default_height' , LIMELIGHT_HEIGHT );
	}
	$output = str_replace( '###HEIGHT###' , $height , $output );

	return ( $output );
}

function limelight_plugin( $content ) {
	return ( preg_replace_callback( LIMELIGHT_REGEXP , 'limelight_plugin_callback' , $content ) );
}

add_filter( 'the_content', 'limelight_plugin' , 1 );
add_filter( 'the_content_rss', 'limelight_plugin' );
add_filter( 'the_excerpt', 'limelight_plugin' );




//////////////////////////////////////////////////////////////////////////////////////
// Adding the actions and hooks for admin menu, and embed-code short code replacement.
//////////////////////////////////////////////////////////////////////////////////////

class add_ll_button {

	var $pluginname = "limelight";

	function add_ll_button()	{
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
		$plugin_array[$this->pluginname] =	"$site_url/wp-content/plugins/limelight/editor_plugin.js";

		return $plugin_array;
	}

	function change_tinymce_version( $version ) {
		return ++$version;
	}
}

// Call it now
$ll_tinymce_button = new add_ll_button();
?>
