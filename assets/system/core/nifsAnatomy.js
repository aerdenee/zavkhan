var _dgNifsAnatomy = '';
var _getNifsAnatomyUrlModule = _getUrlModule();
var _permissionNifsAnatomy = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getNifsAnatomyUrlModule == 'snifsAnatomy') {
        _initNifsAnatomy({searchQuery: 'selectedId=' + (getUrlParameter('selectedId') === undefined ? '' : getUrlParameter('selectedId')) + '&inDate=' + (getUrlParameter('inDate') === undefined ? '' : getUrlParameter('inDate')) + '&outDate=' + (getUrlParameter('outDate') === undefined ? '' : getUrlParameter('outDate')) + '&departmentId=' + (getUrlParameter('departmentId') === undefined ? '' : getUrlParameter('departmentId')) + '&keyword=' + (getUrlParameter('keyword') === undefined ? '' : getUrlParameter('keyword'))});
    }

});
$(document).bind('keydown', 'f2', function () {
    if (_getNifsAnatomyUrlModule == 'snifsAnatomy') {
        _addFormNifsAnatomy({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsAnatomyUrlModule == 'snifsAnatomy') {
        var _row = _dgNifsAnatomy.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsAnatomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsAnatomyUrlModule == 'snifsAnatomy') {
        var _row = _dgNifsAnatomy.datagrid('getSelected');
        if (_row != null) {
            _closeNifsAnatomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsAnatomyUrlModule == 'snifsAnatomy') {
        var _row = _dgNifsAnatomy.datagrid('getSelected');
        if (_row != null) {
            _deleteNifsAnatomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsAnatomyUrlModule == 'snifsAnatomy') {
        _exportNifsAnatomy({elem: this});
    }

});
$(document).bind('keydown', 'f10', function () {
    if (_getNifsAnatomyUrlModule == 'snifsAnatomy') {
        _advensedSearchNifsAnatomy({elem: this});
    }
});

function _exportNifsAnatomy(param) {
    if (_permissionNifsAnatomy.custom.export) {

        var _preparingFileModal = $("#file-download-preparing-file-modal");

        _preparingFileModal.dialog({modal: true});

        $.fileDownload('/' + _nifsAnatomyModRootPath + 'export', {
            httpMethod: 'GET',
            data: $(_rootContainerId).find(_nifsAnatomyFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
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
function _initNifsAnatomy(param) {
    if (_permissionNifsAnatomy.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-anatomy"><table id="dgNifsAnatomy" style="width:100%;"></table></div></div></div>');

        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsAnatomy = $('#dgNifsAnatomy').datagrid({
            url: _nifsAnatomyModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Задлан шинжилгээ',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormNifsAnatomy({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgNifsAnatomy.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsAnatomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    disabled: false,
                    handler: function () {
                        var _row = _dgNifsAnatomy.datagrid('getSelected');
                        if (_row != null) {
                            _closeNifsAnatomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgNifsAnatomy.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsAnatomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    disabled: _IS_EXPORT,
                    handler: function () {
                        _exportNifsAnatomy({elem: this});
                    }
                }, '-', {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchNifsAnatomy({elem: this});
                    }
                }, '-', {
                    text: 'Хими',
                    iconCls: 'dg-icon-chemical',
                    handler: function () {
                        var _row = _dgNifsAnatomy.datagrid('getSelected');

                        if (_row != null && _row.send_document_chemical_id == 0 && _row.send_document_chemical_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 11, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsAnatomy, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_chemical_id != 0 && _row.send_document_chemical_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 11, id: _row.send_document_chemical_id, reloadDataGrid: _dgNifsAnatomy, createdUserId: _row.created_user_id});

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
                        var _row = _dgNifsAnatomy.datagrid('getSelected');

                        if (_row != null && _row.send_document_biology_id == 0 && _row.send_document_biology_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 8, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsAnatomy, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_biology_id != 0 && _row.send_document_biology_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 8, id: _row.send_document_biology_id, reloadDataGrid: _dgNifsAnatomy, createdUserId: _row.created_user_id});

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
                        var _row = _dgNifsAnatomy.datagrid('getSelected');

                        if (_row != null && _row.send_document_bakterlogy_id == 0 && _row.send_document_bakterlogy_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 10, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsAnatomy, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_bakterlogy_id != 0 && _row.send_document_bakterlogy_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 10, id: _row.send_document_bakterlogy_id, reloadDataGrid: _dgNifsAnatomy, createdUserId: _row.created_user_id});

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
                    {field: 'is_mixx', title: ' ', align: 'center', width: 20},
                    {field: 'in_out_date', title: 'Бүртгэл', width: 70},
                    {field: 'be_date', title: 'Үзлэг', width: 70},
                    {field: 'full_name', title: 'Шинжлүүлэгч', width: 150},
                    {field: 'is_work', title: 'Ажил', width: 50},
                    {field: 'partner', title: 'Тогтоол ИГ', width: 100},
                    {field: 'short_value', title: 'БХТ', width: 100},
                    {field: 'expert', title: 'Эмч', width: 150,
                        styler: function (value, row, index) {
                            return row.expert_status;

                        }},
                    {field: 'is_where', title: 'Хаана', width: 100},
                    {field: 'diagnosis', title: 'Онош', width: 150},
                    {field: 'send_document_chemical', title: '<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical.svg" style="width:16px;">', width: 30, align: 'center'},
                    {field: 'send_document_biology', title: '<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna.svg" style="width:16px;">', width: 30, align: 'center'},
                    {field: 'send_document_bakterlogy', title: '<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery.svg" style="width:16px;">', width: 30, align: 'center'}
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();

                $.contextMenu({selector: '.datagrid-row td', items: _loadContextMenuNifsCrime({row: row})});
            },
            onLoadSuccess: function (data) {

                if (!$('._search-result-inner').length) {
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                } else {
                    $('._search-result-inner').remove();
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                }

                $('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
            }, onDblClickRow: function () {
                var _row = _dgNifsAnatomy.datagrid('getSelected');
                _editFormNifsAnatomy({elem: this, id: _row.id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _closeNifsAnatomy(param) {
    if (_permissionNifsAnatomy.custom.close) {
        if (!$(_nifsAnatomyDialogId).length) {
            $('<div id="' + _nifsAnatomyDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: _nifsAnatomyModRootPath + 'closeFrom',
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
                $(_nifsAnatomyDialogId).empty().html(data.html);
                $(_nifsAnatomyDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsAnatomyDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsAnatomyDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_nifsAnatomyDialogId).find('form');
                                $.ajax({
                                    type: 'post',
                                    url: _nifsAnatomyModRootPath + 'close',
                                    dataType: "json",
                                    data: _form.serialize(),
                                    beforeSend: function () {
                                        $.blockUI({
                                            message: _jqueryBlockUiMessage,
                                            overlayCSS: _jqueryBlockUiOverlayCSS,
                                            css: _jqueryBlockUiMessageCSS
                                        });
                                    },
                                    success: function (data) {
                                        if (data.status === 'success') {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-success'
                                            });
                                            _initNifsAnatomy({page: 0, searchQuery: {}});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                });
                                $(_nifsAnatomyDialogId).dialog('close').empty();
                            }}
                    ]
                });
                $(_nifsAnatomyDialogId).dialog('open');
                $.unblockUI();
            }
        }).done(function () {
            $('.select2').select2();
            var _from = $("#beginDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _to.datepicker("option", "minDate", _getDate(this));
            });
            var _to = $("#endDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {

                _from.datepicker("option", "maxDate", _getDate(this));
            });
        });
    } else {
        _pageDeny();
    }
}
function _deleteNifsAnatomy(param) {
    if ((_permissionNifsAnatomy.our.delete && param.createdUserId == _uIdCurrent) || (_permissionNifsAnatomy.your.delete && param.createdUserId != _uIdCurrent)) {
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
                $(_dialogAlertDialogId).dialog('close').empty();
            },
            buttons: [
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_dialogAlertDialogId).dialog('close').empty();
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _nifsAnatomyModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initNifsAnatomy({modId: _nifsAnatomyModId, page: 0, searchQuery: {}});
                                $.unblockUI();
                            }
                        });
                        $(_dialogAlertDialogId).dialog('close').empty();
                    }}

            ]
        });
        $(_dialogAlertDialogId).dialog('open');
    } else {
        _pageDeny();
    }
}
function _advensedSearchNifsAnatomy(param) {
    if (_permissionNifsAnatomy.isModule) {
        if (!$(_nifsAnatomyDialogId).length) {
            $('<div id="' + _nifsAnatomyDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsAnatomyModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: $(_rootContainerId).find('#form-nifs-anatomy-init').serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsAnatomyDialogId).html(data.html);
                $(_nifsAnatomyDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsAnatomyDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsAnatomyDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initNifsAnatomy({page: 0, searchQuery: $(_nifsAnatomyDialogId).find('form').serialize()});
                                $(_nifsAnatomyDialogId).dialog('close').empty();
                            }}

                    ]
                });
                $(_nifsAnatomyDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            $("#crimeDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            });

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

            var _protocolFrom = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _protocolTo.datepicker("option", "minDate", _getDate(this));
            });
            var _protocolTo = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _protocolFrom.datepicker("option", "maxDate", _getDate(this));
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

            _createNumber();
            _age();

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initNifsAnatomy({page: 0, searchQuery: $(_nifsAnatomyDialogId).find(_nifsAnatomyFormMainId + '-search').serialize()});
                    $(_nifsAnatomyDialogId).empty().dialog('close');
                }
            });
        });
    } else {
        _pageDeny();
    }

}
function _addFormNifsAnatomy(param) {
    if (_permissionNifsAnatomy.our.create) {
        if (!$(_nifsAnatomyDialogId).length) {
            $('<div id="' + _nifsAnatomyDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsAnatomyModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsAnatomyModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsAnatomyDialogId).empty().html(data.html);

                $(_nifsAnatomyDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsAnatomyDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsAnatomyDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_nifsAnatomyDialogId).find('form' + _nifsAnatomyFormMainId);
//                            var _createNumber = true;
//                            $.ajax({
//                                url: _nifsAnatomyModRootPath + 'checkCreateNumber',
//                                type: 'POST',
//                                dataType: 'json',
//                                async: false,
//                                data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsAnatomyModId, createNumber: _form.find('input[name="createNumber"]').val()},
//                                success: function (data) {
//                                    _createNumber = data;
//                                }
//                            });

                                $(_form).validate({errorPlacement: function () {
                                    }});
                                //$(_form).find('#checkNumberCreateNumber').html('<label class="checkNumberCreateNumber pull-left help-block" style="margin-top:0px; margin-left:0px;"><i class="icon-help"></i> Дугаар давхардаж байна</label>');

                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsAnatomyModRootPath + 'insert',
                                        data: $(_form).serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({message: null});
                                        },
                                        success: function (data) {
                                            _PNotify({status: data.status, message: data.message});
                                            _initNifsAnatomy({page: 0, searchQuery: $(_rootContainerId).find(_nifsAnatomyFormMainId + '-init').serialize()});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsAnatomyDialogId).dialog('close').empty();
                                    });
                                }
                            }}

                    ]
                });
                $(_nifsAnatomyDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            $("#crimeDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            });

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

            _createNumber();
            _age();

            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    var _thisVal = $(this).val();
                    if (_thisVal == '643' || _thisVal == '644' || _thisVal == '645' || _thisVal == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#initAnatomyControlExpertHtmlExtra').removeClass('hide');
                    $('#initAnatomyControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initAnatomyControlExpertHtmlExtra').removeClass('show');
                    $('#initAnatomyControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-anatomy-in-out-date-diff-work-day'});
            _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-anatomy-protocol-in-out-date-diff-work-day'});

            var _crimeValueTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/crimeValueLists",
                data: {modId: _nifsAnatomyModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    _crimeValueTags = data;
                }
            });

            $("#shortValue").autocomplete({
                source: _crimeValueTags
            });

            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsAnatomyModId, departmentId: data.departmentId},
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
function _editFormNifsAnatomy(param) {
    if ((_permissionNifsAnatomy.our.update && param.createdUserId == _uIdCurrent) || (_permissionNifsAnatomy.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_nifsAnatomyDialogId).length) {
            $('<div id="' + _nifsAnatomyDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsAnatomyModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsAnatomyModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsAnatomyDialogId).html(data.html);

                $(_nifsAnatomyDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsAnatomyDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsAnatomyDialogId).dialog('close').empty();
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsAnatomyDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsAnatomyModRootPath + 'update',
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
                                            _initNifsAnatomy({page: 0, searchQuery: $(_rootContainerId).find(_nifsAnatomyFormMainId + '-init').serialize()});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsAnatomyDialogId).dialog('close').empty();
                                    });
                                }
                            }},
                        {text: data.btn_save_close, class: 'btn btn-success active legitRipple', click: function () {

                                var _form = $(_nifsAnatomyDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsAnatomyModRootPath + 'update',
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
                                            _closeNifsAnatomy({elem: this, id: _form.find('input[name="id"]').val()});
                                        }
                                    }).done(function () {
                                        $(_nifsAnatomyDialogId).dialog('close').empty();
                                    });
                                }
                            }}
                    ]
                });
                $(_nifsAnatomyDialogId).dialog('open');
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

            _createNumber();
            _age();

            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    var _thisVal = $(this).val();
                    if (_thisVal == '643' || _thisVal == '644' || _thisVal == '645' || _thisVal == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#initAnatomyControlExpertHtmlExtra').removeClass('hide');
                    $('#initAnatomyControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initAnatomyControlExpertHtmlExtra').removeClass('show');
                    $('#initAnatomyControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-anatomy-in-out-date-diff-work-day'});
            _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-anatomy-protocol-in-out-date-diff-work-day'});

            var _crimeValueTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/crimeValueLists",
                data: {modId: _nifsAnatomyModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    _crimeValueTags = data;
                }
            });


            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsAnatomyModId, departmentId: data.departmentId},
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
function _checkAnatomyAge(param) {
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
function _reportNifsAnatomyWorkInformation(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsAnatomyModRootPath + 'getReportWorkInformationData',
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
function _reportNifsAnatomyWeight(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsAnatomyModRootPath + 'getReportWeightData',
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
function _reportNifsAnatomyPartner(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsAnatomyModRootPath + 'getReportPartnerData',
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

function _isPaymentAnatomy(param) {

    var _isPayment = $("input[name='isPayment']:checked").val();
    $("input[name='payment']").val(_isPayment);

    if (_isPayment == 2) {
        $('#paymentDescriptionHtml').show();
    } else {
        $('#paymentDescriptionHtml').hide();
        $('textarea[name="paymentDescription"]').val('');
    }
}