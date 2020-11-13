<div id="window-content"><?php echo $dataHtml;?></div>

<script type="text/javascript">
    var windowId = "#window-content";
    $(function(){
        $.contextMenu({
            selector: '.context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    window.location = '<?php echo Seducation::$path . 'add/' . $modId;?>';
                }
                if (key === 'edit') {
                    window.location = '<?php echo Seducation::$path . 'edit/' . $modId;?>/' + _tr.attr('data-id');
                }
                if (key === 'delete') {
                    _removeItem(_tr.attr('data-id'));
                }
            },
            items: {
                "add": {name: "Нэмэх", icon: "plus"},
                "edit": {name: "Засах", icon: "edit"},
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
                            url: '<?php echo Seducation::$path; ?>delete',
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
                                window.location = '<?php echo Seducation::$path . 'index/' . $modId;?>';
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
                            url: '<?php echo Seducation::$path; ?>delete',
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
            url: '<?php echo Seducation::$path; ?>isActive',
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

</script>