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

        <form method="post" action="" novalidate="novalidate">
            <?php wp_nonce_field('mph_nonce' . get_current_user_id());?>

            <div id="mph_ideal">
                <div id="mph_ideal_ch">
                    <?php $m = 1;foreach ($mph_option[ 'cart' ] as $cart): if ($cart == "") {continue;}?>
                    <div class="ma_row" id="ma_item_<?php echo absint($m) ?>">
                        <div class="ma_col_5">
                            <input name="cart[]" onchange="ma_aparat_link(<?php echo absint($m) ?>)" type="text"
                                id="mph_item<?php echo absint($m) ?>" class="regular-text"
                                value="<?php echo esc_html($cart) ?>">
                            <button type="button" class="button  mph_btn_remove"
                                onclick="mphremove(<?php echo absint($m) ?>)">حذف</button>
                        </div>
                    </div>
                    <?php $m++;endforeach?>
                </div>
                <button type="button" class="button  mph_btn_add" id="mph_btn_add">اضافه</button>
            </div>
            <p class="submit">
                <button type="submit" name="mph_act" value="mph__submit" id="submit"
                    class="button button-primary">ذخیرهٔ
                    تغییرات</button>
            </p>
        </form>

    </div>


    <div class="clear"></div>
</div>