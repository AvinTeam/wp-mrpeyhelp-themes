<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', 'mph_style');

function mph_style()
{

    wp_register_style(
        'bootstrap',
        MPH_CSS . 'bootstrap.min.css',
        [  ],
        '5.3.3'
    );

    wp_register_style(
        'bootstrap_rtl',
        MPH_CSS . 'bootstrap.rtl.min.css',
        [ 'bootstrap' ],
        '5.3.3'
    );

    wp_enqueue_style(
        'mph_style',
        MPH_CSS . 'mph_style.css',
        [ 'bootstrap_rtl' ],
        MPH_VERSION
    );

    wp_register_script(
        'bootstrap',
        MPH_JS . 'bootstrap.bundle.min.js',
        [  ],
        '5.3.3',
        true
    );

    wp_enqueue_script(
        'mph_javascript',
        MPH_JS . 'mph_javascript.js',
        [ 'jquery', 'bootstrap' ],
        MPH_VERSION,
        true
    );




    wp_localize_script(
        'mph_javascript',
        'mph_script',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'mph_option' => get_option('mph_option'),
         ]
    );




}

add_action('admin_enqueue_scripts', 'mph_admin_script');

function mph_admin_script()
{
    wp_enqueue_style(
        'mr-aparat-admin-style',
        MPH_CSS . 'mph_admin_style.css',
        [  ],
        MPH_VERSION
    );

    wp_enqueue_style( 'wp-color-picker' );

    wp_enqueue_media();


    wp_enqueue_script(
        'mph_admin_javascript',
        MPH_JS . 'mph_admin_javascript.js',
        [ 'jquery','wp-color-picker' ],
        MPH_VERSION,
        true
    );

    wp_localize_script(
        'mph_admin_javascript',
        'ma_script',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'remove_btn_name' => esc_html__('remove item', 'mraparat'),
         ]
    );

}
