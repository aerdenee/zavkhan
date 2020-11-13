var _dgNifsSendPhoto = '';
var _getNifsSendPhotoUrlModule = _getUrlModule();
var _permissionNifsSendPhoto = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getNifsSendPhotoUrlModule == 'snifsSendPhoto') {
        _initNifsSendPhoto({page: 0, searchQuery: {}});
    }

});
$(document).bind('keydown', 'f2', function () {
    if (_getNifsSendPhotoUrlModule == 'snifsSendPhoto') {
        _addFormNifsSendPhoto({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsSendPhotoUrlModule == 'snifsSendPhoto') {
        var _row = _dgNifsSendPhoto.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsSendPhoto({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsSendPhotoUrlModule == 'snifsSendPhoto') {
        var _row = _dgNifsSendPhoto.datagrid('getSelected');
        if (_row != null) {
            _delelteNifsSendPhoto({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsSendPhotoUrlModule == 'snifsSendPhoto') {
        _advensedSearchNifsSendPhoto({elem: this});
    }
});

function _initNifsSendPhoto(param) {
    
    if (_permissionNifsSendPhoto.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-send-photo"><table id="dgNifsSendPhoto" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsSendPhoto = $('#dgNifsSendPhoto').datagrid({
            url: _nifsSendPhotoModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Илгээсэн зургийн жагсаалт',
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
                        _addFormNifsSendPhoto({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    disabled: false,
                    handler: function () {
                        var _row = _dgNifsSendPhoto.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsSendPhoto({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgNifsSendPhoto.datagrid('getSelected');
                        if (_row != null) {
                            _delelteNifsSendPhoto({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _advensedSearchNifsSendPhoto({elem: this});
                    }
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'create_number', title: '#'},
                    {field: 'pic', title: 'Зураг', width: 100},
                    {field: 'description', title: 'Тайлбар', width: 350},
                    {field: 'full_name', title: 'Илгээгч', width: 350}
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();

            },
            onLoadSuccess: function (data) {
                $('.datagrid-toolbar').find('tr').append(data.search);
            }, onDblClickRow: function () {
                var _row = _dgNifsSendPhoto.datagrid('getSelected');
                _showImage({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }

}
function _delelteNifsSendPhoto(param) {
    if ((_permissionNifsSendPhoto.our.delete && param.createdUserId == _uIdCurrent) || (_permissionNifsSendPhoto.your.delete && param.createdUserId != _uIdCurrent)) { 
        if (!$(_nifsSendPhotoDialogId).length) {
            $('<div id="' + _nifsSendPhotoDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_nifsSendPhotoDialogId).empty().html(_dialogAlertDeleteMessage);
        $(_nifsSendPhotoDialogId).dialog({
            cache: false,
            resizable: false,
            bgiframe: false,
            autoOpen: false,
            title: _dialogAlertTitle,
            width: _dialogAlertWidth,
            height: "auto",
            modal: true,
            close: function () {
                $(_nifsSendPhotoDialogId).empty().dialog('close');
            },
            buttons: [
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_nifsSendPhotoDialogId).empty().dialog('close');
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _nifsSendPhotoModRootPath + 'delete',
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
                                _initNifsSendPhoto({page: 0, searchQuery: {}});
                                $.unblockUI();
                            }
                        });
                        $(_nifsSendPhotoDialogId).empty().dialog('close');
                    }}
            ]
        });
        $(_nifsSendPhotoDialogId).dialog('open');
    } else {
        _pageDeny();
    }
}
function _advensedSearchNifsSendPhoto(param) {

    var _dialogId = 'contentAdvencedSearchDialog';
    if (!$('#' + _dialogId).length) {
        $('<div id="' + _dialogId + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsSendPhotoModRootPath + 'searchForm',
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
                height: 126,
                modal: true,
                close: function () {
                    $('#' + _dialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $('#' + _dialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            _initNifsSendPhoto({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
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
                _initNifsSendPhoto({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                $('#' + _dialogId).empty().dialog('close');
            }
        });
    });

}
function _addFormNifsSendPhoto(param) {

    if (_permissionNifsSendPhoto.our.create) {
        if (!$(_nifsSendPhotoDialogId).length) {
            $('<div id="' + _nifsSendPhotoDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _nifsSendPhotoModRootPath + 'add',
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
                $(_nifsSendPhotoDialogId).empty().html(data.html);

                $(_nifsSendPhotoDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsSendPhotoDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsSendPhotoDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsSendPhotoDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsSendPhotoModRootPath + 'insert',
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
                                            _initNifsSendPhoto({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsSendPhotoDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_nifsSendPhotoDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $(".fancybox").fancybox();
            $('.select2').select2();
            $('.radio, .checkbox').uniform();

        });
    } else {
        _pageDeny();
    }
}
function _editFormNifsSendPhoto(param) {
    if ((_permissionNifsSendPhoto.our.update && param.createdUserId == _uIdCurrent) || (_permissionNifsSendPhoto.your.update && param.createdUserId != _uIdCurrent)) {
        var _dialogId = '_contentEditDailog';
        if (!$(_nifsSendPhotoDialogId).length) {
            $('<div id="' + _nifsSendPhotoDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsSendPhotoModRootPath + 'edit',
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
                $(_nifsSendPhotoDialogId).html(data.html);

                $(_nifsSendPhotoDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsSendPhotoDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsSendPhotoDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsSendPhotoDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsSendPhotoModRootPath + 'update',
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
                                            _initNifsSendPhoto({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsSendPhotoDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_nifsSendPhotoDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $(".fancybox").fancybox();
            $('.radio, .checkbox').uniform();

        });
    } else {
        _pageDeny();
    }
}
function _showImage(param) {
    if ((_permissionNifsSendPhoto.our.read && param.createdUserId == _uIdCurrent) || (_permissionNifsSendPhoto.your.read && param.createdUserId != _uIdCurrent)) {
        if (!$(_nifsSendPhotoDialogId).length) {
            $('<div id="' + _nifsSendPhotoDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsSendPhotoModRootPath + 'read',
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
                $(_nifsSendPhotoDialogId).html(data.html);

                $(_nifsSendPhotoDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title + ' эх зураг',
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsSendPhotoDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_close, class: 'btn btn-default active', click: function () {
                                $(_nifsSendPhotoDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_nifsSendPhotoDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        });
    } else {
        _pageDeny();
    }
}