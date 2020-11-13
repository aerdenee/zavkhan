<?php if ($row) { ?>
<table class="table table-bordered _report">
    <tbody>
        <tr>
            <td class="text-right">Лаборатори №</td>
            <td class=""><?php echo $row->create_number;?></td>
        </tr>
        <tr>
            <td class="text-right">Шинжээч</td>
            <td class=""><?php echo $row->expert;?></td>
        </tr>
        <tr>
            <td class="text-right">Хариу гарсан</td>
            <td class=""><?php echo date('Y оны m сарын d өдөр', strtotime($row->close_date));?></td>
        </tr>
        <tr>
            <td class="text-right">Хариу</td>
            <td class=""><?php echo $row->close_description;?></td>
        </tr>
    </tbody>
</table>
<?php } else { ?>
<div class="_alert"><i class="icon-alert _icon"></i><div class="_text">Хариу гараагүй</div></div>
<?php } ?>