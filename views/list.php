<?php (defined('ABSPATH')) || exit;
global $title;?>
<div class="wrap mph_big_style">


    <h1 class="wp-heading-inline"> <?php echo esc_html($title) ?></h1>

    <?php

$mphListTable->mph_res($row);
$mphListTable->prepare_items();

echo '<form method="post" action=""';
$mphListTable->views();

$mphListTable->display();

wp_nonce_field('mph_nonce' . get_current_user_id());

?>

    </form>

</div>


<div class="clear"></div>