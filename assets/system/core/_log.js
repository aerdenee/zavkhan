var _dgLog = '';
var _getLogModule = _getUrlModule();

$(document).ready(function () {
    if (_getLogModule == 'slog') {
        _initLog({page: 0, searchQuery: {}});
    }
});

$(document).bind('keydown', 'f10', function () {
    if (_getNifsCrimeUrlModule == 'snifsCrime') {
        _advensedSearchNifsCrime({elem: this});
    }
});

function _initLog(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'isModule', moduleMenudId: _MODULE_MENU_ID, createdUserId: _uIdCurrent});
    if (_permission) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-log"><table id="dgLog" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgLog = $('#dgLog').datagrid({
            url: _logModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Лог систем',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Түүх харах',
                    iconCls: 'dg-icon-file-view',
                    handler: function () {
                        var _row = _dgLog.datagrid('getSelected');
                _showLog({elem: this, selectedId: _row.id, createdDate: _row.created_date});
                    }
                }, {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchLog({elem: this});
                    }
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'auto_number', title: '#'},
                    {field: 'full_name', title: 'Овог, нэр', width: 150},
                    {field: 'department', title: 'Салбар, хэлтэс', width: 150},
                    {field: 'type', title: 'Үйлдэл', width: 50},
                    {field: 'module', title: 'Модуль', width: 100},
                    {field: 'created_date', title: 'Огноо', width: 70},
                    {field: 'ip_address', title: 'IP хаяг', width: 70}
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
            },
            onLoadSuccess: function (data) {
                if (!$('._search-result-td').length) {
                    $('.datagrid-toolbar').find('tr').append(data.search);
                } else {
                    $('._search-result-td').remove();
                    $('.datagrid-toolbar').find('tr').append(data.search);
                }
            }, onDblClickRow: function () {
                var _row = _dgLog.datagrid('getSelected');
                _showLog({elem: this, selectedId: _row.id, createdDate: _row.created_date});
            }
        });
    } else {
        _pageDeny();
    }
}
function _showLog(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'read', moduleMenudId: _MODULE_MENU_ID, createdUserId: _uIdCurrent});
    if (_permission) {
        if (!$(_logDialogId).length) {
            $('<div id="' + _logDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _logModRootPath + 'show',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, selectedId: param.selectedId, createdDate: param.createdDate},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_logDialogId).html(data.html);
                $(_logDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_logDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: _dialogAlertBtnClose, class: 'btn btn-primary active', click: function () {
                                $(_logDialogId).empty().dialog('close');
                            }}
                    ]
                });
                $(_logDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function () {

            // PHP editor
            var phpeditor = ace.edit('php_editor');
            phpeditor.setTheme("ace/theme/monokai");
            phpeditor.getSession().setMode("ace/mode/php");
            phpeditor.setOptions({
                enableSnippets: true,
                enableLiveAutoComplete: true,
                enableBasicAutocompletion: true,
                useSoftTabs: false,
                setHighlightActiveLine: false,
                enableEmmet: true
            });

        });
    } else {
        _pageDeny();
    }
}
function _advensedSearchLog(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'read', moduleMenudId: _MODULE_MENU_ID, createdUserId: _uIdCurrent});
    if (_permission) {
        if (!$(_logDialogId).length) {
            $('<div id="' + _logDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _logModRootPath + 'searchForm',
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
                $(_logDialogId).html(data.html);
                $(_logDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_logDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_logDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initLog({page: 0, searchQuery: $(_logDialogId).find(_logFormMainId).serialize()});
                                $(_logDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_logDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('#logYear').on('change', function () {
                $('#logMonth').val(0).trigger('change');
                $('#beginDay').val(0).trigger('change');
                $('#endDay').val(0).trigger('change');
            });

            $('#logMonth').on('change', function () {
                var _logYear = parseInt($('#logYear').select2('val'));
                var _logMonth = $(this).val();
                if (_logMonth == 0) {
                    if (!$(_dialogAlertDialogId).length) {
                        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
                    }
                    var dt = new Date();
                    var _month = dt.getMonth();
                    $('#logMonth').val(_month).trigger('change');
                    $(_dialogAlertDialogId).empty().html('<div class="p-3">Лог системээс хайлт хийх сар хоосон байх боломжгүй тул систем автоматаар ' + _month + ' сарыг сонголоо.</div>');
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
                        }
                    });
                    $(_dialogAlertDialogId).dialog('open');
                }
                if (_logYear > 0) {

                    $('#beginDayHtml').html(_getLogDay({logYear: _logYear, logMonth: _logMonth, controlName: 'beginDay'}));
                    $('#endDayHtml').html(_getLogDay({logYear: _logYear, logMonth: _logMonth, controlName: 'endDay'}));
                    $('.select2').select2();

                } else {
                    if (!$(_dialogAlertDialogId).length) {
                        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
                    }
                    var dt = new Date();
                    var _year = dt.getFullYear();
                    $('#logYear').val(_year).trigger('change');
                    $(_dialogAlertDialogId).empty().html('<div class="p-3">Лог системээс хайлт хийх он хоосон байх боломжгүй тул систем автоматаар ' + _year + ' оныг сонголоо.</div>');
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
                        }
                    });
                    $(_dialogAlertDialogId).dialog('open');
                }
            });
        });
    } else {
        _pageDeny();
    }
}

function _getLogDay(param) {
    var _control = '';
    $.ajax({
        url: _logModRootPath + 'controlLogDateDayDropdown',
        type: 'POST',
        dataType: 'json',
        async: false,
        data: {logYear: param.logYear, logMonth: param.logMonth, controlName: param.controlName},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            _control = data;
            $.unblockUI();
        }
    }).done(function () {
        $('.select2').select2();
    });
    return _control;
}