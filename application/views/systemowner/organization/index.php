<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
    <div class="container">

        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        Widget settings form goes here
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn blue">Save changes</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

        <!-- BEGIN PAGE CONTENT INNER -->

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light">

                    <div class="portlet-body form">
<div id="window-organization">
    <?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'frmGaz')); ?>
    <div class="row">
        <div class="col-md-6 text-left">
            <?php //echo form_dropdown('organizationid', $categoryList, '1', 'id="organizationid" class="form-control select2" required="required"');?>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo Sorganization::$path; ?>add/<?php echo $modId; ?>" class="btn green btn-xs"><i class="fa fa-plus"></i> Нэмэх</a>
            <a href="javascript:;" class="btn red btn-xs" onclick="_removeMultiItem()"><i class="fa fa-remove"></i> Устгах</a>
        </div>
        <div class="clearfix"></div>

    </div>
    <hr>
    <table class="table table-striped table-bordered table-hover" id="loadData">
        <thead>
            <tr>
                <th class="table-checkbox text-center">#</th>
                <th>Гарчиг</th>
                <th style="width: 70px;">Төлөв</th>
                <th style="width: 15px;"><i class="fa fa-sort"></i></th>
                <th style="width: 15px;"><i class="fa fa-trash"></i></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <?php echo form_close(); ?>
</div>

                    </div>
                    <!-- END VALIDATION STATES-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT -->
<script type="text/javascript">
    var windowId = "#window-organization";
    var oTable = '';
    var tableName = "loadData";
    oTable = $('#' + tableName, windowId).dataTable({
        scrollCollapse: false,
        ordering: false,
        info: false,
        autoWidth: false
    });
    $(function () {
        _initTable();
    });
    function _removeItem(id) {

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
            close: function () {
                $("#" + dialogId).empty().dialog('close');
            },
            buttons: [
                {text: "Тийм", class: 'btn btn-xs green pull-left', click: function () {
                        $.ajax({
                            type: 'post',
                            url: '<?php echo Sorganization::$path; ?>delete',
                            dataType: "json",
                            data: {id: [id]},
                            success: function (data) {
                                if (data.status === 'success') {
                                    $.growl.notice({title: data.title, message: data.message});
                                    _initTable();
                                } else {
                                    $.growl.error({title: data.title, message: data.message});
                                }
                                $.unblockUI();
                            }
                        });
                        $("#" + dialogId).dialog('close');
                    }},
                {text: "Үгүй", class: 'btn blue btn-xs', click: function () {
                        $("#" + dialogId).dialog('close');
                    }}
            ]
        });
        $("#" + dialogId).dialog('open');

    }
    function _removeMultiItem() {
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
            close: function () {
                $("#" + dialogId).empty().dialog('close');
            },
            buttons: [
                {text: "Тийм", class: 'btn btn-xs green pull-left', click: function () {
                        var id = [];
                        var chekbox = $('#loadData', windowId).find('input[name="id[]"]');
                        $.each(chekbox, function () {
                            if ($(this).is(":checked")) {
                                id.push($(this).val());
                            }
                        });
                        $.ajax({
                            type: 'post',
                            url: '<?php echo Sorganization::$path; ?>delete',
                            dataType: "json",
                            data: {id: id},
                            success: function (data) {
                                if (data.status === 'success') {
                                    $.growl.notice({title: data.title, message: data.message});
                                    _initTable();
                                } else {
                                    $.growl.error({title: data.title, message: data.message});
                                }
                                $.unblockUI();
                            }
                        });
                        $("#" + dialogId).dialog('close');
                    }},
                {text: "Үгүй", class: 'btn blue btn-xs', click: function () {
                        $("#" + dialogId).dialog('close');
                    }}
            ]
        });
        $("#" + dialogId).dialog('open');
    }
    function _active(isActive, id) {
        $.ajax({
            type: 'post',
            url: '<?php echo Sorganization::$path; ?>isActive',
            dataType: "json",
            data: {isActive: isActive, id: id},
            beforeSend: function () {
                $.blockUI({message:null});
            },
            success: function (data) {
                if (data.status === 'success') {
                    $.growl.notice({title: data.title, message: data.message});
                    _initTable();
                } else {
                    $.growl.error({title: data.title, message: data.message});
                }
                $.unblockUI();
            }
        });
    }

    function _initTable() {
        $.ajax({
            url: '<?php echo Sorganization::$path; ?>lists',
            type: 'POST',
            dataType: 'json',
            data: {modId: <?php echo $modId;?>},
            beforeSend: function () {
                $.blockUI({message:null});
            },
            success: function (data) {
                oTable.fnClearTable();
                $.each(data, function () {
                    var row = this;
                    oTable.fnAddData([
                        '<input type="checkbox" name="id[]" value="' + row.id + '">',
                        '<a href="<?php echo Sorganization::$path; ?>edit/' + row.id + '">' + row.title_mn + ' (' + row.title_en + ')' + '</a>',
                        (row.is_active === '1' ? '<span class="label label-sm label-success pointer" onclick="_active(0, ' + row.id + ')"><i class="fa fa-check"></i> Нийтэлсэн </span>' : '<span class="label label-sm label-danger pointer" onclick="_active(1, ' + row.id + ')"><i class="fa fa-times"></i> Хүлээлгэнд </span>'),
                        row.order_num,
                        '<div class="text-center"><a href="javascript:;" title="Устгах" class="btn red btn-xs margin-right-0" onclick="_removeItem(' + row.id + ')"><i class="fa fa-trash"></i></a></div>'
                    ]);

                });
                Gazelle.checkControl();
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        });
    }

</script>