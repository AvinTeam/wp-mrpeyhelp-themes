<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_List_Table')) {

    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

}

class Mph_List_Table extends WP_List_Table
{

    private $all_results;
    private $par_page;
    private $numsql;
    private $m;

    public function mph_res($rows)
    {
        $this->all_results = $rows[ 0 ];
        $this->par_page = $rows[ 1 ];
        $this->numsql = $rows[ 2 ];
        $this->m = $rows[ 3 ];

    }

    public function get_columns()
    {
        return [
            'row' => '#',
            'user_name' => 'نام و نام خانوادگی',
            'mobile' => 'شماره موبایل',
            'ostan' => 'استان',
            'city' => 'شهر',
            'amount' => 'مبلغ (ریال)',
            'created_at' => 'تاریخ پرداخت',
            'type' => 'وضعیت',

         ];
    }

    public function column_default($item, $column_name)
    {

        if (isset($item[ $column_name ])) {
            return wp_kses($item[ $column_name ], [
                'span' => [  ],
             ]);
        }
        return '-';
    }

    public function column_ostan($item)
    {

        $provinces = mph_remote('https://api.mrrashidpour.com/iran/provinces.json');

        return ($provinces[ 'code' ] == 0 && absint($item[ 'ostan' ])) ? get_name_by_id($provinces[ 'result' ], absint($item[ 'ostan' ])) : 'نامعلوم';
    }

    public function column_city($item)
    {
        $city = mph_remote('https://api.mrrashidpour.com/iran/cities.json');
        return ($city[ 'code' ] == 0 && absint($item[ 'city' ])) ? get_name_by_id($city[ 'result' ], absint($item[ 'city' ])) : 'نامعلوم';
    }

    public function column_amount($item)
    {
        return (absint($item[ 'amount' ])) ? number_format(absint($item[ 'amount' ])) : 0 ;
    }

    public function column_type($item)
    {

        switch ($item[ 'type' ]) {
            case 'successful':
                $type = '<span class = "successful dashicons-before dashicons-yes-alt">موفق</span>';
                break;
            case 'progress':
                $type = '<span class = "progress dashicons-before dashicons-warning">درحال انجام</span>';
                break;
            case 'failed':
                $type = '<span class="failed dashicons-before dashicons-dismiss">ناموفق</span>';

                break;
            default:
                $type = '-';
                break;
        }

        return $type;
    }

    public function column_created_at($item)
    {
        return tarikh($item[ 'created_at' ]);
    }

    public function get_bulk_actions()
    {

        if (current_user_can('manage_options')) {
            $action[ 'delete' ] = esc_html__('delete', 'mraparat');
        }
        //return $action;
    }

    public function column_row($item)
    {
        $this->m++;
        return $this->m;
    }

    public function no_items()
    {

        echo 'چیزی یافت نشد';

    }

    public function get_sortabele_colums()
    {

        // return [
        //     'amount' => [ 'amount', true ],
        //     'created_at' => [ 'created', true ],
        //  ];

    }

    public function prepare_items()
    {

        $this->process_bulk_action();

        $this->set_pagination_args([
            'total_items' => $this->numsql,
            'per_page' => $this->par_page,
         ]);

        $this->_column_headers = [
            $this->get_columns(),
            [  ],
            $this->get_sortabele_colums(),
            'mobile',
         ];
        $this->items = $this->all_results;

    }

    private function create_view($key, $label, $url, $count = 0)
    {
        $current_status = isset($_GET[ 'status' ]) ? $_GET[ 'status' ] : 'all';

        $view_tag = sprintf('<a href="%s" %s>%s</a>', $url, $current_status == $key ? 'class="current"' : '', $label);

        $view_tag .= sprintf('<span class="count">(%d)</span>', $count);

        return $view_tag;
    }

    protected function get_views()
    {
        global $wpdb;

        $tablename = $wpdb->prefix . 'mpn_row';

        $all = $wpdb->get_var("SELECT COUNT(*) FROM {$tablename}");
        $successful = $wpdb->get_var("SELECT COUNT(*) FROM {$tablename} WHERE type = 'successful'");
        $progress = $wpdb->get_var("SELECT COUNT(*) FROM {$tablename} WHERE type = 'progress' ");
        $failed = $wpdb->get_var("SELECT COUNT(*) FROM {$tablename} WHERE type = 'failed' ");

        return [
            'all' => $this->create_view('all', 'همه', admin_url('admin.php?page=mrpayhelp&status=all'), $all),
            'successful' => $this->create_view('successful', 'موفق', admin_url('admin.php?page=mrpayhelp&status=successful'), $successful),
            'progress' => $this->create_view('progress', 'درحال انجام', admin_url('admin.php?page=mrpayhelp&status=progress'), $progress),
            'failed' => $this->create_view('failed', 'نا موفق', admin_url('admin.php?page=mrpayhelp&status=failed'), $failed),
         ];
    }

    protected function extra_tablenav($which)
    {
        if ('top' === $which) {
            ?>
            <div class="alignleft actions">
                <a href="<?php echo esc_url(add_query_arg('action', 'download_csv', get_current_relative_url())); ?>" class="button button-primary">دانلود CSV</a>
                <a href="<?php echo esc_url(add_query_arg('action', 'download_exel', get_current_relative_url())); ?>" class="button button-primary">دانلود exel</a>
            </div>
            <?php
}
    }

}
