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

        <form method="post" action="" novalidate="novalidate" class="mph_form">
            <?php wp_nonce_field('mph_nonce' . get_current_user_id());?>
            <table class="form-table" role="presentation">
            <caption>تنظیمات مالی</caption>

                <tbody>
                    <tr>
                        <th scope="row">نام و نام خانوادگی</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>
                                        تعداد واحد</span></legend><label for="user_name">
                                    <input name="user_name" type="checkbox" id="users_can_unit" value="1"
                                        <?php if ($mph_option[ 'user_name' ]) {echo 'checked';}?>>
                                    کاربر نام و نام خانوادگی را وارد کند؟</label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">استان</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>
                                        تعداد واحد</span></legend><label for="ostan">
                                    <input name="ostan" type="checkbox" id="users_can_unit" value="1"
                                        <?php if ($mph_option[ 'ostan' ]) {echo 'checked';}?>>
                                    کاربر استان را انتخاب کند؟</label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">وارد کردن تعداد واحد</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>
                                        تعداد واحد</span></legend><label for="users_can_unit">
                                    <input name="users_can_unit" type="checkbox" id="users_can_unit" value="1"
                                        <?php if ($mph_option[ 'users_can_unit' ]) {echo 'checked';}?>>
                                    کاربر تعداد واحد را انتخاب کند؟</label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="payInput">مبلغ به ازای هر واحد (ریال)</label></th>
                        <td><input name="pay" type="text" id="payInput"
                                value="<?php echo number_format(esc_html($mph_option[ 'pay' ])); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="all_unit">حداکثر تعداد واحد</label></th>
                        <td><input name="all_unit" type="text" id="all_unit"
                                value="<?php echo number_format(esc_html($mph_option[ 'all_unit' ])); ?>"
                                class="regular-text"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="name_unit">عنوان واحد</label></th>
                        <td><input name="name_unit" type="text" id="name_unit"
                                value="<?php echo esc_html($mph_option[ 'name_unit' ]); ?>"
                                class="regular-text"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="merchant_id">مرچند کد</label></th>
                        <td><input name="merchant_id" type="text" id="merchant_id"
                                aria-describedby="tagline-description"
                                value="<?php echo esc_html($mph_option[ 'merchant_id' ]); ?>" class="regular-text">
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="form-table" role="presentation">
            <caption>تنظیمات ظاهری</caption>
                <tbody>
                    <tr>
                        <th scope="row"><label for="body_color">رنگ پس زمینه</label></th>
                        <td><input name="body_color" type="text" id="body_color"
                                value="<?php echo esc_html($mph_option[ 'view' ][ 'body_color' ]); ?>"
                                class="regular-text input_color">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="button_color">رنگ دکمه پرداخت</label></th>
                        <td><input name="button_color" type="text" id="button_color"
                                value="<?php echo esc_html($mph_option[ 'view' ][ 'button_color' ]); ?>"
                                class="regular-text input_color">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="input_border_color">برنگ حاشیه فیلد ها</label></th>
                        <td>
                            <input name="input_border_color" type="text" id="input_border_color"
                                value="<?php echo esc_html($mph_option[ 'view' ][ 'input_border_color' ]); ?>"
                                class="regular-text input_color">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="input_label_color">برنگ متن فیلد ها</label></th>
                        <td>
                            <input name="input_label_color" type="text" id="input_label_color"
                                value="<?php echo esc_html($mph_option[ 'view' ][ 'input_label_color' ]); ?>"
                                class="regular-text input_color">
                        </td>
                    </tr>

                    <tr class="form-field form-required term-name-wrap mph_logo">
                        <th scope="row"><label for="header_image">انتخاب هدر سایت</label></th>
                        <td><input name="header_image" id="header_image" type="url"
                                value="<?=$mph_option[ 'logo' ][ 'header_image' ]?>" class="mph_img_input regular-text " aria-describedby="header_image"><br>
                            <button type="button" class="button button-secondary mph_select_img"
                                id="mph_logo">انتخاب تصویر</button>
                            <p class="description" id="url_logo_description"><img style="max-height: 108px;"
                                    src="<?=$mph_option[ 'logo' ][ 'header_image' ]?>">
                            </p>
                        </td>
                    </tr>
                    <tr class="form-field term-name-wrap mph_background">
                        <th scope="row"><label for="footer_image">انتخاب فوتر سایت</label></th>
                        <td><input name="footer_image" id="footer_image" type="url"
                                value="<?=$mph_option[ 'logo' ][ 'footer_image' ]?>" class="mph_img_input regular-text "
                                aria-describedby="url_background_description"><br>
                            <button type="button" class="button button-secondary mph_select_img"
                                id="mph_background">انتخاب
                                بنر</button>
                            <p class="description" id="url_background_description"><img style="max-height: 108px;"
                                    src="<?=$mph_option[ 'logo' ][ 'footer_image' ]?>">
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p class="submit">
                <button type="submit" name="mph_act" value="mph__submit" id="submit" class="button button-primary">ذخیرهٔ
                    تغییرات</button>
            </p>
        </form>

    </div>


    <div class="clear"></div>
</div>






