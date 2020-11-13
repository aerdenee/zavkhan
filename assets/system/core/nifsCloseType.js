var _dgNifsCloseType = '';
var _getNifsCloseTypeUrlModule = _getUrlModule();
var _permissionNifsCloseType = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});
$(document).ready(function () {
    if (_getNifsCloseTypeUrlModule == 'snifsCloseType') {
        _initNifsCloseType({page: 0, searchQuery: {}});
    }
});
$(document).bind('keydown', 'f2', function () {
    if (_getNifsCloseTypeUrlModule == 'snifsCloseType') {
        _addFormNifsCloseType({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsCloseTypeUrlModule == 'snifsCloseType') {
        var _row = _dgNifsCloseType.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsCloseType({elem: this, id: _row.id, createdUserId: _row.created_user_id});
        } else {
            if (!$(_dialogAlertDialogId).length) {
                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
            }
            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
            $(_dialogAlertDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: _dialogAlertTitle,
                width: _dialogAlertWidth,
                height: "auto",
                modal: true,
                close: function () {
                    $(_dialogAlertDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                            $(_dialogAlertDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_dialogAlertDialogId).dialog('open');
        }
    }
});
function _initNifsCloseType(param) {

    if (_permissionNifsCloseType.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-close-type"><table id="dgNifsCloseType" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsCloseType = $('#dgNifsCloseType').datagrid({
            url: _nifsCloseTypeModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Шинжилгээ хаасан байдал',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormNifsCloseType({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgNifsCloseType.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsCloseType({elem: this, id: _row.id});
                        } else {
                            if (!$(_dialogAlertDialogId).length) {
                                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
                            $(_dialogAlertDialogId).dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: false,
                                autoOpen: false,
                                title: _dialogAlertTitle,
                                width: _dialogAlertWidth,
                                height: "auto",
                                modal: true,
                                close: function () {
                                    $(_dialogAlertDialogId).dialog('close').empty();
                                },
                                buttons: [
                                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                                            $(_dialogAlertDialogId).dialog('close').empty();
                                        }}

                                ]
                            });
                            $(_dialogAlertDialogId).dialog('open');
                        }
                    }
                }, {
                    text: 'Устгах (F6)',
                    iconCls: 'dg-icon-remove',
                    handler: function () {
                        var _row = _dgNifsCloseType.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsCloseType({elem: this, id: _row.id});
                        } else {
                            if (!$(_dialogAlertDialogId).length) {
                                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
                            $(_dialogAlertDialogId).dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: false,
                                autoOpen: false,
                                title: _dialogAlertTitle,
                                width: _dialogAlertWidth,
                                height: "auto",
                                modal: true,
                                close: function () {
                                    $(_dialogAlertDialogId).dialog('close').empty();
                                },
                                buttons: [
                                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                                            $(_dialogAlertDialogId).dialog('close').empty();
                                        }}

                                ]
                            });
                            $(_dialogAlertDialogId).dialog('open');
                        }

                    }
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'create_number', title: '#'},
                    {field: 'title', title: 'Нэр', width: 200},
                    {field: 'cat_title', title: 'Ангилал', width: 200},
                    {field: 'created_date', title: 'Огноо', width: 80},
                    {field: 'is_active', title: 'Төлөв'}
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();

            },
            onBeforeLoad: function (e, index, row) {

            },
            onLoadSuccess: function (data) {

            }, onDblClickRow: function () {
                var _row = _dgNifsCloseType.datagrid('getSelected');
                _editFormNifsCloseType({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}

function _deleteNifsCloseType(param) {
    if ((_permissionNifsCloseType.our.delete && param.userId == _uIdCurrent) || (_permissionNifsCloseType.your.delete && param.userId != _uIdCurrent)) {
        if (!$(_nifsCloseTypeDialogId).length) {
            $('<div id="' + _nifsCloseTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_nifsCloseTypeDialogId).empty().html(_dialogAlertDeleteMessage);
        $(_nifsCloseTypeDialogId).dialog({
            cache: false,
            resizable: false,
            bgiframe: false,
            autoOpen: false,
            title: _dialogAlertTitle,
            width: _dialogAlertWidth,
            height: "auto",
            modal: true,
            close: function () {
                $(_nifsCloseTypeDialogId).dialog('close').empty();
            },
            buttons: [
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_nifsCloseTypeDialogId).dialog('close').empty();
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _nifsCloseTypeModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initNifsCloseType({page: 0, searchQuery: {}});
                                $(_nifsCloseTypeDialogId).dialog('close');
                                $.unblockUI();
                            }
                        });
                        $(_nifsCloseTypeDialogId).dialog('close').empty();
                    }}

            ]
        });
        $(_nifsCloseTypeDialogId).dialog('open');
    } else {
        _pageDeny();
    }
}
function _advensedSearchNifsCloseType(param) {

    if (!$(_nifsCloseTypeDialogId).length) {
        $('<div id="' + _nifsCloseTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsCloseTypeModRootPath + 'searchForm',
        type: 'POST',
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsCloseTypeDialogId).html(data.html);
            $(_nifsCloseTypeDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsCloseTypeDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsCloseTypeDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initNifsCloseType({page: 0, searchQuery: $(_nifsCloseTypeDialogId).find('form').serialize()});
                            $(_nifsCloseTypeDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_nifsCloseTypeDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();
    });

}
function _loadContextMenuNifsCloseType() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "plus",
            callback: function () {
                _addFormNifsCloseType({elem: this, modId: _nifsCloseTypeModId});
            },
            disabled: function (key, opt) {
                if ($('input[name="our[\'create\']"]').val() == 1) {
                    return this.data('');
                } else {
                    return !this.data('');
                }
            }
        },
        "edit": {
            name: "Засах",
            icon: "edit",
            callback: function () {
                var _tr = $(this).parents('tr');
                _editFormNifsCloseType({elem: this, id: _tr.attr('data-id')});
            },
            disabled: function (key, opt) {
                var _tr = $(this).parents('tr');

                if (($('input[name="our[\'update\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'update\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                    return this.data('');
                } else {
                    return !this.data('');
                }
                return !this.data('');
            }
        },
        "separator1": '---------',
        "delete": {
            name: "Устгах",
            icon: "trash",
            callback: function () {
                var _tr = $(this).parents('tr');
                _removeNifsCloseType({elem: this, modId: _nifsCloseTypeModId, id: _tr.attr('data-id')});
            },
            disabled: function (key, opt) {
                var _tr = $(this).parents('tr');
                if (($('input[name="our[\'delete\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'delete\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                    return this.data('');
                } else {
                    return !this.data('');
                }
            }
        }
    }
}
function _addFormNifsCloseType(param) {
    if (_permissionNifsCloseType.our.create) {
        if (!$(_nifsCloseTypeDialogId).length) {
            $('<div id="' + _nifsCloseTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsCloseTypeModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsCloseTypeDialogId).empty().html(data.html);

                $(_nifsCloseTypeDialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsCloseTypeDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsCloseTypeDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_nifsCloseTypeDialogId).find('form' + _nifsCloseTypeFormMainId);
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsCloseTypeModRootPath + 'insert',
                                        data: $(_form).serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({
                                                message: _jqueryBlockUiMessage,
                                                overlayCSS: _jqueryBlockUiOverlayCSS,
                                                css: _jqueryBlockUiMessageCSS
                                            });
                                        },
                                        success: function (data) {
                                            _PNotify({status: data.status, message: data.message});
                                            _initNifsCloseType({page: 0, searchQuery: {}});
                                            $(_nifsCloseTypeDialogId).dialog('close');
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsCloseTypeDialogId).dialog('close').empty();
                                    });
                                }
                            }}

                    ]
                });
                $(_nifsCloseTypeDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.radio, .checkbox').uniform();
            $('.select2').select2();
        });
    } else {
        _pageDeny();
    }
}
function _editFormNifsCloseType(param) {
    if ((_permissionNifsCloseType.our.update && param.userId == _uIdCurrent) || (_permissionNifsCloseType.your.update && param.userId != _uIdCurrent)) {
        if (!$(_nifsCloseTypeDialogId).length) {
            $('<div id="' + _nifsCloseTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsCloseTypeModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsCloseTypeModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsCloseTypeDialogId).html(data.html);
                $(_nifsCloseTypeDialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsCloseTypeDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsCloseTypeDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsCloseTypeDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsCloseTypeModRootPath + 'update',
                                        data: $(_form).serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({
                                                message: _jqueryBlockUiMessage,
                                                overlayCSS: _jqueryBlockUiOverlayCSS,
                                                css: _jqueryBlockUiMessageCSS
                                            });
                                        },
                                        success: function (data) {
                                            _PNotify({status: data.status, message: data.message});
                                            _initNifsCloseType({page: 0, searchQuery: {}});
                                            $(_nifsCloseTypeDialogId).dialog('close');
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsCloseTypeDialogId).dialog('close').empty();
                                    });
                                }
                            }}
                    ]
                });
                $(_nifsCloseTypeDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.radio, .checkbox').uniform();
            $('.select2').select2();
        });
    } else {
        _pageDeny();
    }

}