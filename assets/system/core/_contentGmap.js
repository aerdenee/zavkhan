var _dgContentGmap = '';
var _permissionContactMedia = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).bind('keydown', 'f2', function () {
    _addFormGmap({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchGmap({elem: this});
});

function _initGmap(param) {
    
    var _this = $(_contentGmapWindowId);
    var _height = $(_rootContainerId).height() - 30;
    var _width = $('.tab-content').width();

    _this.html('<table id="dgContentGmap"></table>');
    _dgContentGmap = $('#dgContentGmap').datagrid({
        url: _contentGmapModRootPath + 'lists',
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
                    _addFormContentGmap({elem: this});
                }
            }, {
                text: 'Засах (F3)',
                iconCls: 'dg-icon-edit',
                disabled: false,
                handler: function () {
                    var _row = _dgContentGmap.datagrid('getSelected');
                    if (_row != null) {
                        _editFormContentGmap({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    var _row = _dgContentGmap.datagrid('getSelected');
                    if (_row != null) {
                        _deleteContentGmap({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                {field: 'id', title: '#'},
                {field: 'coordinate', title: 'Координат', width: 300},
                {field: 'address', title: 'Хаяг', width: 250},
                {field: 'created_date', title: 'Огноо', width: 110, align: 'center'},
                {field: 'is_active', title: 'Төлөв', width: 60, align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
            //$.contextMenu({selector: '.context-menu-content-media-selected-row', items: _loadContextMenuContentMedia()});
        },
        onDblClickRow: function () {
            var _row = _dgContentGmap.datagrid('getSelected');
            _editFormContentGmap({elem: this, id: _row.id, createdUserId: _row.created_user_id});
        },
        onLoadSuccess: function () {
            
        }
    });
    $.unblockUI();
}
function _deleteContentGmap(param) {

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
                        url: _contentGmapModRootPath + 'delete',
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
                            _initGmap({page: 0, searchQuery: {}});
                            $.unblockUI();
                        }
                    });
                    $(_dialogAlertDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
function _advensedSearchGmap(param) {

    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _contentGmapModRootPath + 'searchForm',
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

                            _initGmap({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
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
                _initGmap({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                $('#' + _dialogId).empty().dialog('close');
            }
        });
    });

}
function _loadContextMenuGmap() {
    return {
        "add": {
            name: "Шинэ бүртгэл (F2)",
            icon: "plus",
            callback: function () {
                _addFormGmap({elem: this});
            },
            disabled: function (key, opt) {
                if ($('input[name="our[\'create\']"]').val() == 1) {
                    return this.data('');
                }
                return !this.data('');
            }
        },
        "edit": {
            name: "Засварлах",
            icon: "edit",
            callback: function () {
                var _tr = $(this).parents('tr');
                _editFormGmap({elem: this, id: _tr.attr('data-id')});
            },
            disabled: function (key, opt) {
                var _tr = $(this).parents('tr');

                if (($('input[name="our[\'update\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'update\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                    return this.data('');
                }
                return !this.data('');
            }
        },
        "separator": '---------',
        "delete": {
            name: "Устгах",
            icon: "trash",
            callback: function () {
                var _tr = $(this).parents('tr');
                _removeGmap({elem: this, id: _tr.attr('data-id')});
            },
            disabled: function (key, opt) {
                var _tr = $(this).parents('tr');
                if (($('input[name="our[\'delete\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'delete\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                    return this.data('');
                }
                return !this.data('');
            }
        }
    }
}
function _addFormContentGmap(param) {

    if (!$(_contentGmapDialogId).length) {
        $('<div id="' + _contentGmapDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $.ajax({
        url: _contentGmapModRootPath + 'add',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, contId: $(_contentGmapWindowId).attr('data-cont-id'), modId: $(_contentGmapWindowId).attr('data-mod-id')},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_contentGmapDialogId).empty().html(data.html);

            $(_contentGmapDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 1000,
                height: "auto",
                modal: true,
                close: function () {
                    $(_contentGmapDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_contentGmapDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_contentGmapDialogId).find('form');
                            $(_form).validate({
                                errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _contentGmapModRootPath + 'insert',
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
                                        _initGmap({page: 0, searchQuery: {}});
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_contentGmapDialogId).empty().dialog('close');
                                });
                            }
                        }}
                ]
            });
            $(_contentGmapDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {

        $('.select2').select2();
        $('.radio, .checkbox').uniform();

        $(".init-date").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        });

    });

}
function _editFormContentGmap(param) {

    if (!$(_contentGmapDialogId).length) {
        $('<div id="' + _contentGmapDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _contentGmapModRootPath + 'edit',
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
            $(_contentGmapDialogId).html(data.html);

            $(_contentGmapDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 1000,
                height: "auto",
                modal: true,
                close: function () {
                    $(_contentGmapDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_contentGmapDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_contentGmapDialogId).find('form');
                            $(_form).validate({errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _contentGmapModRootPath + 'update',
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
                                        _initGmap({page: 0, searchQuery: {}});
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_contentGmapDialogId).empty().dialog('close');
                                });
                            }
                        }}

                ]
            });
            $(_contentGmapDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {

        $('.radio, .checkbox').uniform();
        $('.select2').select2();

        $(".init-date").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        });

    });

}

