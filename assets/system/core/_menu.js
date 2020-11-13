var _dgMenu = '';
var _getMenuUrlModule = _getUrlModule();

$(document).ready(function () {
    if (_getMenuUrlModule == 'smenu') {
        _initMenu({page: 0, searchQuery: {}});
        $.contextMenu({selector: '.context-menu-menu-selected-row', items: _loadContextMenuMenu()});

    }
});
$(document).bind('keydown', 'f2', function () {
    if (_getMenuUrlModule == 'smenu') {
        _addFormMenu({elem: this});
    }
});
$(document).bind('keydown', 'Ctrl+f', function () {
    if (_getMenuUrlModule == 'smenu') {
        _advensedSearchMenu({elem: this});
    }
});
function _initMenu(param) {
    $.ajax({
        type: 'get',
        url: _menuModRootPath + 'lists',
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
        $('._dg tbody tr').hover(function () {
            $(this).addClass('row-over');
        }, function () {
            $(this).removeClass('row-over');
        });
        $('._dg tbody tr').click(function () {
            $('._dg tbody tr').removeClass('row-selected');
            $(this).addClass('row-selected');
        });
        $.unblockUI();
    });
}
function _loadContextMenuMenu() {
    return {
        "add": {
            name: "Шинэ бүртгэл (F2)",
            icon: "add",
            callback: function () {
                _addFormMenu({elem: this});
            }
        },
        "edit": {
            name: "Засварлах",
            icon: "edit",
            callback: function () {
                var _tr = $(this).parents('tr');
                _editFormMenu({elem: this, id: _tr.attr('data-id'), createdUserId: _tr.attr('data-created-user-id')});
            }
        },
        "separator": '---------',
        "delete": {
            name: "Устгах",
            icon: "delete",
            callback: function () {
                var _tr = $(this).parents('tr');
                _removeMenu({elem: this, id: _tr.attr('data-id'), createdUserId: _tr.attr('data-created-user-id')});
            }
        }
    }
}
function _removeMenu(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'delete', moduleMenudId: _MODULE_MENU_ID, createdUserId: param.createdUserId});
    if (_permission) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html(_dialogAlertDeleteMessage);
        $(_dialogAlertDialogId).dialog({
            show: {
                effect: "fade",
                duration: 500
            },
            hide: {
                effect: "fade",
                duration: 1000
            },
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
                {text: _dialogAlertBtnYes, class: 'btn btn-primary', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _menuModRootPath + 'delete',
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
                                _initMenu({page: 0});
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
function _advensedSearchMenu(param) {

    var _dialogId = 'contentAdvencedSearchDialog';
    if (!$('#' + _dialogId).length) {
        $('<div id="' + _dialogId + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _menuModRootPath + 'searchForm',
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
            $('#' + _dialogId).html(data.html);
            $('#' + _dialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $('#' + _dialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $('#' + _dialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            _initMenu({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                            $('#' + _dialogId).empty().dialog('close');
                            $.unblockUI();
                        }}
                ]
            });
            $('#' + _dialogId).dialog('open');
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
                _initMenu({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                $('#' + _dialogId).empty().dialog('close');
            }
        });
    });

}
function _addFormMenu(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'create', moduleMenudId: _MODULE_MENU_ID, createdUserId: _uIdCurrent});
    if (_permission) {
        if (!$(_menuDialogId).length) {
            $('<div id="' + _menuDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _menuModRootPath + 'add',
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
                $(_menuDialogId).empty().html(data.html);

                $(_menuDialogId).dialog({
                    show: {
                        effect: "fade",
                        duration: 500
                    },
                    hide: {
                        effect: "fade",
                        duration: 1000
                    },
                    cache: true,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: true,
                    title: data.title,
                    width: data.width,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $(_menuDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_menuDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_menuDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _menuModRootPath + 'insert',
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
                                            _initMenu({page: 0, searchQuery: {}});
                                        }
                                    }).done(function () {
                                        $(_menuDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_menuDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            $('.init-date').pickadate({
                labelMonthNext: _globalDatePickerNextMonth,
                labelMonthPrev: _globalDatePickerPrevMonth,
                labelMonthSelect: _globalDatePickerChooseMonth,
                labelYearSelect: _globalDatePickerChooseYear,
                selectMonths: true,
                selectYears: true,
                monthsFull: _globalDatePickerListMonth,
                weekdaysShort: _globalDatePickerListWeekDayShort,
                today: _globalDatePickerChooseToday,
                clear: _globalDatePickerChooseClear,
                close: _globalDatePickerChooseClose,
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd'
            });

            $.unblockUI();

            _orderNum();

            $("#modId").on('change', function () {
                _initMenuCategory({modId: $(this).val()});
                _initMenuContent({modId: $(this).val(), catId: 0});
            });

            $("#catId").on('change', function () {

                _initMenuContent({modId: $(_menuDialogId).find('#modId').val(), catId: $(this).val()});
            });
            $("#contId", _rootContainerId).on('change', function () {


            });
            $('#locationId').on('change', function () {
                _initParentMenuList({elem: this});
            });

        });
    } else {
        _pageDeny();
    }
}
function _editFormMenu(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'update', moduleMenudId: _MODULE_MENU_ID, createdUserId: param.createdUserId});
    if (_permission) {
        if (!$(_menuDialogId).length) {
            $('<div id="' + _menuDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _menuModRootPath + 'edit',
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
                $(_menuDialogId).html(data.html);

                $(_menuDialogId).dialog({
                    show: {
                        effect: "fade",
                        duration: 500
                    },
                    hide: {
                        effect: "fade",
                        duration: 1000
                    },
                    cache: true,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: true,
                    title: data.title,
                    width: data.width,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $(_menuDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_menuDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_menuDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _menuModRootPath + 'update',
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
                                            _initMenu({page: 0});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_menuDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_menuDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            $('.init-date').pickadate({
                labelMonthNext: _globalDatePickerNextMonth,
                labelMonthPrev: _globalDatePickerPrevMonth,
                labelMonthSelect: _globalDatePickerChooseMonth,
                labelYearSelect: _globalDatePickerChooseYear,
                selectMonths: true,
                selectYears: true,
                monthsFull: _globalDatePickerListMonth,
                weekdaysShort: _globalDatePickerListWeekDayShort,
                today: _globalDatePickerChooseToday,
                clear: _globalDatePickerChooseClear,
                close: _globalDatePickerChooseClose,
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd'
            });

            $.unblockUI();

            _orderNum();

            $("#modId").on('change', function () {
                _initMenuCategory({modId: $(this).val()});
                _initMenuContent({modId: $(this).val(), catId: 0});
            });

            $("#catId").on('change', function () {

                _initMenuContent({modId: $(_menuDialogId).find('#modId').val(), catId: $(this).val()});
            });
            $("#contId", _rootContainerId).on('change', function () {


            });
            $('#locationId').on('change', function () {
                _initParentMenuList({elem: this});
            });
            
            _initContentMedia();
        });
    } else {
        _pageDeny();
    }

}
function _initParentMenuList(param) {
    var _this = $(param.elem);
    $.ajax({
        url: _menuModRootPath + 'menuParentList',
        type: 'POST',
        dataType: 'json',
        data: {locationId: _this.val(), parentId: 0, id: 0},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $("#parentId").html(data);
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function () {
        $('.select2').select2();
    });
}
function _initMenuCategory(param) {
    $.ajax({
        url: _menuModRootPath + 'categoryList',
        type: 'POST',
        dataType: 'json',
        data: {modId: param.modId},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $("#catId").html(data);
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function () {
        $('.select2').select2();
    });
}
function _initMenuContent(param) {
    $.ajax({
        url: _menuModRootPath + 'contentList',
        type: 'POST',
        dataType: 'json',
        data: {modId: param.modId, catId: param.catId, contId: 0},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $("#contentListHtml").html(data);
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function () {
        $('.select2').select2();
    });
}