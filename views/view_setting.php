<?php
(defined('ABSPATH')) || exit;
global $title;?>

<div id="wpbody-content">
    <div class="wrap">
        <h1><?php echo esc_html($title) ?></h1>


        <hr class="wp-header-end">

        <?php if ($error = get_transient('error_mph')) {?>
        <div class="notice notice-error settings-error is-dismissible">
            <p><?php echo esc_html($error); ?></p>
        </div>
        <?php set_transient('error_mph', '');}?>

        <?php if ($success = get_transient('success_mph')) {?>
        <div class="notice notice-success settings-error is-dismissible">
            <p><?php echo esc_html($success); ?></p>
        </div>
        <?php set_transient('success_mph', '');}?>

        <form class="mph_form" method="post" action="" novalidate="novalidate">
            <?php wp_nonce_field('mph_nonce' . get_current_user_id());?>



            <p class="submit">
                <button type="submit" name="mph_act" value="mph__submit" id="submit"
                    class="button button-primary">ذخیرهٔ
                    تغییرات</button>
            </p>
        </form>

    </div>


    <div class="clear"></div>
</div>