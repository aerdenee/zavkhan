<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-main', 'enctype' => 'multipart/form-data'));
echo form_hidden('contId', $row['id']);
echo form_hidden('modId', $row['mod_id']);
echo form_hidden('isActive', $row['is_active']);
?>
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo form_label('Бүртгэлийн дугаар: ' . $row['create_number'], 'Бүртгэлийн дугаар: ' . $row['create_number'], array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE)); ?> </h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="<?php echo Surgudul::$path . 'index/' . $modId; ?>"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>
            </ul>
        </div>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

    <div class="panel-body" style="padding-bottom:5px;">
        <div class="pull-right">
            <ul class="list-inline heading-text">
                <li style="padding-right:10px;"><?php echo form_button('send', '<i class="fa fa-print"></i> Маягт хэвлэх', 'class="btn btn-info btn-rounded btn-xs" onclick="_printBlank({id:' . $row['id'] . '});"', 'button'); ?></li>
                <li><?php echo form_button('send', '<i class="fa fa-print"></i> Өргөдөл хэвлэх', 'class="btn btn-info btn-rounded btn-xs" onclick="_printPage({id:' . $row['id'] . '});"', 'button'); ?></li>
            </ul>
        </div>
    </div>

    <table class="table table-bordered table-lg" style="border-left: none; border-right: none; border-bottom: none;">
        <tbody>
            <tr>
                <td style="width:200px;">Илгээгч</td>
                <td><?php echo $row['lname'] . ', ' . $row['fname']; ?></td>
            </tr>
            <tr>
                <td>Төрөл</td>
                <td><?php echo $category->title; ?></td>
            </tr>
            <tr>
                <td>Холбоо барих</td>
                <td>
                    <?php
                    if ($city) {
                        echo $city->title . ', ';
                    }
                    if ($soum) {
                        echo $soum->title . ', ';
                    }
                    if ($street) {
                        echo $street->title . ', ';
                    }
                    ?>
                    <?php echo $row['address'] . ', ' . $row['contact']; ?>
                </td>
            </tr>
            <tr>
                <td>Агуулга</td>
                <td>
                    <?php echo $row['description']; ?>
                </td>
            </tr>
            <?php
            if ($row['close_description'] != '' or $row['close_author'] != '' or $row['close_date'] != '')
                
                ?>
            <tr>
                <td>Удирдлагын заалт</td>
                <td>
                    <?php echo $row['close_description']; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div id="mediaFile"></div>
<div id="trackHtml"></div>
<?php echo form_close(); ?>
<script type="text/javascript">
    var formId = '#form-main';
    $(function () {
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.fancybox').fancybox({
            helpers: {
                title: null,
                overlay: {
                    speedOut: 0
                }
            }
        });
        $('.init-date').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd',
            today: '',
            close: '',
            clear: ''
        });
        $('.init-time').pickatime({
            format: 'HH:i',
            formatLabel: 'HH:i',
            interval: 30,
            min: [0, 0],
            max: [23, 59]
        });
        $('input[name="isClose"]').on('click', function () {
            if ($(this).prop('checked')) {
                $('input[name="isActive"]').val(3);
            } else {
                $('input[name="isActive"]').val(2);
            }
        });
        _mediaInit({modId: <?php echo $modId;?>, contId: <?php echo $row['id'];?>, createNumber: '<?php echo $row['create_number'];?>', controller: '<?php echo $this->uri->segment(2);?>'});
        _initUrgudulTrack({modId: <?php echo $row['mod_id'];?>, contId: <?php echo $row['id'];?>, controller: '<?php echo $path;?>'});
        
    });
    
</script>

