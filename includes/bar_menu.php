<?php
(defined('ABSPATH')) || exit;

add_action('admin_bar_menu', 'admin_bar_item', 500);
function admin_bar_item(WP_Admin_Bar $admin_bar)
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $mph_db = new Mph_Row();

    $admin_bar->add_menu([
        'id' => 'mph-total-pey',
        'parent' => null,
        'group' => null,
        'title' => "مبلغ کمک شده " . number_format($mph_db->sum()) . " ریال",
        'href' => admin_url('admin.php?page=mrpayhelp'),
     ]);

}