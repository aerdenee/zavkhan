var _dgNifsMotive = '';
var _getNifsMotiveUrlModule = _getUrlModule();
var _permissionNifsMotive = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});
$(document).ready(function () {
    if (_getNifsMotiveUrlModule == 'snifsMotive') {
        _initNifsMotive({page: 0, searchQuery: {}});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        _addFormNifsDoctorView({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        var _row = _dgNifsDoctorView.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsDoctorView({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
$(document).bind('keydown', 'f4', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        var _row = _dgNifsDoctorView.datagrid('getSelected');
        if (_row != null) {
            _closeNifsDoctorView({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
$(document).bind('keydown', 'f6', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        var _row = _dgNifsDoctorView.datagrid('getSelected');
        if (_row != null) {
            _deleteNifsDoctorView({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
$(document).bind('keydown', 'f9', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        _exportNifsDoctorView({elem: this});
    }
});
$(document).bind('keydown', 'f10', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        _advensedSearchNifsDoctorView({elem: this});
    }
});

$(document).ready(function () {
    if (_getUrlModule() == 'snifsMotive') {
        _initNifsMotive({page: 0, searchQuery: {}});
        $.contextMenu({selector: '.context-menu-nifs-motive-selected-row', items: _loadContextMenuNifsMotive()});
    }

});
$(document).bind('keydown', 'f2', function () {
    _addFormNifsMotive({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchNifsMotive({elem: this});
});

function _initNifsMotive(param) {
    if (_permissionNifsMotive.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-motive"><table id="dgNifsMotive" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsMotive = $('#dgNifsMotive').datagrid({
            url: _nifsMotiveModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Шинжилгээ ирүүлсэн үндэслэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormNifsMotive({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgNifsMotive.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsMotive({elem: this, id: _row.id});
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
                        var _row = _dgNifsMotive.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsMotive({elem: this, id: _row.id});
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
                if (!$('._search-result-td').length) {
                    $('.datagrid-toolbar').find('tr').append(data.search);
                } else {
                    $('._search-result-td').remove();
                    $('.datagrid-toolbar').find('tr').append(data.search);
                }
            }, onDblClickRow: function () {
                var _row = _dgNifsMotive.datagrid('getSelected');
                _editFormNifsMotive({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _deleteNifsMotive(param) {

    if (!$(_nifsMotiveDialogId).length) {
        $('<div id="' + _nifsMotiveDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_nifsMotiveDialogId).empty().html(_dialogAlertDeleteMessage);
    $(_nifsMotiveDialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_nifsMotiveDialogId).dialog('close').empty();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_nifsMotiveDialogId).dialog('close').empty();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _nifsMotiveModRootPath + 'delete',
                        dataType: "json",
                        data: {id: param.id},
                        success: function (data) {
                            _PNotify({status: data.status, message: data.message});
                            _initNifsMotive({page: 0, searchQuery: {}});
                            $(_nifsMotiveDialogId).dialog('close');
                            $.unblockUI();
                        }
                    });
                    $(_nifsMotiveDialogId).dialog('close').empty();
                }}

        ]
    });
    $(_nifsMotiveDialogId).dialog('open');
}
function _advensedSearchNifsMotive(param) {

    if (!$(_nifsMotiveDialogId).length) {
        $('<div id="' + _nifsMotiveDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsMotiveModRootPath + 'searchForm',
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
            $(_nifsMotiveDialogId).html(data.html);
            $(_nifsMotiveDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsMotiveDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsMotiveDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initNifsMotive({page: 0, searchQuery: $(_nifsMotiveDialogId).find('form').serialize()});
                            $(_nifsMotiveDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_nifsMotiveDialogId).dialog('open');
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
function _loadContextMenuNifsMotive() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "plus",
            callback: function () {
                _addFormNifsMotive({elem: this, modId: _nifsMotiveModId});
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
                _editFormNifsMotive({elem: this, id: _tr.attr('data-id')});
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
                _removeNifsMotive({elem: this, modId: _nifsMotiveModId, id: _tr.attr('data-id')});
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
function _addFormNifsMotive(param) {
    if (_permissionNifsMotive.our.create) {
        if (!$(_nifsMotiveDialogId).length) {
            $('<div id="' + _nifsMotiveDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsMotiveModRootPath + 'add',
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
                $(_nifsMotiveDialogId).empty().html(data.html);

                $(_nifsMotiveDialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsMotiveDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsMotiveDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_nifsMotiveDialogId).find('form');

                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsMotiveModRootPath + 'insert',
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
                                            _initNifsMotive({page: 0, searchQuery: {}});
                                            $(_nifsMotiveDialogId).dialog('close');
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsMotiveDialogId).dialog('close').empty();
                                    });
                                }
                            }}

                    ]
                });
                $(_nifsMotiveDialogId).dialog('open');
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
function _editFormNifsMotive(param) {
    if ((_permissionNifsMotive.our.update && param.userId == _uIdCurrent) || (_permissionNifsMotive.your.update && param.userId != _uIdCurrent)) {
        if (!$(_nifsMotiveDialogId).length) {
            $('<div id="' + _nifsMotiveDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsMotiveModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsMotiveModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsMotiveDialogId).html(data.html);

                $(_nifsMotiveDialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 700,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsMotiveDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsMotiveDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsMotiveDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsMotiveModRootPath + 'update',
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
                                            _initNifsMotive({page: 0, searchQuery: {}});
                                            $(_nifsMotiveDialogId).dialog('close');
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsMotiveDialogId).dialog('close').empty();
                                    });
                                }
                            }}
                    ]
                });
                $(_nifsMotiveDialogId).dialog('open');
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