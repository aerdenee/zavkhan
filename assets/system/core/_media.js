var _dgMedia = '';
var _getMediaUrlModule = _getUrlModule();
var _permissionMedia = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getMediaUrlModule == 'smedia') {
        _initMedia({page: 0, searchQuery: $(_rootContainerId).find(_mediaFormMainId + '-init').serialize()});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getMediaUrlModule == 'smedia') {
        _addFormMedia({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getMediaUrlModule == 'smedia') {
        var _row = _dgNifsCrime.datagrid('getSelected');
        if (_row != null) {
            _editFormMedia({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getMediaUrlModule == 'smedia') {
        var _row = _dgNifsCrime.datagrid('getSelected');
        if (_row != null) {
            _deleteMedia({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
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
    if (_getMediaUrlModule == 'smedia') {
        _advensedSearchMedia({elem: this});
    }
});

function _initMedia(param) {
    var _height = $(_rootContainerId).height() - 30;
    var _width = $(_rootContainerId).width() - 30;

    $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-media"><table id="dgMedia" style="width:100%;"></table></div></div></div>');
    var _param = [];
    if (param.searchQuery.length > 0) {
        _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
    } else {
        _param.push({moduleMenuId: _MODULE_MENU_ID});
    }
    _dgMedia = $('#dgMedia').datagrid({
        url: _mediaModRootPath + 'lists',
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
                    _addFormMedia({elem: this});
                }
            }, {
                text: 'Засах (F3)',
                iconCls: 'dg-icon-edit',
                disabled: false,
                handler: function () {
                    var _row = _dgMedia.datagrid('getSelected');
                    if (_row != null) {
                        _editFormMedia({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    var _row = _dgMedia.datagrid('getSelected');
                    if (_row != null) {
                        _deleteMedia({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    _advensedSearchMedia({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'id', title: '#'},
                {field: 'pic', title: 'Зураг', width: 150},
                {field: 'title', title: 'Гарчиг', width: 550},
                {field: 'cat_title', title: 'Ангилал', width: 300},
                {field: 'created_date', title: 'Огноо', width: 80, align: 'center'},
                {field: 'order_num', title: 'Эрэмбэ', align: 'center'},
                {field: 'is_active', title: 'Төлөв', align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();

            $.contextMenu({selector: '.datagrid-row td', items: _loadContextMenuContent({row: row})});
        },
        onDblClickRow: function () {
            var _row = _dgMedia.datagrid('getSelected');
            _editFormMedia({elem: this, id: _row.id, createdUserId: _row.created_user_id});
        }
    });
}
function _deleteMedia(param) {
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
                            url: _mediaModRootPath + 'delete',
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
                                _initMedia({page: 0, searchQuery: {}});
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
function _advensedSearchMedia(param) {

    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _mediaModRootPath + 'searchForm',
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

                            _initMedia({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
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
                _initMedia({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                $('#' + _dialogId).empty().dialog('close');
            }
        });
    });

}
function _loadContextMenuMedia() {
    return {
        "add": {
            name: "Шинэ бүртгэл (F2)",
            icon: "plus",
            callback: function () {
                _addFormMedia({elem: this});
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
                _editFormMedia({elem: this, id: _tr.attr('data-id')});
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
                _deleteMedia({elem: this, id: _tr.attr('data-id')});
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
function _addFormMedia(param) {

    if (!$(_mediaDialogId).length) {
        $('<div id="' + _mediaDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $.ajax({
        url: _mediaModRootPath + 'add',
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
            $(_mediaDialogId).empty().html(data.html);

            $(_mediaDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 1000,
                height: "auto",
                modal: true,
                close: function () {
                    $(_mediaDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_mediaDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_mediaDialogId).find('form');
                            $(_form).validate({
                                errorPlacement: function () {
                                }});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _mediaModRootPath + 'insert',
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
                                        _initMedia({page: 0, searchQuery: {}});
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_mediaDialogId).empty().dialog('close');
                                });
                            }
                        }}
                ]
            });
            $(_mediaDialogId).dialog('open');
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
function _editFormMedia(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'update', moduleMenudId: _MODULE_MENU_ID, createdUserId: param.createdUserId});
    if (_permission) {
        if (!$(_mediaDialogId).length) {
            $('<div id="' + _mediaDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _mediaModRootPath + 'edit',
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
                $(_mediaDialogId).html(data.html);

                $(_mediaDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_mediaDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_mediaDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_mediaDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _mediaModRootPath + 'update',
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
                                            _initMedia({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_mediaDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_mediaDialogId).dialog('open');
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