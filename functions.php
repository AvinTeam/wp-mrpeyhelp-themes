<?php

define('MPH_VERSION', '1.3.4');

define('MPH_PATH', get_template_directory() . "/");
define('MPH_INCLUDES', MPH_PATH . 'includes/');
define('MPH_CLASS', MPH_PATH . 'classes/');
define('MPH_FUNCTION', MPH_PATH . 'functions/');
define('MPH_VIEWS', MPH_PATH . 'views/');

define('MPH_URL', get_template_directory_uri() . "/");
define('MPH_ASSETS', MPH_URL . 'assets/');
define('MPH_CSS', MPH_ASSETS . 'css/');
define('MPH_JS', MPH_ASSETS . 'js/');
define('MPH_IMAGE', MPH_ASSETS . 'image/');

require_once MPH_INCLUDES . '/styles.php';
require_once MPH_INCLUDES . '/jdf.php';
require_once MPH_INCLUDES . '/fun.php';
require_once MPH_INCLUDES . '/shortcodes.php';
require_once MPH_INCLUDES . '/ajax.php';

require_once MPH_CLASS . '/Mph_Row.php';
require_once MPH_CLASS . '/Mph_List_Table.php';

mph_start_working();
require_once MPH_INCLUDES . '/bar_menu.php';
require_once MPH_INCLUDES . '/jobs.php';

if (is_admin()) {
    require_once MPH_INCLUDES . '/menu.php';
    require_once MPH_INCLUDES . '/install.php';
    require_once MPH_INCLUDES . '/handle_download.php';
    require_once MPH_INCLUDES . '/dashboard_widget.php';

}

if (isset($_POST[ 'mph_act' ]) && $_POST[ 'mph_act' ] == 'peyment') {

    require_once MPH_INCLUDES . '/payment.php';

}

