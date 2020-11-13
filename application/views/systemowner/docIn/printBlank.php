<table cellpadding="0" cellspacing="0" style="border:none; width: 100%; font-family: arial; font-size: 11px;">
    <tr>
        <td colspan="3" style="width: 33%; text-align: center; font-size: 11px; text-transform: uppercase; font-weight: bold; padding-bottom: 2px;">Бүртгэл хяналтын карт</td>
    </tr>
    <tr>
        <td style="text-align: left; font-size: 11px; padding-top: 5px;">Хэвлэсэн: <?php echo date('Y/m/d');?></td>
    </tr>
</table>

<table cellpadding="0" cellspacing="0" style="font-family: arial; margin-top: 5px; width: 100%; font-size: 11px;">
    <tbody>
        <tr>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;" colspan="3">Бүртгэсэн</td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;">Хаанаас, хэнээс ирүүлсэн</td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; padding: 8px 14px; text-align: center;" colspan="4">Ирсэн бичгийн</td>
        </tr>
        
        <tr>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;">Дугаар</td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;">Карт</td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;">Огноо</td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;" rowspan="2"><?php echo $row->department->title;?></td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;">Дугаар</td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;">Огноо</td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;">Бичгийн төрөл</td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; padding: 8px 14px; text-align: center;">Хуудас</td>
        </tr>
        
        <tr>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;"><?php echo $row->id;?></td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;"> </td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;"><?php echo date('Y/m/d');?></td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;"><?php echo $row->doc_number;?></td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;"><?php echo date('Y/m/d', strtotime($row->doc_date));?></td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;"><?php echo $row->docType->title;?></td>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 8px 14px; text-align: center;"><?php echo ($row->page_number > 0 ? $row->page_number : '');?></td>
        </tr>
        
        

    </tbody>
</table>

<table cellpadding="0" cellspacing="0" style="font-family: arial; margin-top: 8px; width: 100%; font-size: 11px;">
    <tbody>
        <tr>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center; width: 60%;">Тэргүү, товч агуулга</td>
            <td style="border-right: 1px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center; width: 20%;">Хэнд</td>
            <td style="border-top: 1px solid #000; border-right: 1px solid #000; padding: 8px 14px; text-align: center; width: 20%;">Шийдвэрлэх хугацаа</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;"><br><br></td>
            <td style="border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: center;"></td>
            <td style="border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: center;"></td>
        </tr>
    </tbody>
</table>

<table cellpadding="0" cellspacing="0" style="font-family: arial; margin-top: 8px; width: 100%; font-size: 11px;">
    <tbody>
        <tr>
            <td style="border: 1px solid #000; padding: 8px 14px; text-align: left; vertical-align: text-top;">
                Баримт бичгийг шийдвэрлэлтийн явц: 
                <br><br><br><br>
            </td>
        </tr>
    </tbody>
</table>

<table cellpadding="0" cellspacing="0" style="font-family: arial; margin-top: 8px; width: 100%; font-size: 11px;">
    <tbody>
        <tr>
            <td style="border: 1px solid #000; padding: 8px 14px; text-align: left; vertical-align: text-top;">
                Баримт бичгийг шийдвэрлэсэн тухай тэмдэглэл: 
                <br><br><br><br>
            </td>
        </tr>
        
        <tr>
            <td style="border: 1px solid #000; border-top: none; padding: 8px 14px; text-align: left; vertical-align: text-top; line-height: 16px;">
                Шийдвэрлэсэн огноо:<br>
                Явуулсан бичгийн дугаар:<br>
                Явуулсан бичгийн огноо:<br>
            </td>
        </tr>
    </tbody>
</table>

<table cellpadding="0" cellspacing="0" style="font-family: arial; margin-top: 8px; width: 100%; font-size: 11px; margin-top: 10px;">
    <tbody>
        <tr>
            <td style="padding: 8px 0; text-align: left; vertical-align: text-top;">
                
                Нэр:.....................................Гарын үсэг:.....................................албан тушаал.....................................
            </td>
            <td style="padding: 8px 0; text-align: right; vertical-align: text-top;">
                
                ......он......сар......өдөр
            </td>
        </tr>
    </tbody>
</table>
