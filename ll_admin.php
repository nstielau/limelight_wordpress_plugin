<?php
/**
 * The HTML for the admin options page.
 *
 */

if($_POST['limelight_hidden'] == 'Y') {
  //Form data sent
  $ll_org_id = $_POST['ll_org_id'];
  update_option('ll_org_id', $ll_org_id);

  $ll_default_width = $_POST['ll_default_width'];
  update_option('ll_default_width', $ll_default_width);

  $ll_default_height = $_POST['ll_default_height'];
  update_option('ll_default_height', $ll_default_height);

  $ll_additional_flashvars = $_POST['ll_additional_flashvars'];
  update_option('ll_additional_flashvars', $ll_additional_flashvars);
  ?>
  <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
  <?php
} else {
  //Normal page display
  $ll_org_id = get_option('ll_org_id');
  $ll_default_width = get_option('ll_default_width');
  $ll_default_height = get_option('ll_default_height');
}
?>

<div class="wrap">
  <?php    echo "<h2>" . __( 'Limelight Video Options', 'limelight_text_domain' ) . "</h2>"; ?>
  <form name="limelight_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="limelight_hidden" value="Y">

    <?php    echo "<h4>" . __( 'Limelight Embed Code Settings', 'limelight_text_domain' ) . "</h4>"; ?>
    <p><?php _e("Video player width (px): " ); ?><input type="text" name="ll_default_width" value="<?php echo $ll_default_width; ?>" size="5"><?php _e(" ex: 480" ); ?></p>
    <p><?php _e("Video player height (px): " ); ?><input type="text" name="ll_default_height" value="<?php echo $ll_default_height; ?>" size="5"><?php _e(" ex: 411" ); ?></p>
    <p><em>Warning</em>: This will change the height and width for any videos that don&quot;t have specific dimensions set. </p>
    <p><?php _e("Additional Flashvars: " ); ?><input type="text" name="ll_additional_flashvars" value="<?php echo $ll_additional_flashvars; ?>" size="40"><?php _e(" ex: deepLink=true" ); ?></p>
    <p>These Flashvars will be added before any specific Flashvars</p>

    <?php    echo "<h4>" . __( 'Limelight API Settings', 'limelight_text_domain' ) . "</h4>"; ?>
    <p><?php _e("Organization ID: " ); ?><input type="text" name="ll_org_id" value="<?php echo $ll_org_id; ?>" size="32"><?php _e(" ex: 1fcedd0a66334ac28fbb2a4117707145" ); ?></p>

    <p class="submit">
    <input type="submit" name="Submit" value="<?php _e('Update Options', 'limelight_text_domain' ) ?>" />
    </p>
  </form>
</div>

