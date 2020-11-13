<?php
echo form_open('', array('class' => 'form-horizontal p-0', 'id' => 'form-doc-transfer', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>

<div class="table-responsive">
    <table class="table _doc-transfer-information">
        <tbody>
            <tr>
                <td style="width: 20%;" class="text-right">Албан бичгийн дугаар</td>
                <td style="width: 20%;"><?php echo $row->doc_number; ?></td>
                <td style="width: 20%;" class="text-right">Огноо</td>
                <td style="width: 40%;"><?php echo $row->doc_date; ?></td>
            </tr>
            <tr>
                <td style="width: 20%;" class="text-right">Төрөл</td>
                <td style="width: 20%;"><?php echo $row->doc_type_title; ?></td>
                <td style="width: 20%;" class="text-right">Хаанаас ирсэн</td>
                <td style="width: 40%;"><?php echo $row->from_department . ' ' . $row->from_full_name; ?></td>
            </tr>
            <tr>
                <td colspan="4"><?php echo $row->description; ?></td>
            </tr>
            <tr>
                <td colspan="4">Хариу албанн бичгийн төсөл</td>
            </tr>
            <tr>
                <td colspan="4">
                    <?php
                    echo form_textarea(array(
                        'name' => 'transferDescription',
                        'id' => 'transferDescription',
                        'value' => $row->transfer_description,
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php echo form_close(); ?>