var _dgStatus = '';
var _getStatusUrlModule = _getUrlModule();
var _permissionStatus = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getStatusUrlModule == 'sstatus') {
        _initStatus({searchQuery:'selectedId=' + (getUrlParameter('selectedId') === undefined ? '' : getUrlParameter('selectedId')) + '&inDate=' + (getUrlParameter('inDate') === undefined ? '' : getUrlParameter('inDate')) + '&outDate=' + (getUrlParameter('outDate') === undefined ? '' : getUrlParameter('outDate')) + '&departmentId=' + (getUrlParameter('departmentId') === undefined ? '' : getUrlParameter('departmentId')) + '&keyword=' + (getUrlParameter('keyword') === undefined ? '' : getUrlParameter('keyword'))});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getStatusUrlModule == 'sstatus') {
        _addFormStatus({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getStatusUrlModule == 'sstatus') {
        var _row = _dgStatus.datagrid('getSelected');
        if (_row != null) {
            _editFormStatus({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getStatusUrlModule == 'sstatus') {
        var _row = _dgStatus.datagrid('getSelected');
        if (_row != null) {
            _deleteStatus({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
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
$(document).bind('keydown', 'f10', function () {
    if (_getStatusUrlModule == 'sstatus') {
        _advensedSearchStatus({elem: this});
    }
});
function _initStatus(param) {
    if (_permissionStatus.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-status"><table id="dgStatus" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgStatus = $('#dgStatus').datagrid({
            url: _statusModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Системийн төлөв бүртгэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    disabled: false,
                    handler: function () {
                        _addFormStatus({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    disabled: false,
                    handler: function () {
                        var _row = _dgStatus.datagrid('getSelected');
                        if (_row != null) {
                            _editFormStatus({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgStatus.datagrid('getSelected');
                        if (_row != null) {
                            _deleteStatus({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
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
                }, '-', {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchStatus({elem: this});
                    }
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'num', title: '#'},
                    {field: 'title', title: 'Гарчиг', width: 300},
                    {field: 'module_title', title: 'Модуль', width: 200},
                    {field: 'modified_date', title: 'Огноо', width: 200},
                    {field: 'id', title: 'ID', align: 'center', width: 60},
                    {field: 'is_active', title: 'Төлөв', align: 'center', width: 60}
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();

            },
            onRowContextMenu: function (e, rowIndex, rowData) {
                e.preventDefault();

            },
            onLoadSuccess: function (data) {
                if (!$('._search-result-inner').length) {
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                } else {
                    $('._search-result-inner').remove();
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                }
            }, onDblClickRow: function () {
                var _row = _dgStatus.datagrid('getSelected');
                _editFormStatus({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}

function _deleteStatus(param) {

    if ((_permissionStatus.our.delete && param.createdUserId == _uIdCurrent) || (_permissionStatus.your.delete && param.createdUserId != _uIdCurrent)) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html(_dialogAlertDeleteMessage);
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
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_dialogAlertDialogId).dialog('close').empty();
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _statusModRootPath + 'delete',
                            dataType: "json",
                            data: {moduleMenuId: _MODULE_MENU_ID, id: param.id, createdUserId: param.createdUserId, modId: param.modId},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initStatus({page: 0, searchQuery: $(_rootContainerId).find(_statusFormMainId + '-init').serialize()});

                                $.unblockUI();
                            }
                        });
                        $(_dialogAlertDialogId).dialog('close').empty();
                    }}

            ]
        });
        $(_dialogAlertDialogId).dialog('open');
    } else {
        _pageDeny();
    }

}
function _advensedSearchStatus(param) {
    if (_permissionStatus.isModule) {
        if (!$(_statusDialogId).length) {
            $('<div id="' + _statusDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _statusModRootPath + 'searchForm',
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
                $(_statusDialogId).html(data.html);
                $(_statusDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_statusDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_statusDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initStatus({page: 0, searchQuery: $(_statusDialogId).find(_statusFormMainId + '-search').serialize()});
                                $(_statusDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_statusDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initStatus({page: 0, searchQuery: $(_statusDialogId).find(_statusFormMainId + '-search').serialize()});
                    $(_statusDialogId).empty().dialog('close');
                }
            });

        });
    } else {
        _pageDeny();
    }
}
function _addFormStatus(param) {

    if (_permissionStatus.our.create) {

        if (!$(_statusDialogId).length) {
            $('<div id="' + _statusDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _statusModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsCrimeModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                if (data.is_page_deny) {
                    _pageDeny();
                } else {
                    $(_statusDialogId).empty().html(data.html);
                    $(_statusDialogId).dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: false,
                        autoOpen: false,
                        title: data.title,
                        width: data.width,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $(_statusDialogId).empty().dialog('close');
                        },
                        buttons: [
                            {text: data.btn_no, class: 'btn btn-default', click: function () {
                                    $(_statusDialogId).empty().dialog('close');
                                }},
                            {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                    var _form = $(_statusDialogId).find('form');
                                    $(_form).validate({errorPlacement: function () {
                                        }});
                                    if ($(_form).valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: _statusModRootPath + 'insert',
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
                                                _initStatus({page: 0, searchQuery: {}});
                                                $.unblockUI();
                                            }
                                        });
                                    }
                                    $(_statusDialogId).empty().dialog('close');
                                }}
                        ]
                    });
                    $(_statusDialogId).dialog('open');
                }

                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();

        });
    } else {
        _pageDeny();
    }
}
function _editFormStatus(param) {
    if ((_permissionStatus.our.update && param.createdUserId == _uIdCurrent) || (_permissionStatus.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_statusDialogId).length) {
            $('<div id="' + _statusDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _statusModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_statusDialogId).empty().html(data.html);
                if (data.is_page_deny) {
                    _pageDeny();
                } else {
                    $(_statusDialogId).dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: false,
                        autoOpen: false,
                        title: data.title,
                        width: data.width,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $(_statusDialogId).empty().dialog('close');
                        },
                        buttons: [
                            {text: data.btn_no, class: 'btn btn-default', click: function () {
                                    $(_statusDialogId).empty().dialog('close');
                                }},
                            {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                    var _form = $(_statusDialogId).find('form');
                                    $(_form).validate({errorPlacement: function () {
                                        }});
                                    if ($(_form).valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: _statusModRootPath + 'update',
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
                                                _initStatus({page: 0, searchQuery: $(_rootContainerId).find(_statusFormMainId + '-init').serialize()});
                                                $.unblockUI();
                                            }
                                        });
                                    }
                                    $(_statusDialogId).empty().dialog('close');
                                }}
                        ]
                    });
                    $(_statusDialogId).dialog('open');
                }

                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();

        });
    } else {
        _pageDeny();
    }
}