var _dgModuleMenu = '';
var _getModuleMenuUrlModule = _getUrlModule();
var _permissionModuleMenu = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getModuleMenuUrlModule == 'smoduleMenu') {
        _initModuleMenu({page: 0, searchQuery: {}});
        $.contextMenu({selector: '.context-menu-modulemenu-selected-row', items: _loadContextMenuModuleMenu()});
    }

});
$(document).bind('keydown', 'f2', function () {
    if (_getModuleMenuUrlModule == 'smoduleMenu') {
        _addFormModuleMenu({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchModuleMenu({elem: this, moduleMenuId: _MODULE_MENU_ID});
});
function _initModuleMenu(param) {
    if (_permissionModuleMenu.isModule) {
    $.ajax({
        type: 'get',
        url: _moduleMenuModRootPath + 'lists',
        data: $(_rootContainerId).find(_moduleMenuFormMainId + '-init').serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&per_page=' + param.page,
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
        $.unblockUI();
    });
    } else {
        _pageDeny();
    }
}
function _deleteModuleMenu(param) {
    if ((_permissionModuleMenu.our.delete && param.userId == _uIdCurrent) || (_permissionModuleMenu.your.delete && param.userId != _uIdCurrent)) {

        if (!$(_moduleMenuDialogId).length) {
            $('<div id="' + _moduleMenuDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_moduleMenuDialogId).empty().html(_dialogAlertDeleteMessage);
        $(_moduleMenuDialogId).dialog({
            cache: false,
            resizable: false,
            bgiframe: false,
            autoOpen: false,
            title: _dialogAlertTitle,
            width: _dialogAlertWidth,
            height: "auto",
            modal: true,
            close: function () {
                $(_moduleMenuDialogId).empty().dialog('close');
            },
            buttons: [
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_moduleMenuDialogId).empty().dialog('close');
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _moduleMenuModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id, moduleMenuId: _MODULE_MENU_ID},
                            beforeSend: function () {
                                $.blockUI({
                                    message: _jqueryBlockUiMessage,
                                    overlayCSS: _jqueryBlockUiOverlayCSS,
                                    css: _jqueryBlockUiMessageCSS
                                });
                            },
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
                                _initModuleMenu({moduleMenuId: _MODULE_MENU_ID, page: 0});
                            }
                        });
                        $(_moduleMenuDialogId).empty().dialog('close');
                    }}

            ]
        });
        $(_moduleMenuDialogId).dialog('open');
    } else {
        _pageDeny();
    }
}
function _advensedSearchModuleMenu(param) {
    if (_permissionModuleMenu.isModule) {
        if (!$(_moduleMenuDialogId).length) {

            $('<div id="' + _moduleMenuDialogId.replace('#', '') + '"></div>').appendTo('body');
            $.ajax({
                url: _moduleMenuModRootPath + 'searchForm',
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
                    $(_moduleMenuDialogId).html(data.html);
                    $(_moduleMenuDialogId).dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: false,
                        autoOpen: false,
                        title: data.title,
                        width: 600,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $(_moduleMenuDialogId).empty().dialog('close');
                        },
                        buttons: [
                            {text: data.btn_no, class: 'btn  btn-default', click: function () {
                                    $(_moduleMenuDialogId).empty().dialog('close');
                                }},
                            {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                    _initModuleMenu({moduleMenuId: _MODULE_MENU_ID, page: 0, searchQuery: $(_moduleMenuDialogId).find('form').serialize()});
                                    $(_moduleMenuDialogId).empty().dialog('close');
                                }}
                        ]
                    });
                    $(_moduleMenuDialogId).dialog('open');
                    $.unblockUI();
                },
                error: function () {
                    $.unblockUI();
                }
            }).done(function (data) {
                $('.select2').select2();
                $('.radio, .checkbox').uniform({radioClass: 'choice'});
                var _from = $("#startDate").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    dateFormat: _dateFormat
                }).on("change", function () {
                    _to.datepicker("option", "minDate", _getDate(this));
                });
                var _to = $("#endDate").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    dateFormat: _dateFormat
                }).on("change", function () {
                    _from.datepicker("option", "maxDate", _getDate(this));
                });
                var _protocolFrom = $("#protocolInDate").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    dateFormat: _dateFormat
                }).on("change", function () {
                    _protocolTo.datepicker("option", "minDate", _getDate(this));
                });
                var _protocolTo = $("#protocolOutDate").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    dateFormat: _dateFormat
                }).on("change", function () {
                    _protocolFrom.datepicker("option", "maxDate", _getDate(this));
                });
            });
        } else {
            $(_moduleMenuDialogId).dialog('open');
        }
    } else {
        _pageDeny();
    }
}
function _loadContextMenuModuleMenu() {
    return {
        "add": {
            name: "Шинэ бүртгэл (F2)",
            icon: "add",
            callback: function () {
                _addFormModuleMenu({elem: this});
            }
        },
        "edit": {
            name: "Засварлах",
            icon: "edit",
            callback: function () {
                var _tr = $(this).parents('tr');
                _editFormModuleMenu({elem: this, id: _tr.attr('data-id'), createdUserId: _tr.attr('data-created-user-id')});
            }
        },
        "separator": '---------',
        "delete": {
            name: "Устгах",
            icon: "delete",
            callback: function () {
                var _tr = $(this).parents('tr');
                _deleteModuleMenu({elem: this, id: _tr.attr('data-id'), createdUserId: _tr.attr('data-created-user-id')});
            }
        }
    }
}
function _addFormModuleMenu(param) {

    if (_permissionModuleMenu.our.create) {
        if (!$(_moduleMenuDialogId).length) {
            $('<div id="' + _moduleMenuDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _moduleMenuModRootPath + 'add',
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
                $(_moduleMenuDialogId).empty().html(data.html);
                $(_moduleMenuDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_moduleMenuDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_moduleMenuDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $(_moduleMenuFormMainId).validate({errorPlacement: function () {
                                    }});
                                if ($(_moduleMenuFormMainId).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _moduleMenuModRootPath + 'insert',
                                        data: $(_moduleMenuFormMainId).serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({
                                                message: _jqueryBlockUiMessage,
                                                overlayCSS: _jqueryBlockUiOverlayCSS,
                                                css: _jqueryBlockUiMessageCSS
                                            });
                                        },
                                        success: function (data) {
                                            if (data.status === 'success') {
                                                new PNotify({
                                                    text: data.message,
                                                    addclass: 'bg-success'
                                                });
                                                _initModuleMenu({moduleMenuId: _MODULE_MENU_ID, page: 0, searchQuery: {}});
                                            } else {
                                                new PNotify({
                                                    text: data.message,
                                                    addclass: 'bg-danger'
                                                });
                                            }
                                            $(_moduleMenuDialogId).empty().dialog('close');
                                            $.unblockUI();
                                        }
                                    });
                                }

                            }}

                    ]
                });
                $(_moduleMenuDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            $('select[name="modId"]').on('change', function () {
                if ($('input[name="menuTypeId"]').val() == 5) {
                    _controlModuleMenuCategory({modId: $(this).val(), selectedId: 0});
                }
            });
        });
    } else {
        _pageDeny();
    }
}
function _editFormModuleMenu(param) {
    if ((_permissionModuleMenu.our.update && param.userId == _uIdCurrent) || (_permissionModuleMenu.your.update && param.userId != _uIdCurrent)) {
        if (!$(_moduleMenuDialogId).length) {

            $('<div id="' + _moduleMenuDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _moduleMenuModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {id: param.id, moduleMenuId: _MODULE_MENU_ID},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_moduleMenuDialogId).empty().html(data.html);
                $(_moduleMenuDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_moduleMenuDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default legitRipple', click: function () {
                                $(_moduleMenuDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $(_moduleMenuFormMainId).validate({errorPlacement: function () {
                                    }});
                                if ($(_moduleMenuFormMainId).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _moduleMenuModRootPath + 'update',
                                        data: $(_moduleMenuFormMainId).serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({
                                                message: _jqueryBlockUiMessage,
                                                overlayCSS: _jqueryBlockUiOverlayCSS,
                                                css: _jqueryBlockUiMessageCSS
                                            });
                                        },
                                        success: function (data) {
                                            if (data.status === 'success') {
                                                new PNotify({
                                                    text: data.message,
                                                    addclass: 'bg-success'
                                                });
                                                _initModuleMenu({moduleMenuId: _MODULE_MENU_ID, page: 0, searchQuery: {}});
                                            } else {
                                                new PNotify({
                                                    text: data.message,
                                                    addclass: 'bg-danger'
                                                });
                                            }
                                            $.unblockUI();
                                            $(_moduleMenuDialogId).dialog('close').remove();
                                        }
                                    });
                                }

                            }}
                    ]
                });
                $(_moduleMenuDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            $('select[name="modId"]').on('change', function () {
                if ($('input[name="menuTypeId"]').val() == 5) {
                    _controlModuleMenuCategory({modId: $(this).val(), selectedId: 0});
                }
            });

        });
    } else {
        _pageDeny();
    }
}
function _inlineSetMenuTypeValue(param) {

    $('input[name="menuTypeId"]').val(param.val);
    if (param.val == 5) {
        $('.moduleMenuLocalContent').show();
        _controlModuleMenuCategory({modId: $('select[name="modId"]').val(), selectedId: 0});
    } else {
        $('.moduleMenuLocalContent').hide();
        $('.select2').select2();
    }
}
function _controlModuleMenuCategory(param) {
    $.ajax({
        url: _moduleMenuModRootPath + 'controlCategoryDropdown',
        type: 'POST',
        dataType: 'json',
        data: {modId: param.modId, selectedId: param.selectedId},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $("#moduleMenuCategory").html(data);
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function () {
        $('.select2').select2();
        _controlModuleContent({modId: param.modId, catId: 0, selectedId: 0});
        $('select[name="moduleMenuCatId"]').on('change', function () {
            _controlModuleContent({modId: $('select[name="modId"]').val(), catId: $(this).val(), contId: 0});
        });
    });
}
function _controlModuleContent(param) {
    $.ajax({
        url: _moduleMenuModRootPath + 'controlContentDropdown',
        type: 'POST',
        dataType: 'json',
        data: {modId: param.modId, catId: param.catId, selectedId: param.selectedId},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $("#moduleMenuContent").html(data);
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function () {
        $('.select2').select2();
    });
}