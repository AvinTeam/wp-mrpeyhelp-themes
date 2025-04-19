<?php

add_action('wp_ajax_mph_get_city', 'mph_get_city');
add_action('wp_ajax_nopriv_mph_get_city', 'mph_get_city');

function mph_get_city()
{
    $city_row = '<option  value="0" selected>انتخاب شهرستان</option>';

    if ($_POST[ 'type' ] == 'city' && absint($_POST[ 'ostanId' ])) {

        $city = mph_remote('https://api.mrrashidpour.com/iran/cities.json');

        if ($city[ 'code' ] == 0) {

            foreach ($city[ 'result' ] as $value) {
                if ($value->province_id == absint($_POST[ 'ostanId' ])) {

                    $city_row .= '<option value="' . $value->id . '">' . $value->name . '</option>' . PHP_EOL;

                }

            }

            wp_send_json_success($city_row);

        } else {
            $city_row .= '<option>مشکلی در بارگزاری پیش آمده</option>';

            wp_send_json_error($city_row);
        }

    }

}
