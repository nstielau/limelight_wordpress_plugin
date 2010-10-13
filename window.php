<?php
/*
+----------------------------------------------------------------+
+	wordtube-tinymce V1.60
+	by Alex Rabe
+----------------------------------------------------------------+
*/

$site_url = "http://ec2-174-129-76-127.compute-1.amazonaws.com/wordpress";

// look up for the path
require_once(dirname(__FILE__).'/limelight-config.php');

// check for rights
if ( !is_user_logged_in() || !current_user_can('edit_posts') )
 wp_die(__("You are not allowed to be here"));

// get the organziation id
$ll_org_id = get_option('ll_org_id');


function request_cache($url, $key, $timeout=7200) {
  $cache_file = get_option('limelight_'.$key.'_cache');
	if(!file_exists($cache_file) || filemtime($cache_file) < (time()-$timeout)) {
	  echo "Not cached<br/>";
		$data = file_get_contents($url);
		if ($data === false) return false;
		// $tmpf = tempnam(WP_LOAD_PATH.'/wp-content','limelight_cache');
		$tmpf = tmpfile();
		$fp = fopen($tmpf,"w");
		fwrite($fp, $data);
		fclose($fp);
    update_option('limelight_'.$key.'_cache', $tmp);
	} else {
	  echo "Cached<br/>";
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

  function writeShortCode(flashVars) {
    if(window.tinyMCE) {
      window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, "[limelight " + flashVars + "]");
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
    writeShortCode(channel_select.value);
  }

  function select_media() {
    var mediaSelect = document.getElementById('media_select');
    writeShortCode(media_select.value);
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
	  <?php
  $media_url = "http://api.delvenetworks.com/organizations/35cead0a66324a428fba2a4117707165/media.json";
  $media_json = request_cache($media_url, 'media');
	  ?>
    <p>Select Media</p>
    <select id="media_select">
    <?php
      $media_list = json_decode($media_json);
      $count = count($media_list);
      for ($i = 0; $i < $count; $i++) {
          $title = $media_list[$i]->title;
          $id = $media_list[$i]->media_id;
          echo "<option value=\"mediaId=$id\">$title</option>\n";
      }
    ?>
    </select>
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
    <p>Select Channel</p>
    <select id="channel_select">
    <?php
      $url = "http://api.delvenetworks.com/organizations/35cead0a66324a428fba2a4117707165/channels.json";
      $channels_json = file_get_contents($url);
      $channels_list = json_decode($channels_json);
      $count = count($channels_list);
      for ($i = 0; $i < $count; $i++) {
          $title = $channels_list[$i]->title;
          $id = $channels_list[$i]->channel_id;
          echo "<option value=\"channelId=$id\">$title</option>\n";
      }
    ?>
    </select>
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