<?php
$site_url = "http://ec2-174-129-76-127.compute-1.amazonaws.com/wordpress";
function request_cache($url, $dest_file, $timeout=7200) {
	if(!file_exists($dest_file) || filemtime($dest_file) < (time()-$timeout)) {
		$data = file_get_contents($url);
		if ($data === false) return false;
		$tmpf = tempnam('.','YWS');
		$fp = fopen($tmpf,"w");
		fwrite($fp, $data);
		fclose($fp);
		rename($tmpf, $dest_file);
	} else {
		return file_get_contents($dest_file);
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
var myWindow = window;
function handleChannels(data) {
  var html = "";
  // console.log(data);
  html += "<ul>";
  for (var m in data) {
    html += "<li>" + data[m].title + "</li>";
  }
  html += "</ul>";
  myWindow = window;
  console.log(window);
  console.log(window.document);
  var cl = window.document.getElementById('channels_list');
  console.log(cl);

  cl.innerHTML = html;
  // document.write(html);
  //alert("Loaded " + data.length + " channels.");
}
</script>
</head>
<body>

<div id="channels_list">
  <?php
  $url = "http://api.delvenetworks.com/organizations/35cead0a66324a428fba2a4117707165/channels.json";
  $ttl = 60;
  $channels_json = request_cache($url, 'channel_list_cache');
  $channels_list = json_decode($channels_json);
  $count = count($channels_list);
  for ($i = 0; $i < $count; $i++) {
      $title = $channels_list[$i]->title;
      $id = $channels_list[$i]->channel_id;
      echo "<a hef=\"#\" onclick=\"writeShortCode('$id');\">$title</a><br/>\n";
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