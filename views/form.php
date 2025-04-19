<?php (defined('ABSPATH')) || exit;

$mph_db = new Mph_Row;

?>

<style>
img {
    border: 0;
    max-width: 100%;
    height: auto;
    display: inline-block;
    vertical-align: middle;
}



body {
    background-color: <?php echo $mph_option[ 'view'][ 'body_color'];
    ?>;
}

#mph_Form_payment {
    font-weight: bold;
}


.mph_all_body .mph_FFF {
    color: #FFF;
}


#mph_Form_payment button {
    background-color: <?php echo $mph_option[ 'view'][ 'button_color'];
    ?>;
    border-color: <?php echo $mph_option[ 'view'][ 'button_color'];
    ?>;
}

#mph_Form_payment .form-label {
    color: <?php echo $mph_option[ 'view'][ 'input_label_color'];
    ?>;
}

#mph_Form_payment input,
#mph_Form_payment select {
    border-color: <?php echo $mph_option[ 'view'][ 'input_border_color'];
    ?>;
    background-color: <?php echo $mph_option[ 'view'][ 'body_color'];
    ?>;
    color: #FFF !important;
}

#mph_Form_payment input[type="range"]::-webkit-slider-thumb {
    background: <?php echo $mph_option[ 'view'][ 'input_border_color'];
    ?>;
}

#mph_Form_payment input[type="range"]::-moz-range-thumb {
    background: <?php echo $mph_option[ 'view'][ 'input_border_color'];
    ?>;
}


#mph_Form_payment #amount {
    color: <?php echo $mph_option[ 'view'][ 'input_border_color'];
    ?>;
}
</style>

<div class="mph_all_body">



    <header id="crt-header">
        <center>
            <img src="<?php echo $mph_option[ 'logo' ][ 'header_image' ]; ?>">
        </center>
    </header>

    <div class="container" style="max-width: 1100px; width: 90%">

        <div class="mb-3 mt-3 col-12 mph_FFF">
            <?php echo $mph_option[ 'text' ][ 'header_text' ]; ?>
        </div>

        <?php

if (isset($_GET[ 'Authority' ]) && isset($_GET[ 'Status' ])) {
    $payid = sanitize_text_field($_GET[ 'Authority' ]);
    $get_amount = $mph_db->get('payid', $payid);
    if ($_GET[ 'Status' ] == 'OK') {

        $data = array(
            'merchant_id' => $mph_option[ 'merchant_id' ],
            'authority' => $payid,
            'amount' => $get_amount->amount,
        );

        $headers = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        );

        $response = wp_remote_post(
            'https://payment.zarinpal.com/pg/v4/payment/verify.json',
            array(
                'method' => 'POST',
                'body' => wp_json_encode($data), // تبدیل آرایه به JSON
                'headers' => $headers,
                'timeout' => 1500,
            )
        );

        if (is_wp_error($response)) {
            // اگر خطایی رخ داده باشد
            $error_message = $response->get_error_message();
            echo "خطا: $error_message";
        } else {

            // پاسخ موفقیت‌آمیز
            $body = wp_remote_retrieve_body($response); // محتوای پاسخ

            $body = json_decode($body);

            if ($body->data->code == 100) {

                $mph_db->update(
                    [ 'type' => 'successful' ],
                    [ 'payid' => $payid ],
                    [ '%s' ],
                    [ '%s' ]
                );
                ?>
        <div class="mb-3 col-12">
            <div id="mph_form_danger" class="alert alert-success" role="alert">
                <h5 class="alert-heading">تراکنش موفق</h5>
                <p><strong>شماره تراكنش: </strong><?=$body->data->ref_id?></p>
                <p><strong>شماره کارت: </strong><?=$body->data->card_pan?></p>
            </div>
        </div>
        <?php
} elseif ($body->data->code == 101) {?>

        <div class="mb-3 col-12">
            <div id="mph_form_danger" class="alert alert-danger" role="alert">
                <p><strong>خطا تراکنش: </strong>این تراکنش قبلا انجام شده</p>
            </div>
        </div>
        <?php
}
        }
    } elseif ($_GET[ 'Status' ] == 'NOK') {
        $mph_db->update(
            [ 'type' => 'failed' ],
            [ 'payid' => $payid ],
            [ '%s' ],
            [ '%s' ]
        );?>
        <div class="mb-3 col-12">
            <div id="mph_form_danger" class="alert alert-danger" role="alert">
                <p><strong>خطا تراکنش: </strong>به نظر میرسد مشکلی پیش آمده شما میتوانید از راه های دیگه اقدام کنید</p>
            </div>
        </div>
        <?php
} elseif ($_GET[ 'Status' ] == 'Server-Error') {?>

        <div class="mb-3 col-12">
            <div id="mph_form_danger" class="alert alert-danger" role="alert">
                <p><strong>خطا دریافت اطلاعات: </strong>خطایی از سمت سرور درگاه پرداخت رخ داده است</p>
            </div>
        </div>
        <?php } elseif ($_GET[ 'Status' ] == 'WP-Error') {?>
        <div class="mb-3 col-12">
            <div id="mph_form_danger" class="alert alert-danger" role="alert">
                <p><strong>خطا ارسال اطلاعات: </strong>خطایی در ازسال اطلاعات پیش آمده لطفا دوباره تللاش کنید</p>
            </div>
        </div>
        <?php } elseif ($_GET[ 'Status' ] == 'small-amount-Error') {?>
        <div class="mb-3 col-12">
            <div id="mph_form_danger" class="alert alert-danger" role="alert">
                <p><strong>خطا مبلغ: </strong>مبلغ نمیتواند کمتر از 10,000 ریال باشد</p>
            </div>
        </div>
        <?php } elseif ($_GET[ 'Status' ] == 'big-amount-Error') {?>
        <div class="mb-3 col-12">
            <div id="mph_form_danger" class="alert alert-danger" role="alert">
                <p><strong>خطا مبلغ: </strong>مبلغ نمیتواند بیشتر از 1,000,000,000 ریال باشد</p>
            </div>
        </div>
        <?php }
}?>




        <form id="mph_Form_payment" class="row" method="post" autocomplete="off">



            <div class="mb-3 col-lg-6 col-md-6 col-sm-12  <?php if (!$mph_option[ 'user_name' ]) {echo 'mph_none';}?> ">
                <label for="user_name" class="form-label">لطفا نام و نام خانوادگی خود را وارد نمائید: </label>
                <input type="text" dir="rtl" class="form-control" name="user_name" id="user_name"
                    placeholder="نام و نام خانوادگی">
            </div>

            <div
                class="mb-3  <?php if ($mph_option[ 'user_name' ]) {echo 'col-lg-6 col-md-6 col-sm-12 ';}else{echo 'col-12'; }?>   ">
                <label for="user_mobile" class="form-label">لطفا شماره همراه خود را وارد نمائید: </label>
                <input type="text" dir="rtl" class="form-control" name="user_mobile" id="user_mobile"
                    placeholder="شماره همراه">
            </div>


            <div class="mb-3 col-lg-6 col-md-6 col-sm-12  <?php if (!$mph_option[ 'ostan' ]) {echo 'mph_none';}?>  ">
                <label for="user_ostan" class="form-label">استان:</label>
                <select name="user_ostan" id="user_ostan" class="form-select" required>
                    <?php echo $ostan_row; ?>
                </select>
            </div>
            <div class="mb-3 col-lg-6 col-md-6 col-sm-12 <?php if (!$mph_option[ 'ostan' ]) {echo 'mph_none';}?>">
                <label for="user_shahr" class="form-label">از کدام شهرستان:</label>
                <select name="user_shahr" id="user_shahr" class="form-select" required>
                    <option value="0">انتخاب شهرستان</option>
                </select>
            </div>
            <div class="mb-3 col-12  <?php if (!$mph_option[ 'users_can_unit' ]) {echo 'mph_none';}?>  ">
                <label for="customRange1" class="form-label">تعداد <?php echo $mph_option[ 'name_unit' ]; ?>:</label>
                <div class="position-relative">
                    <span id="rangeValue" class="position-absolute translate-middle">1</span>
                    <input name="rangeValue" type="range" class="" id="customRange1" min="1" value="1"
                        max="<?php echo $mph_option[ 'all_unit' ]; ?>" step="1">
                    <div class="d-flex justify-content-between">
                        <span>1</span>
                        <span><?php echo $mph_option[ 'all_unit' ]; ?></span>
                    </div>
                </div>
            </div>

            <div class="mb-3 col-12">
                <label for="numberInput" class="form-label">مبلغ دلخواه (ریال): </label>
                <input name="numberInput" type="text" id="numberInput" class="form-control"
                    placeholder="مبلغ دلخواه (ریال)" dir="rtl" min="<?php echo $mph_option[ 'pay' ]; ?>"
                    max="<?php echo $mph_option[ 'pay' ] * $mph_option[ 'all_unit' ]; ?>">
            </div>

            <div class="mb-3 col-12 ">
                <label for="amount" class="form-label">مبلغ قابل پرداخت:</label>
                <p id="amount" class="text-center col-12 "></p>
            </div>

            <input name="mph_my_page" value="<?php echo get_permalink() ?>" type="hidden">
            <input name="mph_act" value="peyment" type="hidden">

            <div class="mb-3 col-12">
                <div id="mph_form_danger" class="alert alert-danger mph_none" role="alert">
                </div>
            </div>

            <div class="mb-3 col-12">
                <button type="submit" class="btn btn-primary btn-lg form-control">پرداخت</button>
            </div>
        </form>

        <div class="mb-3 mt-3 col-12 mph_FFF">
            <?php echo do_shortcode($mph_option[ 'text' ][ 'footer_text' ]); ?>
        </div>
    </div>

    <footer>

        <center>
            <img src="<?php echo $mph_option[ 'logo' ][ 'footer_image' ]; ?>">
        </center>
    </footer>

</div>