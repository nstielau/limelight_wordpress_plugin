<?php
/**
 * The HTML for the admin options page.
 *
 * @package WordPress
 */
  if($_POST['oscimp_hidden'] == 'Y') {
    //Form data sent
    $ll_org_id = $_POST['ll_org_id'];
    update_option('ll_org_id', $ll_org_id);

    $ll_default_width = $_POST['ll_default_width'];
    update_option('ll_default_width', $ll_default_width);

    $ll_default_height = $_POST['ll_default_height'];
    update_option('ll_default_height', $ll_default_height);
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

    <?php    echo "<h4>" . __( 'Limelight API Settings', 'limelight_text_domain' ) . "</h4>"; ?>
    <p><?php _e("Organization ID: " ); ?><input type="text" name="ll_org_id" value="<?php echo $ll_org_id; ?>" size="32"><?php _e(" ex: 1fcedd0a66334ac28fbb2a4117707145" ); ?></p>

    <p class="submit">
    <input type="submit" name="Submit" value="<?php _e('Update Options', 'limelight_text_domain' ) ?>" />
    </p>
  </form>
</div>

