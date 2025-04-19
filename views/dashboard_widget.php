<?php
(defined('ABSPATH')) || exit;?>




<table class="wp-list-table widefat striped">
    <thead>
        <th>ردیف</th>
        <th>زمان</th>
        <th>مبلغ</th>
        <th>وضعیت</th>

    </thead>



<?php
$mphm=1;
foreach ($mph_all_row as $row) :




    switch ($row[ 'type' ]) {
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
?>
    <tbody>
        <td><?= $mphm ?></td>
        <td><?= time_difference($row['created_at']) ?></td>
        <td><?=  number_format($row['amount']) ?></td>
        <td class="type column-type"><?= $type ?></td>
    </tbody>
<?php 

$mphm ++;
endforeach; ?>



</table>