<?php
(defined('ABSPATH')) || exit;

add_action('wp_dashboard_setup', 'mph_dashboard_widget');
function mph_dashboard_widget()
{

    wp_add_dashboard_widget(
        'mph_dashboard',
        'خلاصه تراکنش ها',
        'mph_dashboard_callback',
        null,
        null,
        'normal',
        'high'
    );

    function mph_dashboard_callback()
    {

        $mph_db = new Mph_Row();

        $mph_all_row = $mph_db->selectall(10, 'ORDER BY `created_at` DESC');

        if ($mph_db->num() > 0) {
            include_once MPH_VIEWS . 'dashboard_widget.php';
        } else {
            echo '<p>هیچ تراکنشی انجام نشده است.</p>';
        }

    }

}
