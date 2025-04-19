
jQuery(document).ready(function ($) {



    $('#user_ostan').on('change', function () {
        let formData = {
            action: 'mph_get_city',
            ostanId: $(this).val(),
            type: 'city',
        };
        // ارسال درخواست AJAX
        $.ajax({
            url: mph_script.ajax_url,
            method: 'POST', // نوع درخواست
            data: formData, // داده‌های ارسالی
            dataType: 'json', // نوع داده‌ی دریافتی (json)
            success: function (response) {
                $('#user_shahr').html(response.data);
            }
        });

    });

});


let rangeInput = document.getElementById('customRange1');
let rangeValue = document.getElementById('rangeValue');
let numberInput = document.getElementById('numberInput');
let amount = document.getElementById('amount');

// مقدار اولیه
updateRangeValue(rangeInput.value, rangeInput);

// بروزرسانی موقعیت و مقدار عدد
rangeInput.addEventListener('input', () => {
    updateRangeValue(rangeInput.value, rangeInput);
});

function updateRangeValue(value, input) {
    let rangeWidth = input.offsetWidth;
    let thumbWidth = 0; // عرض تامب
    let max = input.max;
    let min = input.min;

    let percentage = (value - min) / (max - min);

    let offset = ((1 - percentage) * (rangeWidth - thumbWidth) + thumbWidth / 2);

    rangeValue.style.left = `${offset - 10}px`;
    rangeValue.textContent = value;

    console.log(mph_script.mph_option.view.input_label_color);

    let newval = percentage * 100;
    rangeInput.style.background = `linear-gradient(to left, ${mph_script.mph_option.view.input_label_color} ${newval}%, #ddd ${newval}%)`; // تغییر رنگ

    value = value * mph_script.mph_option.pay;

    // اضافه کردن جداکننده سه‌رقمی
    let formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    amount.innerHTML = (mph_script.mph_option.users_can_unit) ? formattedValue.toString() + ' ریال' : '0 ریال'
}

numberInput.addEventListener('input', () => {

    let numberTrim = numberInput.value.replace(/,/g, '');
    numberTrim = Number(numberTrim);

    if (numberTrim > 1000000000) { numberTrim = 1000000000; }

    mphNewCont = Math.round(numberTrim / mph_script.mph_option.pay);

    let endPay = mph_script.mph_option.pay * mph_script.mph_option.all_unit;


    if (numberTrim <= endPay || !mph_script.mph_option.users_can_unit) {

        // حذف کاراکترهای غیرعددی
        let rawValue = numberTrim.toString().replace(/[^0-9]/g, '');

        // اضافه کردن جداکننده سه‌رقمی
        let formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        formattedValue = (formattedValue != '') ? formattedValue : 0;

        // نمایش مقدار فرمت‌شده
        numberInput.value = formattedValue;

        updateRangeValue(mphNewCont, rangeInput);
        rangeInput.value = mphNewCont;

        amount.innerHTML = formattedValue.toString() + ' ریال';
    } else {

        numberInput.value = endPay.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        updateRangeValue(mph_script.mph_option.all_unit, rangeInput);
        rangeInput.value = mph_script.mph_option.all_unit;
    }

});

const form = document.getElementById('mph_Form_payment');
const mphFormDanger = document.getElementById('mph_form_danger');

form.addEventListener('submit', function (event) {

    event.preventDefault();

    const regex_mobile = /^09(1[0-9]|2[0-9]|3[0-9]|9[0-9]|0[1-9])-?[0-9]{7}$/;


    const user_name = document.getElementById('user_name').value.trim();
    const user_mobile = document.getElementById('user_mobile').value.trim();
    const user_ostan = document.getElementById('user_ostan').value.trim();
    const user_shahr = document.getElementById('user_shahr').value.trim();
    const amount = document.getElementById('amount').innerHTML.trim();
    let newAmount = Number(amount.replace(/[,ریال\s]/g, ''));

    let error = 0;
    let error_massege = '';

    // بررسی اعتبار
    // if (user_name === '' && mph_script.mph_option.user_name) {
    //     error_massege += 'لطفاً نام و نام خانوادگی خود را وارد کنید.<br>';
    //     error = 1;
    // } else if (user_name.length > 2 && mph_script.mph_option.user_name) {
    //     error_massege += 'لطفاً نام و نام خانوادگی خود را به درستی وارد کنید.<br>';
    //     error = 1;
    // }
    // بررسی اعتبار
    if (user_mobile === '') {
        error_massege += 'لطفاً شماره موبایل خود را وارد کنید.<br>';
        error = 1;
    } else if (!regex_mobile.test(user_mobile)) {
        error_massege += 'لطفاً شماره موبایل خود را به درستی وارد کنید.<br>';
        error = 1;
    }


    // if (Number(user_ostan) <= 0 && mph_script.mph_option.ostan) {
    //     error_massege += 'لطفاً استان خود را وارد کنید. <br>';
    //     error = 1;
    // }
    // if (Number(user_shahr) <= 0 && mph_script.mph_option.ostan) {
    //     error_massege += 'لطفاً شهرستان خود را وارد کنید.<br>';
    //     error = 1;
    // }

    // if (newAmount < mph_script.mph_option.pay && mph_script.mph_option.users_can_unit) {
    //     error_massege += 'مبلغ نباید از ' + mph_script.mph_option.pay.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' ریال کمتر باشد';
    //     error = 1;
    // }
    if (newAmount < 10000) {
        error_massege += 'مبلغ نمیتواند کمتر از 10,000 ریال باشد';
        error = 1;
    }
    if (newAmount > 1000000000) {
        error_massege += 'مبلغ نمیتواند بیشتر از 1,000,000,000 ریال باشد';
        error = 1;
    }

    if (error == 0) {
        form.submit(); // ارسال فرم
    } else {
        mphFormDanger.innerHTML = error_massege;

        mphFormDanger.classList.remove('mph_none');
    }


});



