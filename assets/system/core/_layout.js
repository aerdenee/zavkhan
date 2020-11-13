var _dgLayout = '';
var _getLayoutUrlModule = _getUrlModule();

$(document).ready(function () {
    if (_getLayoutUrlModule == 'slayout') {
        _initLayout({page: 0, searchQuery: {}});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getLayoutUrlModule == 'slayout') {
        _addFormLayout({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getLayoutUrlModule == 'slayout') {
        var _row = _dgLayout.datagrid('getSelected');
        if (_row != null) {
            _editFormLayout({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getLayoutUrlModule == 'slayout') {
        var _row = _dgLayout.datagrid('getSelected');
        if (_row != null) {
            _deleteLayout({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
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
    if (_getLayoutUrlModule == 'slayout') {
        _advensedSearchLayout({elem: this});
    }
});

function _initLayout(param) {

    var _height = $(_rootContainerId).height() - 30;
    var _width = $(_rootContainerId).width() - 30;

    $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-layout"><table id="dgLayout" style="width:100%;"></table></div></div></div>');
    var _param = [];
    if (param.searchQuery.length > 0) {
        _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
    } else {
        _param.push({moduleMenuId: _MODULE_MENU_ID});
    }
    _dgLayout = $('#dgLayout').datagrid({
        url: _layoutModRootPath + 'lists',
        method: 'get',
        queryParams: _param[0],
        title: 'Медиа',
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
                    _addFormLayout({elem: this});
                }
            }, {
                text: 'Засах (F3)',
                iconCls: 'dg-icon-edit',
                disabled: false,
                handler: function () {
                    var _row = _dgLayout.datagrid('getSelected');
                    if (_row != null) {
                        _editFormLayout({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    var _row = _dgLayout.datagrid('getSelected');
                    if (_row != null) {
                        _deleteLayout({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    _advensedSearchLayout({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'num', title: '#'},
                {field: 'title', title: 'Гарчиг', width: 550},
                {field: 'id', title: 'id'},
                {field: 'modified_date', title: 'Огноо', width: 60, align: 'center'},
                {field: 'is_active', title: 'Төлөв', align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();

            $.contextMenu({selector: '.datagrid-row td', items: _loadContextMenuContent({row: row})});
        },
        onLoadSuccess: function (data) {
            if (!$('._search-result-inner').length) {
                $(_rootContainerId).find('.datagrid').prepend(data.search);
            } else {
                $('._search-result-inner').remove();
                $(_rootContainerId).find('.datagrid').prepend(data.search);
            }
        },
        onDblClickRow: function () {
            var _row = _dgLayout.datagrid('getSelected');
            _editFormLayout({elem: this, id: _row.id, createdUserId: _row.created_user_id});
        }
    });
}
function _deleteLayout(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'delete', moduleMenudId: _MODULE_MENU_ID, createdUserId: param.createdUserId});
    if (_permission) {
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
                            url: _layoutModRootPath + 'delete',
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
                                _initLayout({page: 0, searchQuery: {}});
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
function _advensedSearchLayout(param) {

    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _layoutModRootPath + 'searchForm',
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
            $(_dialogAlertDialogId).html(data.html);
            $(_dialogAlertDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_dialogAlertDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_dialogAlertDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            _initLayout({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
                            $(_dialogAlertDialogId).empty().dialog('close');
                            $.unblockUI();
                        }}
                ]
            });
            $(_dialogAlertDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();

        var _from = $("#inDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _to.datepicker("option", "minDate", _getDate(this));
        });
        var _to = $("#outDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _from.datepicker("option", "maxDate", _getDate(this));
        });

        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initLayout({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
                $(_dialogAlertDialogId).empty().dialog('close');
            }
        });
    });

}
function _addFormLayout(param) {

    if (!$(_layoutDialogId).length) {
        $('<div id="' + _layoutDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $.ajax({
        url: _layoutModRootPath + 'add',
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
            $(_layoutDialogId).empty().html(data.html);

            $(_layoutDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_layoutDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_layoutDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_layoutDialogId).find('form');
                            $(_form).validate({
                                errorPlacement: function () {
                                }});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _layoutModRootPath + 'insert',
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
                                        _initLayout({page: 0, searchQuery: {}});
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_layoutDialogId).empty().dialog('close');
                                });
                            }
                        }}
                ]
            });
            $(_layoutDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {

        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        _initDate({loadName: '.init-date'});
        _initPickatime({loadName: '.pickatime-limits'});

    });

}
function _editFormLayout(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'update', moduleMenudId: _MODULE_MENU_ID, createdUserId: param.createdUserId});
    if (_permission) {
        if (!$(_layoutDialogId).length) {
            $('<div id="' + _layoutDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _layoutModRootPath + 'edit',
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
                $(_layoutDialogId).html(data.html);

                $(_layoutDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_layoutDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_layoutDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_layoutDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _layoutModRootPath + 'update',
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
                                            _initLayout({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_layoutDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_layoutDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $(".fancybox").fancybox();
            $('.radio, .checkbox').uniform();
            $('.select2').select2();

            _initDate({loadName: '.init-date'});
            _initPickatime({loadName: '.pickatime-limits'});
        });
    } else {
        _pageDeny();
    }

}