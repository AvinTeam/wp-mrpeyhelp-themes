<?php
(defined('ABSPATH')) || exit;

function mph_start_working(): void
{

    $mph_option = get_option('mph_option');

    if (! is_plugin_active('mrsendesms/mrsendesms.php')) {
        $mph_option[ 'send_sms' ] = 0;

        update_option('mph_option', $mph_option);
    }

    if (! isset($mph_option[ 'version' ]) || version_compare(MPH_VERSION, $mph_option[ 'version' ], '>')) {

        update_option(
            'mph_option',
            [
                'version'        => MPH_VERSION,
                'user_name'      => (isset($mph_option[ 'user_name' ])) ? filter_var($mph_option[ 'user_name' ], FILTER_VALIDATE_BOOLEAN) : false,
                'users_can_unit' => (isset($mph_option[ 'users_can_unit' ])) ? filter_var($mph_option[ 'users_can_unit' ], FILTER_VALIDATE_BOOLEAN) : false,
                'ostan'          => (isset($mph_option[ 'ostan' ])) ? filter_var($mph_option[ 'ostan' ], FILTER_VALIDATE_BOOLEAN) : false,
                'pay'            => (isset($mph_option[ 'pay' ])) ? $mph_option[ 'pay' ] : 0,
                'all_unit'       => (isset($mph_option[ 'all_unit' ])) ? $mph_option[ 'all_unit' ] : 500,
                'name_unit'      => (isset($mph_option[ 'name_unit' ])) ? sanitize_text_field($mph_option[ 'name_unit' ]) : 'پرس',
                'merchant_id'    => (isset($mph_option[ 'merchant_id' ])) ? $mph_option[ 'merchant_id' ] : '',
                'send_sms'       => (isset($mph_option[ 'send_sms' ])) ? $mph_option[ 'send_sms' ] : 0,
                'send_sms_text'       => (isset($mph_option[ 'send_sms_text' ])) ? $mph_option[ 'send_sms_text' ] : '',

                'view'           => [
                    'button_color'       => (isset($mph_option[ 'view' ][ 'button_color' ])) ? $mph_option[ 'view' ][ 'button_color' ] : '#daae5c',
                    'button_color_text'       => (isset($mph_option[ 'view' ][ 'button_color_text' ])) ? $mph_option[ 'view' ][ 'button_color_text' ] : '#FFFFFF',
                    'input_border_color' => (isset($mph_option[ 'view' ][ 'input_border_color' ])) ? $mph_option[ 'view' ][ 'input_border_color' ] : '#daae5c',
                    'input_label_color'  => (isset($mph_option[ 'view' ][ 'input_label_color' ])) ? $mph_option[ 'view' ][ 'input_label_color' ] : '#daae5c',
                    'input_text_color'  => (isset($mph_option[ 'view' ][ 'input_text_color' ])) ? $mph_option[ 'view' ][ 'input_text_color' ] : '#FFFFFF',
                    'body_color'         => (isset($mph_option[ 'view' ][ 'body_color' ])) ? $mph_option[ 'view' ][ 'body_color' ] : '#1b3f75',
                 ],
                'logo'           => [
                    'header_image' => (isset($mph_option[ 'logo' ][ 'header_image' ])) ? $mph_option[ 'logo' ][ 'header_image' ] : '',
                    'footer_image' => (isset($mph_option[ 'logo' ][ 'footer_image' ])) ? $mph_option[ 'logo' ][ 'footer_image' ] : '',

                 ],
                'text'           => [
                    'header_text' => (isset($mph_option[ 'text' ][ 'header_text' ])) ? wp_kses_post(wp_unslash($mph_option[ 'text' ][ 'header_text' ])) : '',
                    'footer_text' => (isset($mph_option[ 'text' ][ 'footer_text' ])) ? wp_kses_post(wp_unslash($mph_option[ 'text' ][ 'footer_text' ])) : '',

                 ],
                'cart'           => (isset($mph_option[ 'cart' ])) ? $mph_option[ 'cart' ] : [  ],
             ]

            //
        );
        // Clear WP Super Cache if needed
        if (function_exists("wp_cache_flush")) {
            wp_cache_flush();
        }

    }

    $mph_db = new Mph_Row();

    $mph_db->update_type();

}

function mph_remote(string $url)
{
    $res = wp_remote_get(
        $url,
        [
            'timeout' => 1000,
         ]);

    if (is_wp_error($res)) {
        $result = [
            'code'   => 1,
            'result' => $res->get_error_message(),
         ];
    } else {
        $result = [
            'code'   => 0,
            'result' => json_decode($res[ 'body' ]),
         ];
    }

    return $result;
}

function mph_add_param($url, $params)
{
    // تجزیه URL
    $parsedUrl = parse_url($url);

    // مدیریت query string
    $query = isset($parsedUrl[ 'query' ]) ? $parsedUrl[ 'query' ] : '';
    parse_str($query, $queryParams);

    // اضافه کردن پارامترهای جدید
    foreach ($params as $key => $value) {
        $queryParams[ $key ] = $value;
    }

    // بازسازی query string
    $newQuery = http_build_query($queryParams);

    // بازسازی URL
    $newUrl = $parsedUrl[ 'scheme' ] . '://' . $parsedUrl[ 'host' ];
    if (isset($parsedUrl[ 'path' ])) {
        $newUrl .= $parsedUrl[ 'path' ];
    }
    if ($newQuery) {
        $newUrl .= '?' . $newQuery;
    }

    return $newUrl;
}

function mph_update_option($data)
{

    $mph_option = get_option('mph_option');

    $mph_option = [
        'version'        => MPH_VERSION,
        'user_name'      => (isset($data[ 'user_name' ])) || false,
        'users_can_unit' => (isset($data[ 'users_can_unit' ])) || false,
        'ostan'          => (isset($data[ 'ostan' ])) || false,
        'all_unit'       => (isset($data[ 'all_unit' ])) ? sanitize_text_field(str_replace(',', '', $data[ 'all_unit' ])) : $mph_option[ 'all_unit' ],
        'name_unit'      => (isset($data[ 'name_unit' ])) ? sanitize_text_field($data[ 'name_unit' ]) : $mph_option[ 'name_unit' ],
        'pay'            => (isset($data[ 'pay' ])) ? sanitize_text_field(str_replace(',', '', $data[ 'pay' ])) : $mph_option[ 'pay' ],
        'merchant_id'    => (isset($data[ 'merchant_id' ])) ? $data[ 'merchant_id' ] : $mph_option[ 'merchant_id' ],
        'send_sms'       => (isset($data[ 'send_sms' ])) ? $data[ 'send_sms' ] : $mph_option[ 'send_sms' ],
        'send_sms_text'       => (isset($data[ 'send_sms_text' ])) ? $data[ 'send_sms_text' ] : $mph_option[ 'send_sms_text' ],

        'view'           => [
            'button_color'       => (isset($data[ 'button_color' ])) ? sanitize_hex_color($data[ 'button_color' ]) : $mph_option[ 'view' ][ 'button_color' ],
            'button_color_text'       => (isset($data[ 'button_color_text' ])) ? sanitize_hex_color($data[ 'button_color_text' ]) : $mph_option[ 'view' ][ 'button_color_text' ],
            'input_border_color' => (isset($data[ 'input_border_color' ])) ? sanitize_hex_color($data[ 'input_border_color' ]) : $mph_option[ 'view' ][ 'input_border_color' ],
            'input_label_color'  => (isset($data[ 'input_label_color' ])) ? sanitize_hex_color($data[ 'input_label_color' ]) : $mph_option[ 'view' ][ 'input_label_color' ],
            'input_text_color'  => (isset($data[ 'input_text_color' ])) ? sanitize_hex_color($data[ 'input_text_color' ]) : $mph_option[ 'view' ][ 'input_text_color' ],
            'body_color'         => (isset($data[ 'body_color' ])) ? sanitize_hex_color($data[ 'body_color' ]) : $mph_option[ 'view' ][ 'body_color' ],
         ],

        'logo'           => [
            'header_image' => (isset($data[ 'header_image' ])) ? $data[ 'header_image' ] : $mph_option[ 'logo' ][ 'header_image' ],
            'footer_image' => (isset($data[ 'footer_image' ])) ? $data[ 'footer_image' ] : $mph_option[ 'logo' ][ 'footer_image' ],

         ],

        'text'           => [
            'header_text' => (isset($data[ 'header_text' ])) ? wp_kses_post(wp_unslash($data[ 'header_text' ])) : $mph_option[ 'text' ][ 'header_text' ],
            'footer_text' => (isset($data[ 'footer_text' ])) ? wp_kses_post(wp_unslash($data[ 'footer_text' ])) : $mph_option[ 'text' ][ 'footer_text' ],

         ],

        'cart'           => (isset($data[ 'cart' ])) ? $data[ 'cart' ] : $mph_option[ 'cart' ],

     ];

    update_option('mph_option', $mph_option);

}

function tarikh($data, $time = "")
{
    $data1 = "";
    if (! empty($data)) {
        $arr  = explode(" ", $data);
        $data = $arr[ 0 ];

        $arrayData = [ '/', '-' ];

        foreach ($arrayData as $arrayData) {
            $x = explode($arrayData, $data);
            if (sizeof($x) == 3) {

                list($gy, $gm, $gd) = explode($arrayData, $data);

                if ($arrayData == '/') {
                    $tagir = '-';
                    $chen  = 'jalali_to_gregorian';
                }
                if ($arrayData == '-') {
                    $tagir = '/';
                    $chen  = 'gregorian_to_jalali';
                }

                $data1 = $chen($gy, $gm, $gd, $tagir);

                break;
            }

        }

        if ($time == "d") {
            $data1 = $data1;
        } elseif ($time == "t") {
            $data1 = $arr[ 1 ];
        } else {
            $data1 = $data1 . " " . $arr[ 1 ];
        }
    }
    return $data1;
}

function get_current_relative_url()
{
    // گرفتن مسیر فعلی بدون دامنه
    $path = esc_url_raw(wp_unslash($_SERVER[ 'REQUEST_URI' ]));

                                                // حذف دامنه و فقط نگه داشتن مسیر نسبی + پارامترها
    $relative_url = strtok($path, '?');         // مسیر قبل از پارامترها
    $query_string = $_SERVER[ 'QUERY_STRING' ]; // پارامترهای GET

    // اگر پارامتر وجود داره، به مسیر اضافه کن
    if ($query_string) {
        $relative_url .= '?' . $query_string;
    }

    return $relative_url;
}

function get_name_by_id($data, $id)
{
    $filtered = array_filter($data, function ($item) use ($id) {
        return $item->id == $id;
    });

    // برگرداندن اولین مقدار پیدا شده
    if (! empty($filtered)) {
        return array_values($filtered)[ 0 ]->name;
    }
    return null;
}

function Change_the_heade($results)
{

    $data = [  ];

    foreach ($results as $result) {

        switch ($result[ 'type' ]) {
            case 'successful':
                $type = 'موفق';
                break;
            case 'progress':
                $type = 'درحال انجام';
                break;
            case 'failed':
                $type = 'ناموفق';
                break;
            default:
                $type = '-';
                break;
        }

        $city      = mph_remote('https://api.mrrashidpour.com/iran/cities.json');
        $provinces = mph_remote('https://api.mrrashidpour.com/iran/provinces.json');

        $row[ 'وضعیت' ]                        = $type;
        $row[ 'تاریخ و ساعت' ]            = tarikh($result[ 'created_at' ]);
        $row[ 'مبلغ' ]                          = (absint($result[ 'amount' ])) ? number_format(absint($result[ 'amount' ])) : 0;
        $row[ 'شهر' ]                            = ($city[ 'code' ] == 0 && absint($result[ 'city' ])) ? get_name_by_id($city[ 'result' ], absint($result[ 'city' ])) : 'نامعلوم';
        $row[ 'استان' ]                        = ($provinces[ 'code' ] == 0 && absint($result[ 'ostan' ])) ? get_name_by_id($provinces[ 'result' ], absint($result[ 'ostan' ])) : 'نامعلوم';
        $row[ 'شماره موبایل' ]           = $result[ 'mobile' ];
        $row[ 'نام و نام خانوادگی' ] = $result[ 'user_name' ];

        $data[  ] = $row;
    }

    return $data;
}

function time_difference($time)
{
    date_default_timezone_set('Asia/Tehran');

    // زمان فعلی
    $current_time = time();

    // تبدیل زمان ورودی به timestamp (در صورت نیاز)
    $input_time = is_numeric($time) ? $time : strtotime($time);

    // محاسبه فاصله به ثانیه
    $difference = abs($current_time - $input_time);

    // دسته‌بندی فاصله
    $seconds = $difference;
    $minutes = floor($difference / 60);
    $hours   = floor($difference / 3600);
    $days    = floor($difference / 86400);
    $weeks   = floor($difference / 604800);
    $months  = floor($difference / 2592000);  // تقریباً 30 روز
    $years   = floor($difference / 31536000); // 365 روز

    $new_time = 'آلان';

    if ($years > 0) {
        $new_time = $years . ' سال';
    } elseif ($months > 0) {
        $new_time = $months . ' ماه';
    } elseif ($weeks > 0) {
        $new_time = $weeks . ' هفته';
    } elseif ($days > 0) {
        $new_time = $days . ' روز';
    } elseif ($hours > 0) {
        $new_time = $hours . ' ساعت';
    } elseif ($minutes > 0) {
        $new_time = $minutes . ' دقیقه';
    } elseif ($seconds > 0) {
        $new_time = $seconds . ' ثانیه';
    } else {
        $new_time = 'آلان';

    }

    // خروجی دسته‌بندی شده
    return $new_time;
}
