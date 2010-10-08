<?php
$site_url = "http://ec2-174-129-76-127.compute-1.amazonaws.com/wordpress";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Limelight</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script language="javascript" type="text/javascript" src="<?php echo $site_url ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $site_url ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $site_url ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
  <script language="javascript" type="text/javascript">
function writeShortCode() {
  if(window.tinyMCE) {
    window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, "[limelight channelId=2bc02e0995ee4e6e9fb62512a41af408]");
    //Peforms a clean up of the current editor HTML.
    //tinyMCEPopup.editor.execCommand('mceCleanup');
    //Repaints the editor. Sometimes the browser has graphic glitches.
    tinyMCEPopup.editor.execCommand('mceRepaint');
    tinyMCEPopup.close();
  }

  return
}
function handleChannels(data) {
  var html = "";
  // console.log(data);
  html += "<ul>";
  for (var m in data) {
    html += "<li>" + data[m].title + "</li>";
  }
  html += "</ul>";
  var cl = jQuery('iframe')[1].contentDocument.getElementById('channels_list');
  console.log(cl);
  cl.innerHTML = html;
  // document.write(html);
  //alert("Loaded " + data.length + " channels.");
}
</script>
<script language="javascript" type="text/javascript" src="http://api.delvenetworks.com/organizations/35cead0a66324a428fba2a4117707165/channels.js?callback=handleChannels"></script>
</head>
<body>

<div id="channels_list">

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