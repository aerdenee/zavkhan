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

                        <div class="row">
                            <div class="col-md-6 text-left">
                                <select name="catid" id="catid" class="form-control" name="organizationid" id="organizationid" data-placeholder=" - Бүгд - " required="required">
                                    <option value=""></option>
                                    <?php echo $categoryList; ?>
                                </select>
                            </div>

                            <div class="clearfix"></div>

                        </div>
                        <hr>
                        <table class="table table-striped table-bordered table-hover" id="loadData">
                            <thead>
                                <tr>
                                    <th class="table-checkbox">#</th>
                                    <th>Овог</th>
                                    <th>Нэр</th>
                                    <th>Утас</th>
                                    <th>Мэйл</th>
                                    <th>Огноо</th>
                                    <th>Мөнгөн дүн</th>
                                    <th>Тайлбар</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

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
    var modId = '<?php echo $modId; ?>',
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
                url: '/scontent/setSessionCatId',
                data: {catId: catId},
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
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
            url: '/sdonate/contentList',
            type: 'POST',
            data: {modId: modId, catId: $("#catid").select2("val")},
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                oTable.fnClearTable();
                var i = 1;
                $.each(data, function () {
                    elem = this;
                    oTable.fnAddData([
                        i,
                        elem['fname'],
                        elem['lname'],
                        elem['phone'],
                        elem['email'],
                        elem['createdate'],
                        elem['amount'] + ' ₮',
                        elem['description'] + ' '
                    ]);
                    i++;
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
                            url: '/scontent/deleteContent',
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
                            url: '/scontent/deleteContent',
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