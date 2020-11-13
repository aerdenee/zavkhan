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
<style>
    a:hover{
        text-decoration: none;
    }
</style>
<?php echo form_open($path . $modId . '/delete', array('class' => 'form-horizontal', 'id' => 'frmGaz')); ?>
<div class="row">
    <div class="col-md-6 text-left">
        <select name="catid" id="catid" class="form-control" name="organizationid" id="organizationid" data-placeholder=" - Бүгд - " required="required">
             
            <option value=""></option>
            <?php echo $categoryList; ?>
        </select>
    </div>
    <div class="col-md-6 text-right">
        <a href="/sproduct/<?php echo $modId; ?>/add" class="btn green btn-xs"><i class="fa fa-plus"></i> Нэмэх</a>
        <a href="javascript:;" class="btn red btn-xs" onclick="_removeMultiItem()"><i class="fa fa-remove"></i> Устгах</a>
    </div>
    <div class="clearfix"></div>

</div>
<hr>
<table class="table table-striped table-bordered table-hover" id="loadData">
    <thead>
        <tr>
            <th class="table-checkbox">#</th>
            <th style="width: 70px;">Зураг</th>
            <th>Гарчиг</th>
            <th style="width: 70px;">Төлөв</th>
            <th style="width: 70px;">Эрэмбэ</th>
            <th style="width: 30px;" class="text-center" id="trash"><i class="fa fa-trash"></i></th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<?php echo form_close(); ?>


                    </div>
                    <!-- END VALIDATION STATES-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT -->
<script>
    var frmName = '#frmGaz',
            modId = '<?php echo $modId; ?>',
            catId = '',
            oTable;
    $(function () {

        $("#catid").select2();
        var table = $('#loadData');

        oTable = table.dataTable({
            "language": {
                "emptyTable": "Бичлэг оруулаагүй байна",
                "info": "_START_ / _END_ нийт _TOTAL_ бичлэг",
                "infoEmpty": "",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_  хуудсанд харуулах",
                "search": "Хайх:",
                "zeroRecords": "No matching records found"
            },
            "lengthMenu": [
                [5, 15, 20, 30, -1],
                [5, 15, 20, 30, "Бүгд"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "aoColumns": [
                {"bSortable": false},
                {"bSortable": false},
                {"bSortable": true},
                {"bSortable": false},
                {"bSortable": true},
                {"bSortable": false}
            ]

        });

        var tableWrapper = $('#loadData_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

        _initTableData();

        $('#catid').on('click', function () {
            var catId = $(this).val();
            $.ajax({
                type: 'post',
                url: '/sproduct/setSessionCatId',
                data: {catId: catId},
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message:null});
                },
                success: function (data) {
                    _initTableData();
                },
                error: function () {
                    $.unblockUI();
                }
            });
        });
    });

    function _initTableData() {
        $.ajax({
            url: '/sproduct/contentList',
            type: 'POST',
            data: {modId: modId, catId: $("#catid").select2("val")},
            dataType: 'json',
            beforeSend: function (data) {
                $.blockUI({message:null});
            },
            success: function (data) {
                oTable.fnClearTable();
                $.each(data, function () {
                    $elem = this;
                    oTable.fnAddData([
                        '<input type="checkbox" name="id[]" value="' + $elem['id'] + '">',
                        '<a href="/sproduct/' + $elem['modid'] + '/edit/' + $elem['id'] + '"><img src="' + ($elem['pic'] != '' ? '/upload/image/s_' + $elem['pic'] : '/assets/img/default.jpg') + '" style="max-width:60px;"></a>',
                        '<a href="/sproduct/' + $elem['modid'] + '/edit/' + $elem['id'] + '">' + $elem['title'] + '</a>',
                        ($elem['publish'] == 1 ? '<a href="/sproduct/' + $elem['modid'] + '/id/' + $elem['id'] + '/publish/0"><span class="label label-sm label-success"><i class="fa fa-check"></i> Нийтэлсэн </span></a>' : '<a href="/sproduct/' + $elem['modid'] + '/id/' + $elem['id'] + '/publish/1"><span class="label label-sm label-danger"><i class="fa fa-times"></i> Хүлээлгэнд </span></a>'),
                        $elem['ordering'],
                        '<div class="text-center"><a href="javascript:;" title="Устгах" class="btn red btn-xs" onclick="_removeItem(' + $elem['id'] + ')"><i class="fa fa-trash"></i></a></div>'
                    ]);
                });
                Metronic.init();
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        });
    }

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
                            url: '/sproduct/deleteContent',
                            dataType: "json",
                            data: {contId: [id]},
                            success: function (data) {
                                noty({
                                    text: data.message,
                                    type: 'success',
                                    dismissQueue: true,
                                    layout: 'topRight',
                                    theme: 'defaultTheme'
                                });
                                setTimeout(function () {
                                    $.noty.closeAll();
                                }, 10000);
                                _initTableData();
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
                        var contId = [];
                        var chekbox = $('#loadData').find('input[name="id[]"]');
                        $.each(chekbox, function () {
                            if ($(this).is(":checked")) {
                                contId.push($(this).val());
                            }
                        });
                        $.ajax({
                            type: 'post',
                            url: '/sproduct/deleteContent',
                            dataType: "json",
                            data: {contId: contId},
                            success: function (data) {
                                noty({
                                    text: data.message,
                                    type: 'success',
                                    dismissQueue: true,
                                    layout: 'topRight',
                                    theme: 'defaultTheme'
                                });
                                setTimeout(function () {
                                    $.noty.closeAll();
                                }, 10000);
                                _initTableData();
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
</script>