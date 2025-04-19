<?php
(defined('ABSPATH')) || exit;

add_action('admin_menu', 'mph_admin_menu');

/**
 * Fires before the administration menu loads in the admin.
 *
 * @param string $context Empty context.
 */
function mph_admin_menu(string $context): void
{

    $menu_suffix = add_menu_page(
        'کمک مالی',
        'کمک مالی',
        'manage_options',
        'mrpayhelp',
        'mph_menu_callback',
        'dashicons-money-alt',
        3
    );

    function mph_menu_callback()
    {

        $mphListTable = new Mph_List_Table;
        $mph_db = new Mph_Row();

        $par_page = 25;

        $offset = (isset($_GET[ 'paged' ])) ? ($par_page * absint($_GET[ 'paged' ])) - 1 : 0;

        $status = (isset($_GET[ 'status' ]) && $_GET[ 'status' ] !="all" ) ? sanitize_text_field($_GET[ 'status' ]) : "";

        $all_results = $mph_db->select($par_page, $offset,$status);


        $row = [
            $all_results, //all_results array
            $par_page, //par_page
            $mph_db->num(), //numsql
            $offset, //start at m-1
         ];

        require_once MPH_VIEWS . 'list.php';
    }

    add_submenu_page(
        'mrpayhelp',
        'لیست پرداخت',
        'لیست پرداخت',
        'manage_options',
        'mrpayhelp',
        'mph_menu_callback',
    );

    $mph_setting_suffix = add_submenu_page(
        'mrpayhelp',
        'تنظیمات اصلی',
        'تنظیمات اصلی',
        'manage_options',
        'mph_setting',
        'mph_setting_callback',
    );

    function mph_setting_callback()
    {

        $mph_option = get_option('mph_option');

        require_once MPH_VIEWS . 'setting.php';
    }

    $mph_text_suffix = add_submenu_page(
        'mrpayhelp',
        'متن ها',
        'متن ها',
        'manage_options',
        'text-view',
        'mph_texts_callback',
    );

    function mph_texts_callback()
    {

        $mph_option = get_option('mph_option');

        require_once MPH_VIEWS . 'texts.php';

    }

    $mph_cart_suffix = add_submenu_page(
        'mrpayhelp',
        'شماره کارت ها',
        'شماره کارت ها',
        'manage_options',
        'mph_cart',
        'mph_cart_callback',
    );

    function mph_cart_callback()
    {

        $mph_option = get_option('mph_option');
        require_once MPH_VIEWS . 'carts.php';

    }

    add_action('load-' . $menu_suffix, 'mph__submit');

    add_action('load-' . $mph_setting_suffix, 'mph__submit');

    add_action('load-' . $mph_text_suffix, 'mph__submit');

    add_action('load-' . $mph_cart_suffix, 'mph__submit');

    function mph__submit()
    {

        if (isset($_POST[ 'mph_act' ]) && $_POST[ 'mph_act' ] == 'mph__submit') {
            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'mph_nonce' . get_current_user_id())) {

                mph_update_option($_POST);

                set_transient('success_mph', 'تغییر با موفقیت ثبت شد');
            } else {
                set_transient('error_mph', 'ذخیره سازی به مشکل خورده دوباره تلاش کنید');

            }

        }

    }

}
