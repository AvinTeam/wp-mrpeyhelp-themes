<?php

(defined('ABSPATH')) || exit;
function mpn_row_install()
{
    global $wpdb;
    $tabel_mpn_row = $wpdb->prefix . 'mpn_row';
    $wpdb_collate_mpn_row = $wpdb->collate;
    $sql = "

    CREATE TABLE IF NOT EXISTS `$tabel_mpn_row` (
        `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
        `user_name` varchar(250) COLLATE $wpdb_collate_mpn_row NOT NULL,
        `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE $wpdb_collate_mpn_row NOT NULL,
        `ostan` int unsigned NOT NULL,
        `city` int unsigned NOT NULL,
        `amount` bigint unsigned NOT NULL,
        `payid` varchar(200) CHARACTER SET utf8mb4 COLLATE $wpdb_collate_mpn_row NOT NULL,
        `type` varchar(20) COLLATE $wpdb_collate_mpn_row NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`ID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate_mpn_row COMMENT='This table is for recording mph records'";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta($sql);

}

add_action('after_switch_theme', 'mpn_row_install');

