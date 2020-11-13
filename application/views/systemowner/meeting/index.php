<div id="window-content"><?php echo $dataHtml; ?></div>

<script type="text/javascript">
    var windowId = "#window-content";
    $(function () {
        $.contextMenu({
            selector: '.context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    window.location = '<?php echo Smeeting::$path . 'add/' . $modId; ?>';
                }
                if (key === 'edit') {
                    window.location = '<?php echo Smeeting::$path . 'edit/' . $modId; ?>/' + _tr.attr('data-id');
                }
                if (key === 'print') {
                    console.log(_tr.attr('data-id'));
                }
                if (key === 'view') {
                    window.location = '<?php echo Smeeting::$path . 'view/' . $modId; ?>/' + _tr.attr('data-id');
                }
                if (key === 'delete') {
                    _removeItem(_tr.attr('data-id'));
                }
            },
            items: {
                "add": {name: "Ажил, уулзалт үүсгэх", icon: "plus"},
                "edit": {name: "Ажил, уулзалт засварлах", icon: "edit"},
                "separator": {className: 'context-menu-separator'},
                "print": {name: "Ажил, уулзалт хэвлэх", icon: "print"},
                "view": {name: "Харах", icon: "eye"},
                "separator1": {className: 'context-menu-separator'},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
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
            dialogClass: 'dialog-blue',
            close: function () {
                $("#" + dialogId).empty().dialog('close');
            },
            buttons: [
                {text: "Тийм", class: 'btn btn-xs btn-success', click: function () {
                        $.ajax({
                            type: 'post',
                            url: '<?php echo Smeeting::$path; ?>delete',
                            dataType: "json",
                            data: {id: [id]},
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
                                window.location = '<?php echo Smeeting::$path . 'index/' . $modId; ?>';
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
                            url: '<?php echo Smeeting::$path; ?>delete',
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
            url: '<?php echo Smeeting::$path; ?>isActive',
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
    function _advensedSearch(elem) {
        var dialogId = 'advencedSearchDialog';
        if (!$('#' + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
            $.ajax({
                url: '<?php echo Smeeting::$path; ?>searchForm',
                type: 'POST',
                dataType: 'json',
                data: {modId: <?php echo $modId; ?>},
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    $('#' + dialogId).html(data.html);

                    $('#' + dialogId).dialog({
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
                            $('#' + dialogId).dialog('close');
                        },
                        buttons: [
                            {text: data.btn_yes, class: 'btn btn-xs btn-success', click: function () {
                                    var _form = $('#' + dialogId).find('form');
                                    _form.submit();
                                }},
                            {text: data.btn_no, class: 'btn  btn-default btn-xs', click: function () {
                                    $('#' + dialogId).dialog('close');
                                }}
                        ]
                    });
                    $('#' + dialogId).dialog('open');
                    $.unblockUI();
                },
                error: function () {
                    $.unblockUI();
                }
            }).done(function (data) {
                $('.select2').select2();
                $('.radio, .checkbox').uniform({radioClass: 'choice'});

                $("#generateDate").datepicker({
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: "yy-mm-dd"
                });
                $('#cityId').on('change', function () {
                    console.log(this);
                });
                $('#soumId').on('change', function () {
                    console.log(this);
                });
            });
        } else {
            $('#' + dialogId).dialog('open');
        }
    }
    function _export(elem) {
        var _this = $(elem);
        var _form = _this.parents('form');
        $.fileDownload('/<?php echo Smeeting::$path; ?>export', {
            httpMethod: "POST",
            data: _form.serialize()
        }).done(function () {
            alert('ok download');
            var dialogId = 'alertDialog';
            if (!$('#' + dialogId).length) {
                $('<div id="' + dialogId + '"></div>').appendTo('body');
            }
            $('#' + dialogId).html('Захиалгыг амжилттай excel файл болгон хөрвүүллээ');

            $('#' + dialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Санамж',
                width: 300,
                height: "auto",
                modal: true,
                dialogClass: 'dialog-blue dialog-notification',
                close: function () {
                    $('#' + dialogId).empty().dialog('close');
                },
                buttons: [
                    {text: "Хаах", class: 'btn blue btn-xs', click: function () {
                            $('#' + dialogId).dialog('close');
                        }}
                ]
            });
            $('#' + dialogId).dialog('open');
        }).fail(function () {
            _dialog({
                dialogId: 'alertDialog',
                message: 'Алдаа гарлаа. Та дахин шүүж хөрвүүлэлтийг хийнэ үү',
                title: 'Санамж',
                width: 300,
                class: 'dialog-blue dialog-notification',
                isButton: [{
                        text: 'Хаах',
                        class: 'btn btn-xs btn-default',
                        click: function () {
                            $("#alertDialog").dialog('close');
                        }
                    }]
            });
        });
    }
</script>