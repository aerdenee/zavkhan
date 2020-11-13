
<table style="margin: 0; padding: 0;"  cellpadding="0" cellspacing="0">
    <tbody>
        <tr style="">
            <td colspan="4" style="width: 100%; padding: 10px; text-align: center; margin-bottom: 20px; font-weight: bold; text-transform: uppercase;">Хариу албан бичгийн төсөл</td>
        </tr>
        <tr style="">
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; width: 20%; padding: 10px; text-align: right;">Албан бичгийн дугаар</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; width: 20%; padding: 10px;"><?php echo $row->doc_number; ?></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; width: 20%; padding: 10px; text-align: right;">Огноо</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; width: 40%; padding: 10px;"><?php echo date('Y оны m сарын d', strtotime($row->doc_date)); ?></td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; width: 20%; padding: 10px; text-align: right;">Төрөл</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; width: 20%; padding: 10px;"><?php echo $row->doc_type_title; ?></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; width: 20%; padding: 10px; text-align: right;">Хаанаас ирсэн</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; width: 40%; padding: 10px;"><?php echo $row->from_department . ' ' . $row->from_full_name; ?></td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 10px;" colspan="4">
                Агуулга:<br>
                <?php echo $row->description; ?>
            </td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 10px;" colspan="4">
                Хариу албанн бичгийн төсөл:<br>
                <?php
                echo $row->transfer_description;
                ?>
                <br>

            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0;" colspan="4">
                Хэвлэсэн огноо: <?php echo date('Y оны m сарын d өдөр H цаг i');?>
            </td>
        </tr>
    </tbody>
</table>
<br><br>
<table style="margin: 0; padding: 0;"  cellpadding="0" cellspacing="0" border="0">
    <tbody>
        <tr>
            <td style="width: 40%; padding: 10px; text-align: right;">ХЯНАСАН:</td>
            <td style="padding: 10px;">...........................</td>
        </tr>
        <tr>
            <td style="width: 40%; padding: 10px; text-align: right;">БОЛОВСРУУЛСАН:</td>
            <td style="padding: 10px;">.........................../<?php echo $this->session->userdata['adminFullName'];?>/</td>
        </tr>
    </tbody>
</table>