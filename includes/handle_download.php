<?php

(defined('ABSPATH')) || exit;

add_action('admin_init', 'handle_download');

function handle_download()
{
    if (isset($_GET[ 'action' ]) && $_GET[ 'action' ] === 'download_csv') {
        // بررسی سطح دسترسی
        if (!current_user_can('manage_options')) {
            wp_die(__('دسترسی ندارید.', 'your-textdomain'));
        }

        $mph_db = new Mph_Row();

        $status = (isset($_GET[ 'status' ]) && $_GET[ 'status' ] != "all") ? sanitize_text_field($_GET[ 'status' ]) : "";

        $results = $mph_db->selecttype($status);

        $data = Change_the_heade($results);

        $filename = date('Y-m-d') . ".csv";

        // ارسال هدرهای HTTP برای دانلود CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // باز کردن خروجی برای نوشتن داده‌ها
        $output = fopen('php://output', 'w');

        // نوشتن هدرها (نام ستون‌ها)
        if (!empty($data)) {
            fputcsv($output, array_keys($data[ 0 ]));
        }

        // نوشتن داده‌ها
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    if (isset($_GET[ 'action' ]) && $_GET[ 'action' ] === 'download_csv') {
        // بررسی سطح دسترسی
        if (!current_user_can('manage_options')) {
            wp_die(__('دسترسی ندارید.', 'your-textdomain'));
        }

        $mph_db = new Mph_Row();

        $status = (isset($_GET[ 'status' ]) && $_GET[ 'status' ] != "all") ? sanitize_text_field($_GET[ 'status' ]) : "";

        $results = $mph_db->selecttype($status);

        $data = Change_the_heade($results);

        $filename = jdate('Y-m-j') . ".csv";

        // ارسال هدرهای HTTP برای دانلود CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // باز کردن خروجی برای نوشتن داده‌ها
        $output = fopen('php://output', 'w');

        // نوشتن هدرها (نام ستون‌ها)
        if (!empty($data)) {
            fputcsv($output, array_keys($data[ 0 ]));
        }

        // نوشتن داده‌ها
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    if (isset($_GET[ 'action' ]) && $_GET[ 'action' ] === 'download_exel') {
        // بررسی سطح دسترسی
        if (!current_user_can('manage_options')) {
            wp_die(__('دسترسی ندارید.', 'your-textdomain'));
        }

        $mph_db = new Mph_Row();

        $status = (isset($_GET[ 'status' ]) && $_GET[ 'status' ] != "all") ? sanitize_text_field($_GET[ 'status' ]) : "";

        $results = $mph_db->selecttype($status);

        $data = Change_the_heade($results);

        function filterData(&$str)
        {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if (strstr($str, '"')) {
                $str = '"' . str_replace('"', '""', $str) . '"';
            }

        }

        // file name for download
        $fileName = jdate('Y-m-j') . ".xls";

        // headers for download
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-type: application/octet-stream");
        header('Content-Transfer-Encoding: binary');
        header("Pragma: no-cache");
        header("Expires: 0");

        $flag = false;
        foreach ($data as $row) {
            if (!$flag) {
                // display column names as first row
                $key1 = implode("\t", array_keys($row)) . "\n";

                echo chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE", $key1);
                $flag = true;
            }
            // filter data
            array_walk($row, 'filterData');
            $key2 = implode("\t", array_values($row)) . "\n";
            echo chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE", $key2);
        }

    exit;

    }

}
