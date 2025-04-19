<?php
(defined('ABSPATH')) || exit; ?>

<div class="all_cart">
    <?php foreach ($mph_option[ 'cart' ] as $cart): if ($cart == "") {continue;}?>
    <p><span class="mph_cart"><?=$cart?></span></p>
    <?php endforeach?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.mph_cart');

    cards.forEach(function(card) {
        card.addEventListener('click', function() {
            // متن کارت را کپی کن
            const originalText = card.textContent;
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = originalText;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            // نمایش انیمیشن "کپی شد"
            card.textContent = 'کپی شد!';
            card.classList.add('copied');

            // بازگشت به متن اصلی بعد از 1 ثانیه
            setTimeout(function() {
                card.textContent = originalText;
                card.classList.remove('copied');
            }, 1000);
        });
    });
});
</script>