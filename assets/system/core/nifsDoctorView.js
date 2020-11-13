var _dgNifsDoctorView = '';
var _getNifsDoctorViewUrlModule = _getUrlModule();
var _permissionNifsDoctorView = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});
$(document).ready(function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        _initNifsDoctorView({searchQuery:'selectedId=' + (getUrlParameter('selectedId') === undefined ? '' : getUrlParameter('selectedId')) + '&inDate=' + (getUrlParameter('inDate') === undefined ? '' : getUrlParameter('inDate')) + '&outDate=' + (getUrlParameter('outDate') === undefined ? '' : getUrlParameter('outDate')) + '&departmentId=' + (getUrlParameter('departmentId') === undefined ? '' : getUrlParameter('departmentId')) + '&keyword=' + (getUrlParameter('keyword') === undefined ? '' : getUrlParameter('keyword'))});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        _addFormNifsDoctorView({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        var _row = _dgNifsDoctorView.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsDoctorView({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
$(document).bind('keydown', 'f4', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        var _row = _dgNifsDoctorView.datagrid('getSelected');
        if (_row != null) {
            _closeNifsDoctorView({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        var _row = _dgNifsDoctorView.datagrid('getSelected');
        if (_row != null) {
            _deleteNifsDoctorView({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
$(document).bind('keydown', 'f9', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        _exportNifsDoctorView({elem: this});
    }
});
$(document).bind('keydown', 'f10', function () {
    if (_getNifsDoctorViewUrlModule == 'snifsDoctorView') {
        _advensedSearchNifsDoctorView({elem: this});
    }
});

function _exportNifsDoctorView(param) {
    if (_permissionNifsDoctorView.custom.export) {

        var _preparingFileModal = $("#file-download-preparing-file-modal");

        _preparingFileModal.dialog({modal: true});

        $.fileDownload('/' + _nifsDoctorViewModRootPath + 'export', {
            httpMethod: 'GET',
            data: $(_rootContainerId).find(_nifsDoctorViewFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
            successCallback: function (url) {
                _preparingFileModal.dialog('close');
            },
            failCallback: function (responseHtml, url) {

                _preparingFileModal.dialog('close');
                $("#file-download-error-modal").dialog({modal: true});
            }
        }).done(function () {
            _preparingFileModal.dialog('close');
        });
        return false;
    } else {
        _pageDeny();
    }
}
function _initNifsDoctorView(param) {
    if (_permissionNifsDoctorView.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-doctor-view"><table id="dgNifsDoctorView" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsDoctorView = $('#dgNifsDoctorView').datagrid({
            url: _nifsDoctorViewModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Үзлэгийн бүртгэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormNifsDoctorView({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgNifsDoctorView.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsDoctorView({elem: this, id: _row.id});
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
                    text: 'Хаалт (F4)',
                    iconCls: 'dg-icon-lock-1',
                    handler: function () {
                        var _row = _dgNifsDoctorView.datagrid('getSelected');
                        if (_row != null) {
                            _closeNifsDoctorView({elem: this, id: _row.id});
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
                        var _row = _dgNifsDoctorView.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsDoctorView({elem: this, id: _row.id});
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
                    text: 'Экспорт (F9)',
                    iconCls: 'dg-icon-export',
                    handler: function () {
                        _exportNifsDoctorView({elem: this});
                    }
                }, '-', {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchNifsDoctorView({elem: this});
                    }
                }, '-', {
                    text: 'Хими',
                    iconCls: 'dg-icon-chemical',
                    handler: function () {
                        var _row = _dgNifsDoctorView.datagrid('getSelected');

                        if (_row != null && _row.send_document_chemical_id == 0 && _row.send_document_chemical_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 11, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsDoctorView, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_chemical_id != 0 && _row.send_document_chemical_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 11, id: _row.send_document_chemical_id, reloadDataGrid: _dgNifsDoctorView, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_chemical_id != 0 && _row.send_document_chemical_close_type_id != 0) {

                            _readResultNifsSendDocument({elem: this, typeId: 11, contId: _row.id, moduleId: _row.mod_id});

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
                    text: 'Биологи',
                    iconCls: 'dg-icon-dna',
                    handler: function () {
                        var _row = _dgNifsDoctorView.datagrid('getSelected');

                        if (_row != null && _row.send_document_biology_id == 0 && _row.send_document_biology_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 8, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsDoctorView, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_biology_id != 0 && _row.send_document_biology_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 8, id: _row.send_document_biology_id, reloadDataGrid: _dgNifsDoctorView, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_biology_id != 0 && _row.send_document_biology_close_type_id != 0) {

                            _readResultNifsSendDocument({elem: this, typeId: 8, contId: _row.id, moduleId: _row.mod_id});

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
                    text: 'Бактериологи',
                    iconCls: 'dg-icon-baktery',
                    handler: function () {
                        var _row = _dgNifsDoctorView.datagrid('getSelected');

                        if (_row != null && _row.send_document_bakterlogy_id == 0 && _row.send_document_bakterlogy_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 10, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsDoctorView, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_bakterlogy_id != 0 && _row.send_document_bakterlogy_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 10, id: _row.send_document_bakterlogy_id, reloadDataGrid: _dgNifsDoctorView, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_bakterlogy_id != 0 && _row.send_document_bakterlogy_close_type_id != 0) {

                            _readResultNifsSendDocument({elem: this, typeId: 10, contId: _row.id, moduleId: _row.mod_id});

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
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'create_number', title: '#',
                        styler: function (value, row, index) {
                            return row.row_status;

                        }},
                    {field: 'in_out_date', title: 'Бүртгэл', width: 100},
                    {field: 'full_name', title: 'Овог, нэр, РД', width: 140},
                    {field: 'is_work', title: 'Ажил', width: 70},
                    {field: 'partner', title: 'Тогтоол ИГ', width: 150},
                    {field: 'short_value', title: 'БХТ', width: 100},
                    {field: 'expert', title: 'Эмч', width: 80,
                        styler: function (value, row, index) {
                            return row.expert_status;
                        }},
                    {field: 'is_where', title: 'Хаана', align: 'center', width: 70},
                    {field: 'close_type', title: 'Гэмтэл', width: 70},
                    {field: 'description', title: 'Тайлбар', width: 100},
                    {field: 'send_document_chemical', title: '<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical.svg" style="width:16px;">', width: 30, align: 'center'},
                    {field: 'send_document_biology', title: '<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna.svg" style="width:16px;">', width: 30, align: 'center'},
                    {field: 'send_document_bakterlogy', title: '<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery.svg" style="width:16px;">', width: 30, align: 'center'},
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();

            },
            onBeforeLoad: function (e, index, row) {

            },
            onLoadSuccess: function (data) {
                if (!$('._search-result-inner').length) {
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                } else {
                    $('._search-result-inner').remove();
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                }
                $('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
            }, onDblClickRow: function () {
                var _row = _dgNifsDoctorView.datagrid('getSelected');
                _editFormNifsDoctorView({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _closeNifsDoctorView(param) {
    if (_permissionNifsDoctorView.custom.close) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: _nifsDoctorViewModRootPath + 'closeFrom',
            dataType: "json",
            data: {moduleMenuId: _MODULE_MENU_ID, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_dialogAlertDialogId).empty().html(data.html);
                $(_dialogAlertDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_dialogAlertDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_dialogAlertDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_dialogAlertDialogId).find('form');
                                $.ajax({
                                    type: 'post',
                                    url: _nifsDoctorViewModRootPath + 'close',
                                    dataType: "json",
                                    data: _form.serialize(),
                                    success: function (data) {
                                        _PNotify({status: data.status, message: data.message});
                                        _initNifsDoctorView({page: 0, searchQuery: {}});
                                        $.unblockUI();
                                    }
                                });
                                $(_dialogAlertDialogId).dialog('close').empty();
                            }}
                    ]
                });
                $(_dialogAlertDialogId).dialog('open');
                $.unblockUI();
            }
        }).done(function () {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            $(".init-date").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            });
        });
    } else {
        _pageDeny();
    }
}
function _deleteNifsDoctorView(param) {
    if ((_permissionNifsDoctorView.our.delete && param.createdUserId == _uIdCurrent) || (_permissionNifsDoctorView.your.delete && param.createdUserId != _uIdCurrent)) {
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
                $(_dialogAlertDialogId).dialog('close').remove();
            },
            buttons: [
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_dialogAlertDialogId).empty().dialog('close');
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _nifsDoctorViewModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initNifsDoctorView({page: 0, searchQuery: {}});
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
function _advensedSearchNifsDoctorView(param) {
    if (_permissionNifsDoctorView.isModule) {
        if (!$(_nifsDoctorViewDialogId).length) {
            $('<div id="' + _nifsDoctorViewDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsDoctorViewModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: $(_rootContainerId).find('form#form-nifs-doctor-view-init').serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsDoctorViewDialogId).html(data.html);
                $(_nifsDoctorViewDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsDoctorViewDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsDoctorViewDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initNifsDoctorView({modId: _nifsDoctorViewModId, page: 0, searchQuery: $(_nifsDoctorViewDialogId).find('form').serialize()});
                                $(_nifsDoctorViewDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_nifsDoctorViewDialogId).dialog('open');
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

            var _fromOut = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _toOut.datepicker("option", "minDate", _getDate(this));
            });
            var _toOut = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _fromOut.datepicker("option", "maxDate", _getDate(this));
            });

            var _closeDateFrom = $("#closeInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _closeDateTo.datepicker("option", "minDate", _getDate(this));
            });
            var _closeDateTo = $("#closeOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _closeDateFrom.datepicker("option", "maxDate", _getDate(this));
            });

            var _crimeInDate = $("#crimeInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _crimeOutDate.datepicker("option", "minDate", _getDate(this));
            });
            var _crimeOutDate = $("#crimeOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _crimeInDate.datepicker("option", "maxDate", _getDate(this));
            });

            $('#shortValueId').on('change', function () {

                var _this = $(this);
                var _html = '';

                if (_this.val() == 9 || _this.val() == 3) {

                    _html += '<div class="col-md-6">';
                    _html += '<div class="form-group">';
                    _html += '<label for="Эр бэлгийн эс" required="required" class="control-label" defined="1">Эр бэлгийн эс</label>';

                    $.ajax({
                        url: _nifsDoctorViewModRootPath + 'controlNifsIsSpermDropdown',
                        dataType: 'json',
                        async: false,
                        success: function (data) {
                            _html += data;
                        }
                    });

                    _html += '</div>';
                    _html += '</div>';

                    $('#initDoctorViewControlIsSpermHtml').html(_html);

                    _html = '<div class="col-md-6">';
                    _html += '<div class="form-group">';
                    _html += '<label for="Оролцогч" required="required" class="control-label" defined="1">Оролцогч</label>';

                    _html += '<select class="select2" name="isCrimeShip">';
                    _html += '<option value="0" selected="selected"> - Бүгд - </option>';
                    _html += '<option value="1"> Хохирогч </option>';
                    _html += '<option value="2"> Холбогдогч </option>';
                    _html += '</select>';

                    _html += '</div>';
                    _html += '</div>';

                    $('#initDoctorViewControlIsCrimeShipHtml').html(_html);

                    $('.select2').select2();

                } else {

                    $('#initDoctorViewControlIsSpermHtml').html('<input type="hidden" name="isSperm" value="0">');
                    $('#initDoctorViewControlIsCrimeShipHtml').html('<input type="hidden" name="isCrimeShip" value="0">');

                }

            });

            $('input[type="text"]').keypress(function () {

                if (event.keyCode == 13) {
                    _initNifsDoctorView({page: 0, searchQuery: $(_nifsDoctorViewDialogId).find(_nifsDoctorViewFormMainId + '-search').serialize()});
                    $(_nifsDoctorViewDialogId).empty().dialog('close');
                }

            });
        });
    } else {
        _pageDeny();
    }

}
function _addFormNifsDoctorView(param) {
    if (_permissionNifsDoctorView.our.create) {
        if (!$(_nifsDoctorViewDialogId).length) {
            $('<div id="' + _nifsDoctorViewDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsDoctorViewModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsDoctorViewModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsDoctorViewDialogId).empty().html(data.html);

                $(_nifsDoctorViewDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsDoctorViewDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsDoctorViewDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_nifsDoctorViewDialogId).find('form');

                                var _createNumber = true;
                                $.ajax({
                                    url: _nifsDoctorViewModRootPath + 'checkCreateNumber',
                                    type: 'POST',
                                    dataType: 'json',
                                    async: false,
                                    data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsDoctorViewModId, createNumber: _form.find('input[name="createNumber"]').val()},
                                    success: function (data) {
                                        _createNumber = data;
                                    }
                                });

                                $(_form).validate({errorPlacement: function () {
                                    }});
                                $(_form).find('#checkNumberCreateNumber').html('<label class="checkNumberCreateNumber pull-left help-block"><i class="icon-help"></i> Дугаар давхардаж байна</label>');

                                if ($(_form).valid() && _createNumber != false) {

                                    $.ajax({
                                        type: 'post',
                                        url: _nifsDoctorViewModRootPath + 'insert',
                                        data: $(_form).serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({message: null});
                                        },
                                        success: function (data) {
                                            _PNotify({status: data.status, message: data.message});
                                            _initNifsDoctorView({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsDoctorViewDialogId).dialog('close').empty();
                                    });
                                }
                            }}

                    ]
                });
                $(_nifsDoctorViewDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            

            _initPickatime({loadName: '.init-pickatime'});
            
            _initDate({loadName: '#crimeDate'});

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

            var _fromOut = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _toOut.datepicker("option", "minDate", _getDate(this));
            });
            var _toOut = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _fromOut.datepicker("option", "maxDate", _getDate(this));
            });

            $('#shortValueId').on('change', function () {

                var _this = $(this);
                var _html = '';

                if (_this.val() == 7) {

                    $('#initDoctorViewControlShortValueHtml').html('<div class="form-group row"><label class="col-md-4 control-label text-right"></label><div class="col-md-8"><textarea name="shortValue" row="2" class="form-control"></textarea></div></div>');

                } else {

                    $('#initDoctorViewControlShortValueHtml').html('<input type="hidden" name="shortValue" value="">');

                }

                if (_this.val() == 9 || _this.val() == 3) {

                    _html += '<div class="form-group row">';
                    _html += '<label for="Оролцогч" required="required" class="control-label col-md-4 text-right" defined="1">Оролцогч: </label>';
                    _html += '<div class="col-md-8">';
                    _html += '<div class="form-check form-check-inline">';
                    _html += '<label class="form-check-label"><input type="radio" name="isCrimeShip" value="1" class="radio">Хохирогч</label>';
                    _html += '</div>';
                    _html += '<div class="form-check form-check-inline">';
                    _html += '<label class="form-check-label"><input type="radio" name="isCrimeShip" value="0" class="radio" checked="checked">Холбогдогч</label>';
                    _html += '</div>';
                    _html += '</div>';
                    _html += '</div>';

                    $('#initDoctorViewControlIsCrimeShipHtml').html(_html);
                    $('.radio, .checkbox').uniform();

                } else {
                    $('#initDoctorViewControlIsCrimeShipHtml').html('<input type="hidden" name="isCrimeShip" value="0">');
                }

            });

            _createNumber();
            _age();
            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            
            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsDoctorViewModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#expertName").autocomplete({
                source: agentNameTags
            });

        });
    } else {
        _pageDeny();
    }
}
function _editFormNifsDoctorView(param) {
    if ((_permissionNifsDoctorView.our.update && param.createdUserId == _uIdCurrent) || (_permissionNifsDoctorView.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_nifsDoctorViewDialogId).length) {
            $('<div id="' + _nifsDoctorViewDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsDoctorViewModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsDoctorViewModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsDoctorViewDialogId).html(data.html);

                $(_nifsDoctorViewDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsDoctorViewDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsDoctorViewDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsDoctorViewDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsDoctorViewModRootPath + 'update',
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
                                            _initNifsDoctorView({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsDoctorViewDialogId).dialog('close').empty();
                                    });
                                }
                            }},
                        {text: data.btn_save_close, class: 'btn btn-success active legitRipple', click: function () {

                                var _form = $(_nifsDoctorViewDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsDoctorViewModRootPath + 'update',
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
                                            _closeNifsDoctorView({elem: this, id: _form.find('input[name="id"]').val()});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsDoctorViewDialogId).dialog('close').empty();
                                    });
                                }
                            }}
                    ]
                });
                $(_nifsDoctorViewDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            _initPickatime({loadName: '.init-pickatime'});
            _initDate({loadName: '#crimeDate'});

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

            var _fromOut = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _toOut.datepicker("option", "minDate", _getDate(this));
            });
            var _toOut = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _fromOut.datepicker("option", "maxDate", _getDate(this));
            });

            $('#shortValueId').on('change', function () {

                var _this = $(this);
                var _html = '';

                if (_this.val() == 7) {

                    $('#initDoctorViewControlShortValueHtml').html('<div class="form-group row"><label class="col-md-4 control-label text-right"></label><div class="col-md-8"><textarea name="shortValue" row="2" class="form-control"></textarea></div></div>');

                } else {

                    $('#initDoctorViewControlShortValueHtml').html('<input type="hidden" name="shortValue" value="">');

                }

                if (_this.val() == 9 || _this.val() == 3) {

                    _html += '<div class="form-group row">';
                    _html += '<label for="Оролцогч" required="required" class="control-label col-md-4 text-right" defined="1">Оролцогч: </label>';
                    _html += '<div class="col-md-8">';
                    _html += '<div class="form-check form-check-inline">';
                    _html += '<label class="form-check-label"><input type="radio" name="isCrimeShip" value="1" class="radio">Хохирогч</label>';
                    _html += '</div>';
                    _html += '<div class="form-check form-check-inline">';
                    _html += '<label class="form-check-label"><input type="radio" name="isCrimeShip" value="0" class="radio" checked="checked">Холбогдогч</label>';
                    _html += '</div>';
                    _html += '</div>';
                    _html += '</div>';

                    $('#initDoctorViewControlIsCrimeShipHtml').html(_html);
                    $('.radio, .checkbox').uniform();

                } else {
                    $('#initDoctorViewControlIsCrimeShipHtml').html('<input type="hidden" name="isCrimeShip" value="0">');
                }

            });

            _createNumber();
            _age();

            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsDoctorViewModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#expertName").autocomplete({
                source: agentNameTags
            });
        });
    } else {
        _pageDeny();
    }

}
function _checkDoctorViewAge(param) {
    var _this = $(param.elem);
    var _row = _this.parents('.form-group');
    var _age = $(_row.find('input[name="age"]'));
    if (_this.is(":checked")) {
        _age.val('');
        _age.attr('readonly', true);
        _age.attr('required', false);
    } else {
        _age.attr('readonly', false);
        _age.attr('required', true);
    }
}
function _reporNifsDoctorViewtWorkInformation(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsDoctorViewModRootPath + 'getReportWorkInformationData',
            data: $(_reportGeneralFormMainId).serialize() + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_reportGeneralInitWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _reportNifsDoctorViewWeight(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsDoctorViewModRootPath + 'getReportWeightData',
            data: $(_reportGeneralFormMainId).serialize() + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_reportGeneralInitWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _reportNifsDoctorViewPartner(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsDoctorViewModRootPath + 'getReportPartnerData',
            data: $(_reportGeneralFormMainId).serialize() + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_reportGeneralInitWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _isPaymentDoctorView(param) {

    var _isPayment = $("input[name='isPayment']:checked"). val();
    $("input[name='payment']"). val(_isPayment);
    
    if (_isPayment == 2) {
        $('#paymentDescriptionHtml').show();
    } else {
        $('#paymentDescriptionHtml').hide();
        $('textarea[name="paymentDescription"]').val('');
    }
}

