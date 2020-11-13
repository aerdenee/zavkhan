var _dgNifsFileFolder = '';
var _getNifsFileFolderUrlModule = _getUrlModule();
var _permissionNifsFileFolder = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getNifsFileFolderUrlModule == 'snifsFileFolder') {
        _initNifsFileFolder({searchQuery:'selectedId=' + (getUrlParameter('selectedId') === undefined ? '' : getUrlParameter('selectedId')) + '&inDate=' + (getUrlParameter('inDate') === undefined ? '' : getUrlParameter('inDate')) + '&outDate=' + (getUrlParameter('outDate') === undefined ? '' : getUrlParameter('outDate')) + '&departmentId=' + (getUrlParameter('departmentId') === undefined ? '' : getUrlParameter('departmentId')) + '&keyword=' + (getUrlParameter('keyword') === undefined ? '' : getUrlParameter('keyword'))});
    }

});
$(document).bind('keydown', 'f2', function () {
    if (_getNifsFileFolderUrlModule == 'snifsFileFolder') {
        _addFormNifsFileFolder({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsFileFolderUrlModule == 'snifsFileFolder') {
        var _row = _dgNifsFileFolder.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsFileFolder({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsFileFolderUrlModule == 'snifsFileFolder') {
        var _row = _dgNifsFileFolder.datagrid('getSelected');
        if (_row != null) {
            _closeNifsFileFolder({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsFileFolderUrlModule == 'snifsFileFolder') {
        var _row = _dgNifsFileFolder.datagrid('getSelected');
        if (_row != null) {
            _deleteNifsFileFolder({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsFileFolderUrlModule == 'snifsFileFolder') {
        _exportNifsFileFolder({elem: this});
    }

});
$(document).bind('keydown', 'f10', function () {
    if (_getNifsFileFolderUrlModule == 'snifsFileFolder') {
        _advensedSearchNifsFileFolder({elem: this});
    }
});

function _exportNifsFileFolder(param) {
    if (_permissionNifsFileFolder.custom.export) {

        var _preparingFileModal = $("#file-download-preparing-file-modal");

        _preparingFileModal.dialog({modal: true});

        $.fileDownload('/' + _nifsFileFolderModRootPath + 'export', {
            httpMethod: 'GET',
            data: $(_rootContainerId).find(_nifsFileFolderFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
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
function _initNifsFileFolder(param) {
    if (_permissionNifsFileFolder.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-file-folder"><table id="dgNifsFileFolder" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsFileFolder = $('#dgNifsFileFolder').datagrid({
            url: _nifsFileFolderModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Хавтаст хэргийн бүртгэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormNifsFileFolder({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgNifsFileFolder.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsFileFolder({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgNifsFileFolder.datagrid('getSelected');
                        if (_row != null) {
                            _closeNifsFileFolder({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgNifsFileFolder.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsFileFolder({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _exportNifsFileFolder({elem: this});
                    }
                }, '-', {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchNifsFileFolder({elem: this});
                    }
                }, '-', {
                    text: 'Хими',
                    iconCls: 'dg-icon-chemical',
                    handler: function () {
                        var _row = _dgNifsFileFolder.datagrid('getSelected');

                        if (_row != null && _row.send_document_chemical_id == 0 && _row.send_document_chemical_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 11, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsFileFolder, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_chemical_id != 0 && _row.send_document_chemical_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 11, id: _row.send_document_chemical_id, reloadDataGrid: _dgNifsFileFolder, createdUserId: _row.created_user_id});

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
                        var _row = _dgNifsFileFolder.datagrid('getSelected');

                        if (_row != null && _row.send_document_biology_id == 0 && _row.send_document_biology_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 8, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsFileFolder, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_biology_id != 0 && _row.send_document_biology_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 8, id: _row.send_document_biology_id, reloadDataGrid: _dgNifsFileFolder, createdUserId: _row.created_user_id});

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
                        var _row = _dgNifsFileFolder.datagrid('getSelected');

                        if (_row != null && _row.send_document_bakterlogy_id == 0 && _row.send_document_bakterlogy_close_type_id == 0) {

                            _addFormNifsSendDocument({elem: this, typeId: 10, contId: _row.id, modId: _row.mod_id, reloadDataGrid: _dgNifsFileFolder, createdUserId: _row.created_user_id});

                        } else if (_row != null && _row.send_document_bakterlogy_id != 0 && _row.send_document_bakterlogy_close_type_id == 0) {

                            _editFormNifsSendDocument({elem: this, typeId: 10, id: _row.send_document_bakterlogy_id, reloadDataGrid: _dgNifsFileFolder, createdUserId: _row.created_user_id});

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
                    {field: 'in_out_date', title: 'Бүртгэл', width: 96},
                    {field: 'full_name', title: 'Шинжлүүлэгч', width: 150},
                    {field: 'partner', title: 'Тогтоол ТБ', width: 200},
                    {field: 'protocol', title: 'Хэргийн дугаар', width: 150},
                    {field: 'object', title: 'Объект', width: 150},
                    {field: 'pre', title: 'Өмнөх', width: 150},
                    {field: 'expert', title: 'Шинжээч/төрөл', width: 150,
                        styler: function (value, row, index) {
                            return row.expert_status;

                        }},
                    {field: 'report', title: 'Дүгнэлт', width: 150},
                    {field: 'description', title: 'Тайлбар', align: 'center'},
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
            onLoadSuccess: function (data) {
                if (!$('._search-result-inner').length) {
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                } else {
                    $('._search-result-inner').remove();
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                }
                $('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
            }, onDblClickRow: function () {
                var _row = _dgNifsFileFolder.datagrid('getSelected');
                _editFormNifsFileFolder({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }

}
function _closeNifsFileFolder(param) {
    if (_permissionNifsFileFolder.custom.close) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: _nifsFileFolderModRootPath + 'closeFrom',
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
                    position: {
                        my: "center center",
                        at: "center center",
                        of: window
                    },
                    close: function () {
                        $(_dialogAlertDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_dialogAlertDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_dialogAlertDialogId).find('form');
                                $.ajax({
                                    type: 'post',
                                    url: _nifsFileFolderModRootPath + 'close',
                                    dataType: "json",
                                    data: _form.serialize(),
                                    success: function (data) {
                                        _PNotify({status: data.status, message: data.message});
                                        _initNifsFileFolder({page: 0, searchQuery: {}});
                                        $.unblockUI();
                                    }
                                });
                                $(_dialogAlertDialogId).empty().dialog('close');
                            }}
                    ]
                });
                $(_dialogAlertDialogId).dialog('open');
                $.unblockUI();
            }
        }).done(function () {
            $('.select2').select2();
            _initDate({loadName: '.init-date'});
            _weight();
            $('#closeTypeId').on('change', function () {
                if ($(this).val() == 22) {
                    var _html = '';
                    _html += '<div class="form-group">';
                    _html += '<label for="Тайлбар" required="required" class="col-md-4 control-label text-right" defined="1">Тайлбар: </label>';
                    _html += '<div class="col-md-8">';
                    _html += '<textarea name="closeTypeDescription" cols="40" rows="5" id="closeTypeDescription" class="form-control"></textarea>';
                    _html += '</div>';
                    _html += '</div>';

                    $('#closeTypeDescription').html(_html);
                } else {
                    $('#closeTypeDescription').html('<input type="hidden" name="closeTypeDescription" id="closeTypeDescription">');
                }
            });
        });
    } else {
        _pageDeny();
    }
}
function _deleteNifsFileFolder(param) {
    if ((_permissionNifsFileFolder.our.delete && param.createdUserId == _uIdCurrent) || (_permissionNifsFileFolder.your.delete && param.createdUserId != _uIdCurrent)) {
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
                            url: _nifsFileFolderModRootPath + 'delete',
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
                                _initNifsFileFolder({page: 0, searchQuery: {}});
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
function _advensedSearchNifsFileFolder(param) {
    if (_permissionNifsFileFolder.isModule) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsFileFolderModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsFileFolderModId},
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
                                _initNifsFileFolder({modId: _nifsFileFolderModId, page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
                                $(_dialogAlertDialogId).empty().dialog('close');
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

            $('select[name="questionId"]').on('change', function () {

                if ($(this).val() == 38) {

                    $('#initFileFolderControlQuestionHtml').html(
                            '<div class="col-md-6">' +
                            '<div class="form-group" style="margin-bottom: 0;">' +
                            '<label for="Нас" required="required" class="control-label text-left" defined="">Нас</label>' +
                            '<div class="clearfix"></div>' +
                            '<input type="text" name="age1" value="" id="age1" placeholder="0" maxlength="3" class="form-control init-control-age" style="width: 50px; float:left; margin-right:20px; text-align:right;">' +
                            '<input type="text" name="age2" value="" id="age2" placeholder="100" maxlength="3" class="form-control init-control-age" style="width: 50px; float:left; text-align:right; margin-right:20px;">' +
                            '<div class="clearfix"></div>' +
                            '<span class="help-block"><i class="icon-help"></i> 5 &lt;= Нас &lt;= 60, 10&lt;= Нас, Нас &lt;= 80 эвсэл сонго .</span>' +
                            '</div>' +
                            '</div>');
                    _age();

                } else {
                    $('#initFileFolderControlQuestionHtml').html('<input type="hidden" name="age" id="age" value="0">');
                }

            });

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initNifsFileFolder({page: 0, searchQuery: $(_dialogAlertDialogId).find(_nifsFileFolderFormMainId + '-search').serialize()});
                    $(_dialogAlertDialogId).empty().dialog('close');
                }
            });




        });
    } else {
        _pageDeny();
    }
}
function _addFormNifsFileFolder(param) {
    if (_permissionNifsFileFolder.our.create) {
        if (!$(_nifsFileFolderDialogId).length) {
            $('<div id="' + _nifsFileFolderDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _nifsFileFolderModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsFileFolderModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsFileFolderDialogId).empty().html(data.html);

                $(_nifsFileFolderDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsFileFolderDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsFileFolderDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsFileFolderDialogId).find('form' + _nifsFileFolderFormMainId);
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsFileFolderModRootPath + 'insert',
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
                                            _initNifsFileFolder({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsFileFolderDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_nifsFileFolderDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-file-folder-in-out-date-diff-work-day'});

            var _from = $("#inDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _to.datepicker("option", "minDate", _getDate(this));
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-file-folder-in-out-date-diff-work-day'});
            });
            var _to = $("#outDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _from.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-file-folder-in-out-date-diff-work-day'});
            });

            var _fromOut = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _toOut.datepicker("option", "minDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-file-folder-protocol-in-out-date-diff-work-day'});
            });
            var _toOut = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _fromOut.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-file-folder-protocol-in-out-date-diff-work-day'});
            });

            $('#crimeResearchTypeId').on('change', function () {
                _checkExpertMode({crimeResearchType: $(this).val()});
            });

            $('#object').tokenfield();

            $('#object').on('tokenfield:createdtoken', function (e) {
                var existingTokens = $(this).tokenfield('getTokens');
                var _allObjectCount = 0;
                $.each(existingTokens, function (index, token) {
                    var _result = token.value.split('-');
                    var _objectNumber = $.trim(_result[1]);
                    if (_objectNumber == '') {
                        _objectNumber = 1;
                    } else {
                        var _objectNumber = parseInt(_objectNumber);
                    }
                    _allObjectCount = _allObjectCount + _objectNumber;
                    $('input[name="objectCount"]').val(_allObjectCount);
                    $('.object-count').text('Ирүүлсэн обьект (' + _allObjectCount + '):');
                });
            });
            $('#object').on('tokenfield:removedtoken', function (e) {
                var existingTokens = $(this).tokenfield('getTokens');
                var _allObjectCount = 0;
                $.each(existingTokens, function (index, token) {
                    var _result = token.value.split('-');
                    var _objectNumber = $.trim(_result[1]);
                    if (_objectNumber == '') {
                        _objectNumber = 1;
                    } else {
                        var _objectNumber = parseInt(_objectNumber);
                    }
                    _allObjectCount = _allObjectCount + _objectNumber;
                    $('input[name="objectCount"]').val(_allObjectCount);
                    if (_allObjectCount == 0) {
                        $('.object-count').text('Ирүүлсэн обьект:');
                    } else {
                        $('.object-count').text('Ирүүлсэн обьект (' + _allObjectCount + '):');
                    }

                });
            });

            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    var _thisVal = $(this).val();
                    if (_thisVal == '643' || _thisVal == '644' || _thisVal == '645' || _thisVal == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#initFileFolderControlExpertHtmlExtra').removeClass('hide');
                    $('#initFileFolderControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initFileFolderControlExpertHtmlExtra').removeClass('show');
                    $('#initFileFolderControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            $('select[name="questionId"]').on('change', function () {
                if ($(this).val() == 38) {
                    $('#initFileFolderControlQuestionHtml').html(
                            '<div class="form-group row">' +
                            '<label for="Нас" required="required" class="col-md-4 control-label text-right" defined="1">Нас: </label>' +
                            '<div class="col-md-8">' +
                            '<input type="text" name="age" id="age" maxlength="4" class="form-control init-control-age" style="width:100px;">' +
                            '</div>' +
                            '</div>');
                    _age();

                } else {
                    $('#initFileFolderControlQuestionHtml').html('<input type="hidden" name="age" id="age" value="0">');
                }
            });

            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsFileFolderModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#agentName").autocomplete({
                source: agentNameTags
            });

            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-file-folder-in-out-date-diff-work-day'});
            _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-file-folder-protocol-in-out-date-diff-work-day'});

        });
    } else {
        _pageDeny();
    }
}
function _editFormNifsFileFolder(param) {
    if ((_permissionNifsFileFolder.our.update && param.createdUserId == _uIdCurrent) || (_permissionNifsFileFolder.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_nifsFileFolderDialogId).length) {
            $('<div id="' + _nifsFileFolderDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsFileFolderModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsFileFolderModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsFileFolderDialogId).html(data.html);
                $(_nifsFileFolderDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    position: {
                        my: "center center",
                        at: "center center",
                        of: window
                    },
                    close: function () {
                        $(_nifsFileFolderDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsFileFolderDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsFileFolderDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsFileFolderModRootPath + 'update',
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
                                            _initNifsFileFolder({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsFileFolderDialogId).empty().dialog('close');
                                    });
                                }
                            }},
                        {text: data.btn_save_close, class: 'btn btn-success active legitRipple', click: function () {

                                var _form = $(_nifsFileFolderDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsFileFolderModRootPath + 'update',
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
                                            _closeNifsFileFolder({elem: this, id: _form.find('input[name="id"]').val(), createdUserId: _form.find('input[name="createdUserId"]').val()});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsFileFolderDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_nifsFileFolderDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.radio, .checkbox').uniform();
            $('.select2').select2();

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

            $('#crimeResearchTypeId').on('change', function () {
                _checkExpertMode({crimeResearchType: $(this).val()});
            });

            $('#object').tokenfield();

            $('#object').on('tokenfield:createdtoken', function (e) {
                var existingTokens = $(this).tokenfield('getTokens');
                var _allObjectCount = 0;
                $.each(existingTokens, function (index, token) {
                    var _result = token.value.split('-');
                    var _objectNumber = $.trim(_result[1]);
                    if (_objectNumber == '') {
                        _objectNumber = 1;
                    } else {
                        var _objectNumber = parseInt(_objectNumber);
                    }
                    _allObjectCount = _allObjectCount + _objectNumber;
                    $('input[name="objectCount"]').val(_allObjectCount);
                    $('.object-count').text('Ирүүлсэн обьект (' + _allObjectCount + '):');
                });
            });
            $('#object').on('tokenfield:removedtoken', function (e) {
                var existingTokens = $(this).tokenfield('getTokens');
                var _allObjectCount = 0;
                $.each(existingTokens, function (index, token) {
                    var _result = token.value.split('-');
                    var _objectNumber = $.trim(_result[1]);
                    if (_objectNumber == '') {
                        _objectNumber = 1;
                    } else {
                        var _objectNumber = parseInt(_objectNumber);
                    }
                    _allObjectCount = _allObjectCount + _objectNumber;
                    $('input[name="objectCount"]').val(_allObjectCount);
                    if (_allObjectCount == 0) {
                        $('.object-count').text('Ирүүлсэн обьект:');
                    } else {
                        $('.object-count').text('Ирүүлсэн обьект (' + _allObjectCount + '):');
                    }

                });
            });

            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    var _thisVal = $(this).val();
                    if (_thisVal == '643' || _thisVal == '644' || _thisVal == '645' || _thisVal == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#initFileFolderControlExpertHtmlExtra').removeClass('hide');
                    $('#initFileFolderControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initFileFolderControlExpertHtmlExtra').removeClass('show');
                    $('#initFileFolderControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            $('select[name="questionId"]').on('change', function () {
                if ($(this).val() == 38) {
                    $('#initFileFolderControlQuestionHtml').html(
                            '<div class="form-group row">' +
                            '<label for="Нас" required="required" class="col-md-4 control-label text-right" defined="1">Нас: </label>' +
                            '<div class="col-md-8">' +
                            '<input type="text" name="age" id="age" maxlength="4" class="form-control init-control-age" style="width:100px;">' +
                            '</div>' +
                            '</div>');
                } else {
                    $('#initFileFolderControlQuestionHtml').html('<input type="hidden" name="age" id="age" value="0">');
                }
            });
            _age();

            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsFileFolderModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#agentName").autocomplete({
                source: agentNameTags
            });

            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-file-folder-in-out-date-diff-work-day'});
            _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-file-folder-protocol-in-out-date-diff-work-day'});

        });
    } else {
        _pageDeny();
    }
}
function _reportNifsFileFolderWorkInformation(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsFileFolderModRootPath + 'getReportWorkInformationData',
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
function _reportNifsFileFolderWeight(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsFileFolderModRootPath + 'getReportWeightData',
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
function _reportNifsFileFolderPartner(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsFileFolderModRootPath + 'getReportPartnerData',
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