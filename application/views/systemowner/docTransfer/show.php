<div class="table-responsive">
    <table class="table _doc-transfer-information">
        <tbody>
            <tr>
                <td rowspan="4" style="width: 100px;"><img src="<?php echo UPLOADS_USER_PATH . $row->pic; ?>"</td>
                <td>Нэр: <?php echo $row->full_name; ?></td>
            </tr>
            <tr>
                <td>Албан тушаал: <?php echo $row->position_title; ?></td>
            </tr>
            <tr>
                <td>Байгууллага: <?php echo $row->department_title; ?></td>
            </tr>
            <tr>
                <td>Шилжүүлсэн огноо: <?php echo date('Y оны m сарын d', strtotime($row->created_date)); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="line-height: 24px;">
                    <?php 
                    if ($row->description != '') {
                        echo $row->description;
                    } else {
                        echo 'Мэдээлэл байхгүй';
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
