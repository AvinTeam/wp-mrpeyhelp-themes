<?php

class Mph_Row
{

    private $wpdb;
    private $tablename;

    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->tablename = $wpdb->prefix . 'mpn_row';

    }

    public function insert($usermane, $mobile, $ostan, $city, $amount, $payid): int | false
    {
        $frm = [
            'user_name' => $usermane,
            'mobile' => $mobile,
            'ostan' => $ostan,
            'city' => $city,
            'amount' => $amount,
            'payid' => $payid,
            'type' => 'progress',
            'created_at' => current_time('mysql'),
         ];

        $inserted = $this->wpdb->insert(
            $this->tablename,
            $frm,
            [
                '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s',
             ]
        );

        return ($inserted) ? $this->wpdb->insert_id : false;

    }

    public function select(int $per_page, int $offset, string $status = ''): array | object | null
    {
        $sqlwhere = '';

        if ($status != '') {
            $sqlwhere = "WHERE type ='$status' ";

        }

        $mpn_row = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM %i $sqlwhere ORDER BY `created_at` DESC LIMIT %d OFFSET %d",
                [ $this->tablename, $per_page, $offset ]
            ), ARRAY_A
        );

        return $mpn_row;

    }

    public function selecttype(string $status = ''): array | object | null
    {
        $sqlwhere = '';

        if ($status != '') {
            $sqlwhere = "WHERE type ='$status' ";

        }

        $mpn_row = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM %i $sqlwhere",
                [ $this->tablename ]
            ), ARRAY_A
        );

        return $mpn_row;

    }

    public function selectall(int $limit = 0, string $order_by = ''): array | object | null
    {
        // ORDER

        $sqlwhere = '';

        if ($order_by != '') {
            $sqlwhere = $order_by;

        }

        if ($limit != 0) {
            $sqlwhere .= " LIMIT $limit ";

        }

        $mpn_row = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM %i $sqlwhere",
                [ $this->tablename ]
            ), ARRAY_A
        );

        return $mpn_row;

    }

    public function num(): int
    {
        global $wpdb;

        $num = $wpdb->get_var("SELECT COUNT(*) FROM $this->tablename");

        return absint($num);

    }

    public function sum(): int
    {
        global $wpdb;

        $amountsum = $wpdb->get_var("SELECT SUM(amount) FROM $this->tablename WHERE type ='successful'");
        return absint($amountsum);

    }

    public function update(array $data, array $where, array $format = null, array $where_format = null): int | false
    {

        $result = false;

        if ($data && $where) {

            $result = $this->wpdb->update(
                $this->tablename,
                $data,
                $where,
                $format,
                $where_format
            );
        }
        return $result;

    }

    public function get($key, $value): object | array | false
    {
        $result = false;
        if ($value) {
            global $wpdb;

            $result = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM %i WHERE %i = %s",
                    [ $this->tablename, $key, $value ]
                )
            );
        }
        return $result;
    }

    public function delete($row_id): int | false
    {
        $result = false;
        if ($row_id) {

            $result = $this->wpdb->delete(
                $this->tablename,
                [ 'ID' => $row_id ],
                [ '%d' ]

            );

        }

        return $result;

    }

    public function update_type()
    {
        $mpn_row = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "UPDATE %i SET type = 'failed' WHERE type = 'progress' AND created_at <= NOW() - INTERVAL 30 MINUTE",
                [ $this->tablename ]
            )
        );

        return $mpn_row;

    }



}
