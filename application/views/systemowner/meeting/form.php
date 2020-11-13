<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-main', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row['id']);
echo form_hidden('modId', $modId);
?>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo form_label('Бүртгэлийн дугаар', 'Бүртгэлийн дугаар', array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE)); ?> </h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="<?php echo Smeeting::$path . 'index/' . $modId; ?>"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>
            </ul>
        </div>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

    <div class="panel-body">
        <div class="col-md-6">
            <div class="row">
                <div class="form-group">
                    <?php echo form_label('Төрөл', 'Төрөл', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php echo $controlCategoryListDropdown; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Огноо', 'Огноо', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-5 col-md-5">
                                    <?php $meetingDate = explode(' ', $row['meeting_date']); ?>
                                    <div class="input-group date date-time" id="event_start_date">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'meetingDate',
                                            'id' => 'meetingDate',
                                            'value' => $meetingDate['0'],
                                            'maxlength' => '10',
                                            'class' => 'form-control init-date',
                                            'readonly' => true,
                                            'required' => true
                                        ));
                                        ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="input-group date date-time" id="event_start_date">
                                        <?php
                                        $meetingDateTime = explode(':', $meetingDate['1']);
                                        echo form_input(array(
                                            'name' => 'meetingDateTime',
                                            'id' => 'meetingDateTime',
                                            'value' => $meetingDateTime['0'] . ':' . $meetingDateTime['1'],
                                            'maxlength' => '8',
                                            'class' => 'form-control init-time',
                                            'readonly' => true,
                                            'required' => true
                                        ));
                                        ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php
                        echo form_input(array(
                            'name' => 'title',
                            'id' => 'title',
                            'value' => $row['title'],
                            'class' => 'form-control',
                            'required' => true
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="form-group">
                    <?php echo form_label('Нийслэл/аймаг', 'Нийслэл/аймаг', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php echo $controlCityDropdown; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Дүүрэг/сум', 'Дүүрэг/сум', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7" id="address-soum-html">
                        <?php echo $controlSoumDropdown; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Хороо/баг', 'Хороо/баг', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7" id="address-street-html">
                        <?php echo $controlStreetDropdown; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-md-12">
                    <?php echo form_label('Товч агуулга', 'Товч агуулга', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'description',
                        'id' => 'description',
                        'value' => $row['description'],
                        'rows' => 4,
                        'class' => 'form-control',
                        'placeholder' => 'Дэлгэрэнгүй хаяг',
                        'style' => 'height:200px;',
                        'required' => true
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <button type="button" class="btn btn-red btn-xs" onclick="_addRow(this);" style="margin-bottom: 10px;"><i class="fa fa-plus"></i> Нэмэх</button>
            <table class="table table-bordered table-custom" id="peopleList">
                <thead>
                    <tr class="active">
                        <th style="width: 30px;">№</th>
                        <th style="width: 200px;">Овог</th>
                        <th style="width: 200px;">Нэр</th>
                        <th style="width: 200px;">Регистр</th>
                        <th>Утас</th>
                        <th style="width: 50px;" class="text-center"><i class="fa fa-cogs"></i></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="clearfix"></div>
        <hr>
        <div class="row">
            <div class="col-md-6 text-left">
            </div>
            <div class="col-md-6 text-right">
                <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm();"', 'button'); ?>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    var formId = '#form-main';
    var _table = $('#peopleList tbody');
    var mywindow = '';
    $(function () {
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.select2').select2();
        CKEDITOR.replace('description', {height: '200px'});
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
        $('#cityId').on('change', function () {
            _selectSoum(this);
        });
        $('#soumId').on('change', function () {
            _selectStreet(this);
        });
        _meetingDtlInit();

    });
    function _addRow() {
        var _rowNumber = parseInt(_table.find('tr.row-people').length) + 1;
        var _tr = '';
        _tr += '<tr class="row-people">';
        _tr += '<td>' + _rowNumber + '</td>';
        _tr += '<td style="padding: 0;"><input type="text" name="lname[]" class="form-control" style="margin: 0; border: none;" placeholder="Эцгийн нэр"></td>';
        _tr += '<td style="padding: 0;"><input type="text" name="fname[]" class="form-control" style="margin: 0; border: none;" placeholder="Өөрийн нэр"></td>';
        _tr += '<td style="padding: 0;"><input type="text" name="register[]" class="form-control" style="margin: 0; border: none;" placeholder="Регистр"></td>';
        _tr += '<td style="padding: 0;"><input type="text" name="phone[]" class="form-control" style="margin: 0; border: none;" placeholder="Холбоо барих утасны дугаар"></td>';
        _tr += '<td style="padding: 0;" class="text-center"><a href="javascript:;" onclick="_removeItemDtl(0, this);"><i class="icon-trash"></i></a></td>';
        _tr += '</tr>';
        _table.append(_tr);
    }
    function _saveForm() {
        $(formId).validate({errorPlacement: function () {}});
        if ($(formId).valid()) {
            $('textarea[name="description"]').val(CKEDITOR.instances.description.getData());
            $.ajax({
                type: 'post',
                url: '<?php echo Smeeting::$path . $mode; ?>',
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
                        window.location.href = '<?php echo Smeeting::$path . 'index/' . $modId; ?>';
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
    function _printImage(id, elem) {
        var mywindow = '';
        $.ajax({
            type: 'post',
            url: '<?php echo Smeeting::$path; ?>mediaPrint',
            dataType: "json",
            data: {id: id},
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                mywindow = window.open('', 'PRINT', 'height=900,width=1000');
                mywindow.document.write('<html><head><title>' + data.title + '</title>');
                mywindow.document.write('</head><body style="font-family:arial;"></body></html>');
                $.unblockUI();
            }
        }).done(function (data) {
            mywindow.document.write(data.html);
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            mywindow.print();
            mywindow.close();
        });
    }
    function _selectSoum(elem) {
        $.ajax({
            type: 'post',
            url: 'saddress/controlAddressDropdown',
            data: {name: 'soumId', parentId: $(elem).val(), selectedId: 0},
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                $('#address-soum-html').html(data);
                $.unblockUI();
            }
        }).done(function () {
            $('.select2').select2();
            $('#soumId').on('change', function () {
                _selectStreet(this);
            });
            $('#streetId').val(0).trigger('change');
        });
    }
    function _selectStreet(elem) {
        $.ajax({
            type: 'post',
            url: 'saddress/controlAddressDropdown',
            data: {name: 'streetId', parentId: $(elem).val(), selectedId: 0},
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                $('#address-street-html').html(data);
                $.unblockUI();
            }
        }).done(function () {
            $('.select2').select2();
            $('#streetId').on('change', function () {
                if (parseInt($(this).val()) > 0) {
                    $('.address-detial-html').removeClass('hide');
                } else {
                    $('.address-detial-html').addClass('hide');
                    $('.address-detial-html').addClass('hide');
                }
            });
        });
    }
    function _meetingDtlInit() {
        $.ajax({
            type: 'post',
            url: '<?php echo Smeeting::$path; ?>meetingDtlList',
            data: {contId: <?php echo $row['id']; ?>},
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                _table.empty().append(data);
                $.unblockUI();
            }
        });
    }
    function _removeItemDtl(id, elem) {
        var _this = $(elem);
        if (id != '0') {
            $.ajax({
                type: 'post',
                url: '<?php echo Smeeting::$path . 'meetingDtlDelete/'; ?>',
                data: {id: [id]},
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    _meetingDtlInit();
                    $.unblockUI();
                }
            });
        } else {
            _this.parents('tr').remove();
        }

    }
</script>
