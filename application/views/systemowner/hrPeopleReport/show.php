<div class="table-responsive">
    <table class="table _report">
        <tbody>
            <tr>
                <td style="width: 150px;" class="text-right">Хамрах хугацаа</td>
                <td><?php echo date('Y оны m сарын d', strtotime($row->in_date)); ?> - с <?php echo date('Y оны m сарын d', strtotime($row->out_date)); ?></td>
            </tr>
            <tr>
                <td style="width: 150px;" class="text-right">Тайлангийн нэр</td>
                <td><?php echo $row->title; ?></td>
            </tr>
            <tr>
                <td style="width: 150px;" class="text-right">Хавсралт файл</td>
                <td>
                    <?php 
                    if ($row->attach_file != '') {
                        echo '<a href="' . UPLOADS_HR_PATH . $row->attach_file . '" target="_blank"><i class="fa fa-download"></i> Татаж авах</a>';
                    } else {
                        echo 'Хавсралт файл байхгүй';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;" class="text-right">Тайлангийн нэр</td>
                <td style="line-height: 24px;"><?php echo $row->description; ?></td>
            </tr>
        </tbody>
    </table>
</div>