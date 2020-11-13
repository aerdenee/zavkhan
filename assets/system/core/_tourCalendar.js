var _permissionTourCalendar = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).bind('keydown', 'f2', function () {
    _addFormTourCalendar({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchTourCalendar({elem: this});
});

function _initTourCalendar(param) {

    var _this = $(_tourCalendarWindowId);

    var _height = $(_rootContainerId).height() - 30;
    var _width = $('.tab-content').width();

    _this.html('<table id="dgTourCalendar"></table>');
    _dgTourCalendar = $('#dgTourCalendar').datagrid({
        url: _tourCalendarModRootPath + 'lists',
        method: 'get',
        queryParams: {
            moduleMenuId: _MODULE_MENU_ID,
            contId: _this.attr('data-cont-id'),
            modId: _this.attr('data-mod-id')
        },
        iconCls: 'icon-save',
        pageList: [10, 20, 50, 100, 110, 120, 150, 200],
        pageSize: 100,
        fitColumns: true,
        rownumbers: false,
        toolbar: [{
                text: 'Шинэ (F2)',
                iconCls: 'dg-icon-add',
                handler: function () {
                    _addFormTourCalendar({elem: this});
                }
            }, {
                text: 'Засах (F3)',
                iconCls: 'dg-icon-edit',
                disabled: false,
                handler: function () {
                    var _row = _dgTourCalendar.datagrid('getSelected');
                    if (_row != null) {
                        _editFormTourCalendar({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    var _row = _dgTourCalendar.datagrid('getSelected');
                    if (_row != null) {
                        _deleteTourCalendar({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    _advensedSearchTourCalendar({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'number', title: '#'},
                {field: 'title', title: 'Хугацаа', width: 150},
                {field: 'price', title: 'Үнэ', width: 100, align: 'center'},
                {field: 'intro_text', title: 'Тайлбар', width: 350},
                {field: 'created_date', title: 'Огноо', width: 110, align: 'center'},
                {field: 'is_active', title: 'Төлөв', width: 60, align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        },
        onDblClickRow: function () {
            var _row = _dgTourCalendar.datagrid('getSelected');
            _editFormTourCalendar({elem: this, id: _row.id, createdUserId: _row.created_user_id});
        },
        onLoadSuccess: function () {
            
        }
    });
    $.unblockUI();

}
function _deleteTourCalendar(param) {
    if ((_permissionTourCalendar.our.delete && param.userId == _uIdCurrent) || (_permissionTourCalendar.your.delete && param.userId != _uIdCurrent)) {
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
                            url: _tourCalendarModRootPath + 'delete',
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
                                _initTourCalendar({page: 0});
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
function _addFormTourCalendar(param) {

    if (_permissionTourCalendar.our.create) {
        if (!$(_tourCalendarDialogId).length) {
            $('<div id="' + _tourCalendarDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _tourCalendarModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, contId: $(_tourCalendarWindowId).attr('data-cont-id'), modId: $(_tourCalendarWindowId).attr('data-mod-id')},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_tourCalendarDialogId).empty().html(data.html);

                $(_tourCalendarDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_tourCalendarDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_tourCalendarDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_tourCalendarFormMainId);

                                _form.validate({
                                    errorPlacement: function () {
                                    }});
                                if (_form.valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _tourCalendarModRootPath + 'insert',
                                        data: _form.serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({message: null});
                                        },
                                        success: function (data) {
                                            _PNotify({status: data.status, message: data.message});
                                            _initTourCalendar({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_tourCalendarDialogId).dialog('close').empty();
                                    });
                                }
                            }}
                    ]
                });
                $(_tourCalendarDialogId).dialog('open');
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
            _orderNum();

        });
    } else {
        _pageDeny();
    }

}
function _editFormTourCalendar(param) {
    if ((_permissionTourCalendar.our.update && param.userId == _uIdCurrent) || (_permissionTourCalendar.your.update && param.userId != _uIdCurrent)) {
        if (!$(_tourCalendarDialogId).length) {
            $('<div id="' + _tourCalendarDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _tourCalendarModRootPath + 'edit',
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
                $(_tourCalendarDialogId).html(data.html);

                $(_tourCalendarDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_tourCalendarDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_tourCalendarDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_tourCalendarDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _tourCalendarModRootPath + 'update',
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
                                            _initTourCalendar({page: 0, searchQuery: {}});
                                        }
                                    }).done(function () {
                                        $(_tourCalendarDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_tourCalendarDialogId).dialog('open');
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
            _orderNum();

        });
    } else {
        _pageDeny();
    }

}