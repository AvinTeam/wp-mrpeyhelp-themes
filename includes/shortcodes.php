<?php
(defined('ABSPATH')) || exit;


/***** START ALL CART *****/
function mph_all_cart()
{



    $mph_option = get_option('mph_option');



    ob_start();
    
    include_once MPH_VIEWS . 'allcart.php';

    return ob_get_clean();



}
add_shortcode('mph_carts', 'mph_all_cart');
/***** END ALL CART *****/



/***** START FORM PAY *****/
function mph_form_pay()
{

    $mph_option = get_option('mph_option');



    $ostan_row = '<option value="0">انتخاب استان</option>';

    $ostan = mph_remote('https://api.mrrashidpour.com/iran/provinces.json');


    if($ostan[ 'code' ] == 0){

        foreach ($ostan[ 'result' ] as $row) {
            $ostan_row .= '<option value="'.$row->id.'">'.$row->name.'</option>'.PHP_EOL;
        }

    }else{

        $ostan_row .= '<option>مشکلی در بارگزاری پیش آمده</option>';
    }


    ob_start();
    
    include_once MPH_VIEWS . 'form.php';

    return ob_get_clean();

}
add_shortcode('mph_form_pay', 'mph_form_pay');

/***** END FORM PAY *****/


