<table cellpadding="0" cellspacing="0" style="border:none; width: 100%; font-family: arial; font-size: 11px;">
    <tr>
        <td style="width: 44%; text-align: left; font-size: 11px; border-bottom: 1px solid #000; padding-bottom: 5px;">УИХ-ын гишүүн Л.Элдэв-Очирын ажлын алба</td>
        <td style="width: 2%; border-bottom: 1px solid #000; padding-bottom: 5px;"></td>
        <td style="width: 44%; text-align: right; border-bottom: 1px solid #000; padding-bottom: 5px;">Хэвлэсэн: <?php echo date('Y-m-d');?></td>
    </tr>
    <tr>
        <td colspan="3" style="width: 33%; text-align: center; font-size: 11px; text-transform: uppercase; font-weight: bold; padding-top: 20px; padding-bottom: 20px;">Бүртгэлийн хуудас</td>
    </tr>
    <tr>
        <td style="width: 33%; text-align: left; font-size: 11px;">Бүртгэл №: <?php echo $row['create_number'];?></td>
        <td style="width: 33%; text-align: center; font-size: 11px;">Бүртгэсэн: <?php echo date('Y оны m сарын d', strtotime($row['generate_date']));?></td>
        <td style="width: 34%; text-align: right; font-size: 11px;"> <?php echo ($row['page'] != 0 ? 'Хуудас: ' . $row['page'] : '');?></td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" style="font-family: arial; margin-top: 20px; width: 100%; font-size: 11px;">
    <tbody>
        <tr>
            <td style="border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: left; width: 200px;">Илгээгч:</td>
            <td style="border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 8px 14px; text-align: left;"><?php echo mb_substr($row['lname'], 0, 1, 'UTF-8') . '.' . $row['fname'];?></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: left;">Төрөл:</td>
            <td style="border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: left;"><?php echo $category->title;?></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: left;">Холбоо барих:</td>
            <td style="border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: left;"><?php echo ($city != false ? $city->title : ''); echo ($soum != false ? ', ' . $soum->title : ''); echo ($street != false ? ', ' . $street->title : '');?>, <?php echo $row['address'];?></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: left;">Агуулга:</td>
            <td style="border-right: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 14px; text-align: left;"><?php echo $row['description'];?></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; text-align: left;" colspan="2">
                <div style="font-weight: bold; min-height: 100px;">Удирдлагын заалт:</div>
                <div style="font-weight: bold; min-height: 50px;">Гарын үсэг:</div>
                <div style="font-weight: bold; min-height: 50px;">Огноо:</div>
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" style="border:none; width: 100%; font-family: arial;">
    <tr>
        <td style="width: 50%; text-align: left; font-size: 11px; border-top: 1px solid #000; padding-top: 5px;">Бүртгэсэн:.................................../А.Эрдэнэбаатар/</td>
        <td style="width: 50%; text-align: right; font-size: 11px; border-top: 1px solid #000; padding-top: 5px;">&nbsp;</td>
    </tr>
</table>