var _dgHrPeopleDepartment = '';
var _getHrPeopleDepartmentUrlModule = _getUrlModule();
var _permissionHrPeopleDepartment = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getHrPeopleDepartmentUrlModule == 'shrPeopleDepartment') {
        _initHrPeopleDepartment({page: 0, searchQuery: {}});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getHrPeopleDepartmentUrlModule == 'shrPeopleDepartment') {
        _addFormHrPeopleDepartment({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getHrPeopleDepartmentUrlModule == 'shrPeopleDepartment') {
        var _row = _dgHrPeopleDepartment.datagrid('getSelected');
        if (_row != null) {
            _editFormHrPeopleDepartment({elem: this, id: _row.id});
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
    if (_getHrPeopleDepartmentUrlModule == 'shrPeopleDepartment') {
        _advensedSearchHrPeopleDepartment({elem: this});
    }
});

function _initHrPeopleDepartment(param) {
    if (_permissionHrPeopleDepartment.isModule) {
        $.ajax({
            type: 'get',
            url: _hrPeopleDepartmentModRootPath + 'lists',
            data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&per_page=' + param.page,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_rootContainerId).html(data);
            }
        }).done(function () {
            $('.select2').select2();
            $.unblockUI();
            $.contextMenu({selector: '.context-menu-hr-people-department-selected-row', items: _loadContextMenuHrPeopleDepartment()});
        });
    } else {
        _pageDeny();
    }
}
function _loadContextMenuHrPeopleDepartment() {
    return {
        "add": {
            name: "Шинэ бүртгэл",
            icon: "add",
            callback: function () {
                _addFormHrPeopleDepartment({elem: this});
            }
        },
        "edit": {
            name: "Засварлах",
            icon: "edit",
            callback: function () {
                var _tr = $(this).parents('tr');
                _editFormHrPeopleDepartment({elem: this, id: _tr.attr('data-id'), createdUserId: _tr.attr('data-uid')});
            }
        },
        "separator1": '---------',
        "delete": {
            name: "Устгах",
            icon: "delete",
            callback: function () {
                var _tr = $(this).parents('tr');
                _deleteHrPeopleDepartment({elem: this, id: _tr.attr('data-id'), createdUserId: _tr.attr('data-uid')});
            }
        }
    }
}
function _deleteHrPeopleDepartment(param) {
    if ((_permissionHrPeopleDepartment.our.delete && param.createdUserId == _uIdCurrent) || (_permissionHrPeopleDepartment.your.delete && param.createdUserId != _uIdCurrent)) {
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
                $(_dialogAlertDialogId).empty().dialog('close');
            },
            buttons: [
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_dialogAlertDialogId).empty().dialog('close');
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _hrPeopleDepartmentModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            beforeSend: function () {
                                $.blockUI({
                                    message: _jqueryBlockUiMessage,
                                    overlayCSS: _jqueryBlockUiOverlayCSS,
                                    css: _jqueryBlockUiMessageCSS
                                });
                            },
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initHrPeopleDepartment({page: 0, searchQuery: {}});
                                $.unblockUI();
                            }
                        });
                        $(_dialogAlertDialogId).empty().dialog('close');
                    }}
            ]
        });
        $(_dialogAlertDialogId).dialog('open');
    } else {
        _pageDeny();
    }
}
function _advensedSearchHrPeopleDepartment(param) {
    if (!$(_hrPeopleDepartmentDialogId).length) {
        $('<div id="' + _hrPeopleDepartmentDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _hrPeopleDepartmentModRootPath + 'searchForm',
        type: 'get',
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_hrPeopleDepartmentDialogId).html(data.html);
            $(_hrPeopleDepartmentDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_hrPeopleDepartmentDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_hrPeopleDepartmentDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initHrPeopleDepartment({page: 0, searchQuery: $(_hrPeopleDepartmentDialogId).find('form').serialize()});
                            $(_hrPeopleDepartmentDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_hrPeopleDepartmentDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initHrPeopleDepartment({page: 0, searchQuery: $(_hrPeopleDepartmentDialogId).find('form').serialize()});
                $(_hrPeopleDepartmentDialogId).empty().dialog('close');
            }
        });
    });
}
function _addFormHrPeopleDepartment(param) {
    if (_permissionHrPeopleDepartment.our.create) {
        if (!$(_hrPeopleDepartmentDialogId).length) {
            $('<div id="' + _hrPeopleDepartmentDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _hrPeopleDepartmentModRootPath + 'add',
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
                $(_hrPeopleDepartmentDialogId).empty().html(data.html);

                $(_hrPeopleDepartmentDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 800,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_hrPeopleDepartmentDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_hrPeopleDepartmentDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_hrPeopleDepartmentFormMainId);
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _hrPeopleDepartmentModRootPath + 'insert',
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
                                            _initHrPeopleDepartment({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_hrPeopleDepartmentDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_hrPeopleDepartmentDialogId).dialog('open');
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
function _editFormHrPeopleDepartment(param) {
    if ((_permissionHrPeopleDepartment.our.update && param.createdUserId == _uIdCurrent) || (_permissionHrPeopleDepartment.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_hrPeopleDepartmentDialogId).length) {
            $('<div id="' + _hrPeopleDepartmentDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _hrPeopleDepartmentModRootPath + 'edit',
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
                $(_hrPeopleDepartmentDialogId).html(data.html);

                $(_hrPeopleDepartmentDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 800,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_hrPeopleDepartmentDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_hrPeopleDepartmentDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_hrPeopleDepartmentFormMainId);
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _hrPeopleDepartmentModRootPath + 'update',
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
                                            _initHrPeopleDepartment({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_hrPeopleDepartmentDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_hrPeopleDepartmentDialogId).dialog('open');
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