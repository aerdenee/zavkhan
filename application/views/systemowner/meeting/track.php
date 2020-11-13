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
                    <?php echo $row['description']; ?>
                </td>
            </tr>

        </tbody>
    </table>
</div>
<br><br>
<div class="panel panel-flat">
    <div class="panel-heading">
        <h6 class="panel-title">Өргөдөл шийдвэрлэлтийн явц</h6>
    </div>

    <div class="panel-body">
        <ul class="media-list">
            <li class="media">
                <div class="media-left">
                    <img src="<?php echo $this->session->adminPic; ?>" class="img-circle" alt="">
                </div>

                <div class="media-body">
                    <?php
                    echo form_textarea(array(
                        'name' => 'description',
                        'id' => 'description',
                        'rows' => 4,
                        'class' => 'form-control',
                        'placeholder' => 'Өргөдөл шийдвэрлэлтийн явц',
                        'style' => 'height:100px;',
                        'required' => true
                    ));
                    ?>
                </div>
            </li>
            <li class="media">
                <div class="media-left"><div style="width: 40px; display: block;">&nbsp;</div></div>
                <div class="media-body">
                    <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Нэмэх', 'class="btn btn-primary btn-md" onclick="_saveForm();"', 'button'); ?>
                </div>
            </li>
        </ul>
    </div>
</div>
<div id="trackHtml">

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
        $('input[name="isClose"]').on('click', function () {
            if ($(this).prop('checked')) {
                $('input[name="isActive"]').val(3);
            } else {
                $('input[name="isActive"]').val(2);
            }
        });
        _initUrgudulDtl();
    });
    function _saveForm() {
        $(formId).validate({errorPlacement: function () {}});
        if ($(formId).valid()) {
            $.ajax({
                type: 'post',
                url: '<?php echo Surgudul::$path; ?>urgudulDtlInsert',
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
                        $("#description").val('');
                        _initUrgudulDtl();
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
    function _initUrgudulDtl() {
        $.ajax({
            type: 'post',
            url: '<?php echo Surgudul::$path; ?>urgudulDtlList',
            dataType: "json",
            data: {modId: <?php echo $row['mod_id']; ?>, contId: <?php echo $row['id']; ?>, controller: '<?php echo $this->uri->segment(2);?>'},
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                $('#trackHtml').html(data);
                $.unblockUI();
            }
        });
    }
    function _updateUrgudulDtl(id) {
        var dialogId = 'removeDialog';
        if (!$("#" + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: '<?php echo Surgudul::$path; ?>urgudulDtlForm',
            dataType: "json",
            data: {id: id},
            success: function (data) {
                $("#" + dialogId).empty().html(data.html);
                $("#" + dialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    dialogClass: 'dialog-blue',
                    close: function () {
                        $("#" + dialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_ok, class: 'btn btn-xs btn-success', click: function () {
                                var _form = $('#' + dialogId).find('form');
                                _form.validate({errorPlacement: function () {}});
                                if (_form.valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: '<?php echo Surgudul::$path; ?>urgudulDtlUpdate',
                                        dataType: "json",
                                        data: _form.serialize(),
                                        success: function (data) {
                                            if (data.status === 'success') {
                                                new PNotify({
                                                    text: data.message,
                                                    addclass: 'bg-success'
                                                });
                                            } else {
                                                new PNotify({
                                                    text: data.message,
                                                    addclass: 'bg-danger'
                                                });
                                            }
                                            $.unblockUI();
                                            _initUrgudulDtl();
                                        }
                                    });
                                    $("#" + dialogId).dialog('close');
                                }
                            }},
                        {text: data.btn_no, class: 'btn btn-default btn-xs', click: function () {
                                $("#" + dialogId).dialog('close');
                            }}
                    ]
                });
                $("#" + dialogId).dialog('open');
            }
        });
    }
    function _removUrgudulDtl(id) {
        var dialogId = 'removeDialog';
        if (!$("#" + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
        }
        $("#" + dialogId).empty().html("Та энэ мэдээллийг устгахдаа итгэлтэй байна");
        $("#" + dialogId).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Сануулга",
            width: 300,
            height: "auto",
            modal: true,
            dialogClass: 'dialog-blue',
            close: function () {
                $("#" + dialogId).empty().dialog('close');
            },
            buttons: [
                {text: "Тийм", class: 'btn btn-xs btn-success', click: function () {
                        $.ajax({
                            type: 'post',
                            url: '<?php echo Surgudul::$path; ?>urgudulDtlDelete',
                            dataType: "json",
                            data: {id: id},
                            success: function (data) {
                                if (data.status === 'success') {
                                    new PNotify({
                                        text: data.message,
                                        addclass: 'bg-success'
                                    });
                                } else {
                                    new PNotify({
                                        text: data.message,
                                        addclass: 'bg-danger'
                                    });
                                }
                                $.unblockUI();
                                _initUrgudulDtl();
                            }
                        });
                        $("#" + dialogId).dialog('close');
                    }},
                {text: "Үгүй", class: 'btn btn-default btn-xs', click: function () {
                        $("#" + dialogId).dialog('close');
                    }}
            ]
        });
        $("#" + dialogId).dialog('open');
    }
</script>

