<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>wordTube</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>WInDow
<script language="javascript" type="text/javascript">
function writeShortCode() {
  if(window.tinyMCE) {
    window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, "[limelight blah]");
    //Peforms a clean up of the current editor HTML.
    //tinyMCEPopup.editor.execCommand('mceCleanup');
    //Repaints the editor. Sometimes the browser has graphic glitches.
    tinyMCEPopup.editor.execCommand('mceRepaint');
    tinyMCEPopup.close();
  }

  return;
}
</script>
<body>

  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

<div class="mceActionPanel">
	<div style="float: left">
		<input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'wpTube'); ?>" onclick="tinyMCEPopup.close();" />
	</div>

	<div style="float: right">
		<input type="submit" id="insert" name="insert" value="<?php _e("Insert", 'wpTube'); ?>" onclick="insertwpTubeLink();" />
	</div>
</div>

</body>
</html>