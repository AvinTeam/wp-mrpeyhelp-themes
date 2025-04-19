<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="<?php bloginfo('charset');?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=get_bloginfo('name')?></title>
    <?php wp_head();?>
</head>
<body>

<?php echo do_shortcode('[mph_form_pay]'); ?>

<?php wp_footer();?>
</body>
</html>