<?php
/**
 * The HTML for post-editing popup window.
 *
 */

// Define Wordpress load path
if ( !defined('WP_LOAD_PATH') ) {
  /** classic root path if wp-content and plugins is below wp-config.php */
  $classic_root = dirname(dirname(dirname(dirname(__FILE__)))) . '/' ;
  if (file_exists( $classic_root . 'wp-load.php') )
    define( 'WP_LOAD_PATH', $classic_root);
  else
    exit("Could not find wp-load.php");
}

// Load Wordpress
require_once(WP_LOAD_PATH.'wp-load.php');

// check for rights
if ( !is_user_logged_in() || !current_user_can('edit_posts') )
 wp_die(__("You are not allowed to be here"));

// Site URL
$site_url = get_option('siteurl');

// get the organziation id
$ll_org_id = get_option('ll_org_id');

// Cache
function request_cache($url, $key, $timeout=7200) {
  $cache_file_key = 'limelight_'.$key.'_cache_file';
  $cache_file = get_option($cache_file_key);
  if(!file_exists($cache_file) || filemtime($cache_file) < (time()-$timeout)) {
    $data = file_get_contents($url);
    if ($data === false) return false;
    $tmpf = tempnam(sys_get_temp_dir(),$cache_file_key);
    $fp = fopen($tmpf,"w");
    fwrite($fp, $data);
    fclose($fp);
    update_option($cache_file_key, $tmpf);
  } else {
    return file_get_contents($cache_file);
  }
  return($data);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Limelight Videos</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <script language="javascript" type="text/javascript" src="<?php echo $site_url ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $site_url ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $site_url ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
  <script language="javascript" type="text/javascript">
  function init() {
    tinyMCEPopup.resizeToInnerSize();
  }

  function writeShortCode(flashVars, width, height) {
    if(window.tinyMCE) {
      window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, "[limelight " + flashVars + " " + width + " " + height + "]");
      //Peforms a clean up of the current editor HTML.
      //tinyMCEPopup.editor.execCommand('mceCleanup');
      //Repaints the editor. Sometimes the browser has graphic glitches.
      tinyMCEPopup.editor.execCommand('mceRepaint');
      tinyMCEPopup.close();
    }
    return false;
  }

  function select_channel() {
    var channelSelect = document.getElementById('channel_select');
    var width = document.getElementById('channel_width').value;
    var height = document.getElementById('channel_height').value;
    writeShortCode(channel_select.value + "&playerForm=DelvePlaylistPlayer", width, height);
  }

  function select_media() {
    var mediaSelect = document.getElementById('media_select');
    var width = document.getElementById('media_width').value;
    var height = document.getElementById('media_height').value;
    writeShortCode(media_select.value, width, height);
  }
</script>
</head>
<body onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';document.getElementById('mediatag').focus();" style="display: none">
<?php
  if ($ll_org_id != "" && strlen($ll_org_id) == 32) {
?>
<div class="tabs">
  <ul>
    <li id="channels_tab" class="current"><span><a href="javascript:mcTabs.displayTab('channels_tab','channels_panel');" onmousedown="return false;">Channels</a></span></li>
    <li id="media_tab"><span><a href="javascript:mcTabs.displayTab('media_tab','media_panel');" onmousedown="return false;">Media</a></span></li>
  </ul>
</div>
<div class="panel_wrapper">
  <!-- media panel -->
  <div id="media_panel" class="panel">
    <p>Media</p>
    <select id="media_select">
    <?php
      $media_url = "http://api.delvenetworks.com/organizations/$ll_org_id/media.json";
      $media_json = request_cache($media_url, 'media');
      $media_list = json_decode($media_json);
      $count = count($media_list);
      for ($i = 0; $i < $count; $i++) {
          $title = $media_list[$i]->title;
          $id = $media_list[$i]->media_id;
          echo "<option value=\"mediaId=$id\">$title</option>\n";
      }
    ?>
    </select>
    <p>Width</p>
    <input id="media_width" type="text" value="" />
    <p>Height</p>
    <input id="media_height" type="text" value="" />
    <div class="mceActionPanel">
      <div style="float: left">
        <input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
      </div>

      <div style="float: right">
        <input type="submit" id="insert" name="insert" value="Insert" onclick="select_media();" />
      </div>
    </div>
  </div>
  <div id="channels_panel" class="panel current">
    <p>Channel</p>
    <select id="channel_select">
    <?php
      $url = "http://api.delvenetworks.com/organizations/$ll_org_id/channels.json";
      $channels_json = request_cache($url, 'channels');
      $channels_list = json_decode($channels_json);
      $count = count($channels_list);
      for ($i = 0; $i < $count; $i++) {
          $title = $channels_list[$i]->title;
          $id = $channels_list[$i]->channel_id;
          echo "<option value=\"channelId=$id\">$title</option>\n";
      }
    ?>
    </select>
    <p>Width</p>
    <input id="channel_width" type="text" value="" />
    <p>Height</p>
    <input id="channel_height" type="text" value="" />
    <div class="mceActionPanel">
      <div style="float: left">
        <input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
      </div>

      <div style="float: right">
        <input type="submit" id="insert" name="insert" value="Insert" onclick="select_channel();" />
      </div>
    </div>
  </div>
</div>
<?php } else { ?>
  <p>You must enter in your organization id in the settings page.</p>
  <div class="mceActionPanel">
    <div style="float: left">
      <input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
    </div>
  </div>
<?php } ?>

</body>
</html>