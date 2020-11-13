<table cellpadding="0" cellspacing="0" style="border:none; width: 100%; font-family: arial; font-size: 11px;">
    <tr>
        <td colspan="3" style="width: 33%; text-align: center; font-size: 11px; text-transform: uppercase; font-weight: bold; padding-top: 20px; padding-bottom: 2px;">Хяналтын карт</td>
    </tr>
    <tr>
        <td style="width: 33%; text-align: left; font-size: 11px; border-top: 1px solid #000; padding-top: 5px;">Бичгийн дугаар: <?php echo $row->doc_number;?></td>
        <td style="width: 34%; border-top: 1px solid #000; padding-top: 5px; text-align: center;">Бичгийн огноо: <?php echo date('Y оны m сарын d', strtotime($row->doc_date));?></td>
        <td style="width: 33%; text-align: right; border-top: 1px solid #000; padding-top: 5px;"><?php echo ($row->page_number > 0 ? 'Хуудас: ' . $row->page_number : '');?></td>
    </tr>
    
</table>
<table cellpadding="0" cellspacing="0" style="font-family: arial; margin-top: 20px; width: 100%; font-size: 11px;">
    <tbody>
        <tr>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: left;">Төрөл:</td>
            <td style="border-right: 1px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: left;"><?php echo $row->docType->title;?></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: left; width: 200px;">Илгээгч:</td>
            <td style="border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: left;"><?php echo $row->department->title;?></td>
        </tr>
        <tr>
            <td colspan="2" style="border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: left;"><?php echo $row->description;?></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; text-align: left;" colspan="2">
                <div style="font-weight: bold; min-height: 130px;">Удирдлагын заалт:</div>
                <div style="font-weight: bold; min-height: 50px;">Гарын үсэг:</div>
                <div style="font-weight: bold; min-height: 50px;">Огноо:</div>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" style="border:none; width: 100%; font-family: arial;">
    <tr>
        <td style="width: 50%; text-align: left; font-size: 11px; border-top: 1px solid #000; padding-top: 5px;">Хэвлэсэн: <?php echo $row->people->full_name;?></td>
        <td style="width: 50%; text-align: right; font-size: 11px; border-top: 1px solid #000; padding-top: 5px;">Огноо: <?php echo date('Y оны m сарын d');?></td>
    </tr>
</table>