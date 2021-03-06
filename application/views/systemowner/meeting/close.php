<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-main', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row['id']);
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

    <div class="panel-body">

    </div>

    <table class="table table-bordered table-lg" style="border-left: none; border-right: none; border-bottom: none;">
        <tbody>
            <tr>
                <td style="width:200px;">Илгээгч</td>
                <td><?php echo $row['lname'] . ', ' . $row['fname']; ?></td>
            </tr>
            <tr>
                <td>Төрөл</td>
                <td><?php echo $category['title']; ?></td>
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
                    <?php echo $row['description'];?>
                </td>
            </tr>
            <tr>
                <td>Шилжүүлсэн байгууллага</td>
                <td>
                    <?php echo $controlUrgudulDirectDropDown;?>
                </td>
            </tr>
            <tr>
                <td>Удирдлагын заалт</td>
                <td style="padding: 0;">
                    <?php
                    echo form_textarea(array(
                        'name' => 'closeDescription',
                        'id' => 'closeDescription',
                        'rows' => 4,
                        'value' => $row['close_description'],
                        'class' => 'form-control',
                        'placeholder' => ($row['close_description'] != '' ? $row['close_description'] : 'Өргөдлийг хэрхэн шийдвэрлэсэн талаар дэлгэрэнгүй мэдээлэл'),
                        'style' => 'height:200px; border:none;'
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td>Шийдвэрлэсэн</td>
                <td style="padding: 0;">
                    <?php
                    echo form_input(array(
                        'name' => 'closeAuthor',
                        'id' => 'closeAuthor',
                        'value' => $row['close_author'],
                        'placeholder' => ($row['close_author'] != '' ? $row['close_author'] : 'Өргөдөл шийдсэн хүний нэр'),
                        'class' => 'form-control',
                        'maxlength' => 255,
                        'style' => ' border:none;'
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td>Огноо</td>
                <td>
                    <span style="width: 150px; display: inline-block;">
                        <?php $closeDate = explode(' ', ($row['close_date'] != '0000-00-00 00:00:00' ? $row['close_date'] : date('Y-m-d H:i:s'))); ?>
                        <div class="input-group date date-time" id="event_start_date">
                            <?php
                            echo form_input(array(
                                'name' => 'closeDate',
                                'id' => 'closeDate',
                                'value' => $closeDate['0'],
                                'maxlength' => '10',
                                'class' => 'form-control init-date',
                                'required' => 'required',
                                'readonly' => true
                            ));
                            ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </span>
                    <span style="width: 100px; display: inline-block;">
                        <div class="input-group date date-time" id="event_start_date">
                            <?php
                            $closeDateTime = explode(':', $closeDate['1']);
                            echo form_input(array(
                                'name' => 'closeDateTime',
                                'id' => 'closeDateTime',
                                'value' => $closeDateTime['0'] . ':' . $closeDateTime['1'],
                                'maxlength' => '8',
                                'class' => 'form-control init-time',
                                'required' => 'required',
                                'readonly' => true
                            ));
                            ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </span>

                </td>
            </tr>
            <tr>
                <td>Өргөдөл хаах эсэх</td>
                <td>
                    <label>
                        <?php echo form_checkbox(array('name'=> 'isClose', 'class' => 'checkbox'), 3, ($row['is_active'] == 3 ? TRUE : '')); ?>
                        Өргөдөл хаах бол сонголтыг сонгоно уу </label>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm();"', 'button');?>
                </td>
            </tr>

        </tbody>
    </table>

</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    var formId = '#form-main';
    $(function () {
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
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
        $('input[name="isClose"]').on('click', function(){
            if ($(this).prop('checked')) {
                $('input[name="isActive"]').val(3);
            } else {
                $('input[name="isActive"]').val(1);
            }
        });
    });
    function _saveForm() {
        $(formId).validate({errorPlacement: function () {}});
        if ($(formId).valid()) {
            $.ajax({
                type: 'post',
                url: '<?php echo Surgudul::$path . $mode; ?>',
                data: $(formId).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            text: data.message,
                            addclass: 'bg-success'
                        });
                        window.location.href = '<?php echo Surgudul::$path . 'index/' . $modId; ?>';
                    } else {
                        new PNotify({
                            text: data.message,
                            addclass: 'bg-danger'
                        });
                    }
                    $.unblockUI();
                }
            });
        }
    }
</script>

