var _permissionTourItinerary = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).bind('keydown', 'f2', function () {
    _addFormTourItinerary({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchContentMedia({elem: this});
});

function _initTourItinerary(param) {

    var _this = $(_tourItineraryWindowId);

    var _height = $(_rootContainerId).height() - 30;
    var _width = $('.tab-content').width();

    _this.html('<table id="dgTourItinerary"></table>');
    _dgTourItinerary = $('#dgTourItinerary').datagrid({
        url: _tourItineraryModRootPath + 'lists',
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
                    _addFormTourItinerary({elem: this});
                }
            }, {
                text: 'Засах (F3)',
                iconCls: 'dg-icon-edit',
                disabled: false,
                handler: function () {
                    var _row = _dgTourItinerary.datagrid('getSelected');
                    if (_row != null) {
                        _editFormTourItinerary({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    var _row = _dgTourItinerary.datagrid('getSelected');
                    if (_row != null) {
                        _deleteContentMedia({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    _advensedSearchNifsCrime({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'number', title: '#'},
                {field: 'pic', title: 'Зураг', width: 150},
                {field: 'title', title: 'Гарчиг', width: 550},
                {field: 'modified_date', title: 'Огноо', width: 110, align: 'center'},
                {field: 'is_active', title: 'Төлөв', width: 60, align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        },
        onDblClickRow: function () {
            var _row = _dgTourItinerary.datagrid('getSelected');
            _editFormTourItinerary({elem: this, id: _row.id, createdUserId: _row.created_user_id});
        },
        onLoadSuccess: function () {
            
        }
    });
    $.unblockUI();

}
function _deleteContentMedia(param) {
    if ((_permissionTourItinerary.our.delete && param.userId == _uIdCurrent) || (_permissionTourItinerary.your.delete && param.userId != _uIdCurrent)) {
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
                            url: _tourItineraryModRootPath + 'delete',
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
                                _initTourItinerary({page: 0});
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
function _addFormTourItinerary(param) {

    if (_permissionTourItinerary.our.create) {
        if (!$(_tourItineraryDialogId).length) {
            $('<div id="' + _tourItineraryDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _tourItineraryModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, contId: $(_tourItineraryWindowId).attr('data-cont-id'), modId: $(_tourItineraryWindowId).attr('data-mod-id')},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_tourItineraryDialogId).empty().html(data.html);

                $(_tourItineraryDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_tourItineraryDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_tourItineraryDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="mediaIntroText"]').val(CKEDITOR.instances.mediaIntroText.getData());
                                var _form = $(_tourItineraryDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _tourItineraryModRootPath + 'insert',
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
                                            _initTourItinerary({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_tourItineraryDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_tourItineraryDialogId).dialog('open');
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
            $('.pickatime-limits').pickatime({
                min: [7, 30],
                max: [14, 0],
                formatSubmit: 'HH:i',
                hiddenName: true
            });
            CKEDITOR.replace('mediaIntroText');
            _orderNum();

        });
    } else {
        _pageDeny();
    }

}
function _editFormTourItinerary(param) {
    if ((_permissionTourItinerary.our.update && param.userId == _uIdCurrent) || (_permissionTourItinerary.your.update && param.userId != _uIdCurrent)) {
        if (!$(_tourItineraryDialogId).length) {
            $('<div id="' + _tourItineraryDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _tourItineraryModRootPath + 'edit',
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
                $(_tourItineraryDialogId).html(data.html);

                $(_tourItineraryDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_tourItineraryDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_tourItineraryDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="mediaIntroText"]').val(CKEDITOR.instances.mediaIntroText.getData());
                                var _form = $(_tourItineraryDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _tourItineraryModRootPath + 'update',
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
                                            _initTourItinerary({page: 0, searchQuery: {}});
                                        }
                                    }).done(function () {
                                        $(_tourItineraryDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_tourItineraryDialogId).dialog('open');
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
            $('.pickatime-limits').pickatime({
                min: [7, 30],
                max: [14, 0],
                formatSubmit: 'HH:i',
                hiddenName: true
            });
            CKEDITOR.replace('mediaIntroText');
            _orderNum();

        });
    } else {
        _pageDeny();
    }

}