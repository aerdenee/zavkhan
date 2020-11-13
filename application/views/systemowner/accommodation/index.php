<div class="panel panel-flat" id="window-content">
    <?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'frmGaz')); ?>
    <div class="panel-body">
        <div class="btn-group">
            <a href="<?php echo Saccommodation::$path; ?>add/<?php echo $modId; ?>" class="btn btn-danger btn-xs"><i class="icon-plus2"></i> Нэмэх</a>
<!--            <button type="button" class="btn btn-danger btn-xs"><i class="icon-database-edit2"></i> Засах</button>
            <button type="button" class="btn btn-primary btn-xs"><i class="icon-trash"></i> Устгах</button>-->
        </div>
        <div class="pull-right">
            <select name="organizationId" id="organizationId" class="form-control select2">
                <?php echo $controlComboCampList; ?>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped" id="loadData">
            <thead>
                <tr>
                    <th class="table-checkbox text-center">#</th>
                    <th style="width: 60px;">Зураг</th>
                    <th>Гарчиг</th>
                    <th>Бааз</th>
                    <th style="width: 70px;">Төлөв</th>
                    <th style="width: 15px;"><i class="icon-menu-open"></i></th>
                    <th style="width: 15px;"><i class="icon-trash"></i></th>
                </tr>
            </thead>
        </table>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    var windowId = "#window-content";
    var tableName = "loadData";
    var oTable = $('#' + tableName, windowId).dataTable({
        scrollCollapse: false,
        ordering: false,
        info: false,
        autoWidth: false,
        dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Хайлт:</span> _INPUT_',
            lengthMenu: '<span>Харуулах:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        },
        select: {
            style: 'multi',
            selector: 'td:first-child'
        }
    });
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Хайлт хийх түлхүүр үг...');
    _initTable();
    $(function(){
        $('#organizationId', windowId).on('change', function(){
            _initTable();
        });
    });
    function _removeItem(id) {
        var dialogId = 'removeDialog';
        if (!$("#" + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
        }
        $("#" + dialogId).empty().html("Та энэ мэдээллийг устгахдаа итгэлтэй байна уу?");
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
                            url: '<?php echo Saccommodation::$path; ?>delete',
                            dataType: "json",
                            data: {id: [id]},
                            success: function (data) {
                                if (data.status === 'success') {
                                    new PNotify({
                                        title: data.title,
                                        text: data.message,
                                        addclass: 'bg-success'
                                    });
                                    _initTable();
                                } else {
                                    new PNotify({
                                        title: data.title,
                                        text: data.message,
                                        addclass: 'bg-danger'
                                    });
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
                            url: '<?php echo Saccommodation::$path; ?>delete',
                            dataType: "json",
                            data: {id: id},
                            success: function (data) {
                                if (data.status === 'success') {
                                    new PNotify({
                                        title: data.title,
                                        text: data.message,
                                        addclass: 'bg-success'
                                    });
                                    _initTable();
                                } else {
                                    new PNotify({
                                        title: data.title,
                                        text: data.message,
                                        addclass: 'bg-danger'
                                    });
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
            url: '<?php echo Saccommodation::$path; ?>isActive',
            dataType: "json",
            data: {isActive: isActive, id: id},
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                if (data.status === 'success') {
                    new PNotify({
                        title: data.title,
                        text: data.message,
                        addclass: 'bg-success'
                    });
                    _initTable();
                } else {
                    new PNotify({
                        title: data.title,
                        text: data.message,
                        addclass: 'bg-danger'
                    });
                }
                $.unblockUI();
            }
        });
    }

    function _initTable() {
        $.ajax({
            url: '<?php echo Saccommodation::$path; ?>lists',
            type: 'POST',
            dataType: 'json',
            data: {modId: '<?php echo $modId; ?>', organizationId: $('#organizationId', windowId).val()},
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                oTable.fnClearTable();
                for (i = 0; i < data.length; i++) {
                    oTable.fnAddData([data[i][0], data[i][1], data[i][2], data[i][3], data[i][4], data[i][5], data[i][6]]);
                }
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        });
    }

</script>