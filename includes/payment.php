<?php
(defined('ABSPATH')) || exit;

$mph_db = new Mph_Row;
$mph_option = get_option('mph_option');

$error = 0;

$mobile = sanitize_text_field($_POST[ 'user_mobile' ]);

if ($_POST[ 'numberInput' ] == '') {

    $amount = $_POST[ 'rangeValue' ] * $mph_option[ 'pay' ];

} else {
    $amount = absint(str_replace(',', '', $_POST[ 'numberInput' ]));

}

$user_name = sanitize_text_field($_POST[ 'user_name' ]);

if ($amount < 10000) {$location = mph_add_param($_POST[ 'mph_my_page' ], [ 'Authority' => 0, 'Status' => 'small-amount-Error' ]);}
if ($amount > 1000000000) {$location = mph_add_param($_POST[ 'mph_my_page' ], [ 'Authority' => 0, 'Status' => 'big-amount-Error' ]);}

if ($error == 0) {
    $data = array(
        'merchant_id' => $mph_option[ 'merchant_id' ],
        'amount' => $amount,
        'callback_url' => $_POST[ 'mph_my_page' ],
        'description' => sprintf('%s کمک %s ریال', $user_name, $amount),
        'metadata' => array(
            'mobile' => $mobile,
            'email' => 'info.test@example.com',
        ),
    );

    $headers = array(
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    );

    $response = wp_remote_post(
        "https://payment.zarinpal.com/pg/v4/payment/request.json",
        array(
            'method' => 'POST',
            'body' => wp_json_encode($data), // تبدیل آرایه به JSON
            'headers' => $headers,
            'timeout' => 1500,
        )
    );

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        $location = mph_add_param($_POST[ 'mph_my_page' ], [ 'Authority' => 0, 'Status' => 'WP-Error' ]);

    } else {

        // پاسخ موفقیت‌آمیز
        $body = wp_remote_retrieve_body($response); // محتوای پاسخ

        $body = json_decode($body);
        if (!isset($body->message)) {
            $payid = $body->data->authority;

            $mph_db->insert($user_name, $mobile, absint($_POST[ 'user_ostan' ]), absint($_POST[ 'user_shahr' ]), $amount, $payid);

            $location = "https://payment.zarinpal.com/pg/StartPay/" . $payid;

        } else {

            $location = mph_add_param($_POST[ 'mph_my_page' ], [ 'Authority' => 0, 'Status' => 'Server-Error' ]);
        }
    }
}
header("Location: $location");
exit;
