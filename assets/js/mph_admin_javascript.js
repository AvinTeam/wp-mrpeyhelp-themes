let idealCount = 0

if (document.getElementById('mph_ideal')) {
    let mph_ideal_ch = document.getElementById('mph_ideal_ch');
    idealCount = mph_ideal_ch.childElementCount;
}

jQuery(document).ready(function ($) {
    $('.mph_form .input_color').wpColorPicker();




    var background_uploader;
    var mph_id;
    $('.mph_select_img').click(function (e) {

        mph_id = $(this).attr('id');

        if (background_uploader !== undefined) {
            background_uploader.open();
            return;
        }

        background_uploader = wp.media({
            title: 'انتخاب تصویر',
            button: {
                text: 'انتخاب',
            },
            library: {
                type: ['image']
            }

        })


        background_uploader.on('select', function () {
            let selected = background_uploader.state().get('selection');

            let mph_utl = selected.first().toJSON().url;

            $('.' + mph_id + ' input').val(mph_utl);

            $('.' + mph_id + ' img').attr('src', mph_utl);

        });


        background_uploader.open();

        e.preventDefault();

    });






    $('.mphAdd').click(function (e) {
        e.preventDefault();

        const newRow = `
            <div class="card-row">
                <input type="text" name="cart[]" placeholder="شماره کارت" maxlength="16">
                <button type="button" onclick="mphremove(this)" class="mphremove">-</button>
            </div>
        `;
        $('#mphcardForm').append(newRow);

    });









    $('#mph_btn_add').click(function (e) {
        idealCount++;

        if (mph_ideal_ch.childElementCount == 0) {
            idealCount = 1;

        }

        $('#mph_ideal_ch').append('<div class="ma_row" id="ma_item_' + idealCount + '">' +
            '<div class="ma_col_5">' +
            '<input name="cart[]" onchange="ma_aparat_link(' + idealCount + ')" type="text" id="mph_item' + idealCount + '" class="regular-text" value="">' +
            '<button type="button" class="button  mph_btn_remove" onclick="mphremove(' + idealCount + ')">حذف</button>' +
            '</div>' +
            '</div>');


    });




});

function mphremove(item) {
    document.getElementById('ma_item_' + item).remove();
}

const payInput = document.getElementById('payInput');
if (payInput) {

    payInput.addEventListener('input', () => {
        // حذف کاراکترهای غیرعددی
        const rawValue = payInput.value.replace(/[^0-9]/g, '');

        // اضافه کردن جداکننده سه‌رقمی
        const formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // نمایش مقدار فرمت‌شده
        payInput.value = formattedValue;
    });
}
const allUnit = document.getElementById('all_unit');
if (allUnit) {
    allUnit.addEventListener('input', () => {
        // حذف کاراکترهای غیرعددی
        const rawValue = allUnit.value.replace(/[^0-9]/g, '');

        // اضافه کردن جداکننده سه‌رقمی
        const formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // نمایش مقدار فرمت‌شده
        allUnit.value = formattedValue;
    });

}