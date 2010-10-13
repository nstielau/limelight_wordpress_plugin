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
function writeShortCode(id) {
  if(window.tinyMCE) {
    window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, "[limelight channelId=" + id + "]");
    //Peforms a clean up of the current editor HTML.
    //tinyMCEPopup.editor.execCommand('mceCleanup');
    //Repaints the editor. Sometimes the browser has graphic glitches.
    tinyMCEPopup.editor.execCommand('mceRepaint');
    tinyMCEPopup.close();
  }

  return false;
}
</script>
</head>
<body>

<div id="channels_list">
<?php
  if ($ll_org_id != "") {
    echo "<h2>Channels for $ll_org_id</h2>";
    $url = "http://api.delvenetworks.com/organizations/35cead0a66324a428fba2a4117707165/channels.json";
    $channels_json = file_get_contents($url);
    $channels_list = json_decode($channels_json);
    $count = count($channels_list);
    for ($i = 0; $i < $count; $i++) {
        $title = $channels_list[$i]->title;
        $id = $channels_list[$i]->channel_id;
        echo "<a hef=\"#\" onclick=\"writeShortCode('$id');\">$title</a><br/>\n";
    }
  } else {
    echo "You must enter in your organization id in the settings page.";
  }
?>

</div>

<div class="mceActionPanel">
	<div style="float: left">
		<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
	</div>

	<div style="float: right">
		<input type="submit" id="insert" name="insert" value="Insert" onclick="writeShortCode();" />
	</div>
</div>

</body>
</html>