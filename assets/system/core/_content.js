var _dgContent = '';
var _getContentUrlModule = _getUrlModule();
var _permissionContent = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getContentUrlModule == 'scontent') {
        _initContent({page: 0, searchQuery: $(_rootContainerId).find(_contentFormMainId + '-init').serialize()});
    }

});

$(document).bind('keydown', 'f2', function () {
    if (_getContentUrlModule == 'scontent') {
        _addFormContent({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getContentUrlModule == 'scontent') {
        var _row = _dgContent.datagrid('getSelected');
        if (_row != null) {
            _editFormContent({elem: this, id: _row.id});
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
    if (_getContentUrlModule == 'scontent') {
        _advensedSearchContent({elem: this});
    }
});

function _initContent(param) {
    if (_permissionContent.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;
        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-content"><table id="dgContent" style="width:100%;"></table></div></div></div>');

        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgContent = $('#dgContent').datagrid({
            url: _contentModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Агуулгын бүртгэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormContent({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgContent.datagrid('getSelected');
                        if (_row != null) {
                            _editFormContent({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgContent.datagrid('getSelected');
                        if (_row != null) {
                            _deleteContent({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _advensedSearchContent({elem: this});
                    }
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'num', title: '#'},
                    {field: 'pic', title: 'Зураг', width: 100, align: 'center'},
                    {field: 'title', title: 'Гарчиг', width: 550},
                    {field: 'modified_date', title: 'Огноо', width: 100},
                    {field: 'cat_title', title: 'Ангилал', width: 300},
                    {field: 'id', title: 'ID', width: 60, align: 'center'},
                    {field: 'is_active', title: 'Төлөв', width: 60, align: 'center'}
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();
                $(this).datagrid('columnMenu').menu('show', {
                    left: e.pageX,
                    top: e.pageY
                });
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();

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
                var _row = _dgContent.datagrid('getSelected');
                _editFormContent({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _deleteContent(param) {
    if ((_permissionContent.our.delete && param.createdUserId == _uIdCurrent) || (_permissionContent.your.delete && param.createdUserId != _uIdCurrent)) {
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
                            url: _contentModRootPath + 'delete',
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
                                _initContent({page: 0, searchQuery: {}});
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
function _advensedSearchContent(param) {
    if (_permissionContent.isModule) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _contentModRootPath + 'searchForm',
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

                                _initContent({page: 0, searchQuery: $(_dialogAlertDialogId).find(_contentFormMainId + '-search').serialize()});
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
            $('.radio, .checkbox').uniform({radioClass: 'choice'});

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
                    _initContent({page: 0, searchQuery: $(_dialogAlertDialogId).find(_contentFormMainId + '-search').serialize()});
                    $(_dialogAlertDialogId).empty().dialog('close');
                }
            });
        });
    } else {
        _pageDeny();
    }
}
function _loadContextMenuContent(param) {

    return {
        "add": {
            name: "Шинэ бүртгэл (F2)",
            icon: "add",
            callback: function () {
                _addFormContent({elem: this});

            }
        },
        "edit": {
            name: "Засварлах",
            icon: "edit",
            callback: function (key, opt) {
                _editFormContent({elem: this, id: param.row.id});
            }
        },
        "separator": '---------',
        "delete": {
            name: "Устгах",
            icon: "delete",
            callback: function () {
                var _tr = $(this).parents('tr');
                _deleteContent({elem: this, id: _tr.attr('data-id')});
            }
        }
    }
}
function _addFormContent(param) {
    if (_permissionContent.our.create) {
        if (!$(_contentDialogId).length) {
            $('<div id="' + _contentDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _contentModRootPath + 'add',
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
                $(_contentDialogId).empty().html(data.html);

                $(_contentDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_contentDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_contentDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="fullText"]').val(CKEDITOR.instances.fullText.getData());

                                var _form = $(_contentDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _contentModRootPath + 'insert',
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
                                            _initContent({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_contentDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_contentDialogId).dialog('open');
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
            CKEDITOR.replace('fullText');

        });
    } else {
        _pageDeny();
    }
}
function _editFormContent(param) {
    if ((_permissionContent.our.update && param.createdUserId == _uIdCurrent) || (_permissionContent.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_contentDialogId).length) {
            $('<div id="' + _contentDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _contentModRootPath + 'edit',
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
                $(_contentDialogId).html(data.html);

                $(_contentDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_contentDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_contentDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="fullText"]').val(CKEDITOR.instances.fullText.getData());
                                var _form = $(_contentDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _contentModRootPath + 'update',
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
                                            _initContent({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_contentDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_contentDialogId).dialog('open');
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
            CKEDITOR.replace('fullText');

            _initContentMedia();
            _initContentComment();



        });
    } else {
        _pageDeny();
    }
}