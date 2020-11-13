var _dgDocTransfer = '';
var _getDocTransferUrlModule = _getUrlModule();
var _permissionDocTransfer = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getDocTransferUrlModule == 'sdocTransfer') {
        _initDocTransfer({page: 0, searchQuery: $(_rootContainerId).find(_docTransferFormMainId + '-init').serialize()});
    }
});
function _initDocTransfer(param) {

    if (_permissionDocTransfer.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-doc-transfer"><table id="dgDocTransfer" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgDocTransfer = $('#dgDocTransfer').datagrid({
            url: _docTransferModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Миний албан бичиг',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgDocTransfer.datagrid('getSelected');
                        if (_row != null) {
                            _editFormDocTransfer({elem: this, id: _row.id, docId: _row.doc_id, createdUserId: _row.created_user_id});
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
                    text: 'Маягт хэвлэх (F11)',
                    iconCls: 'dg-icon-print-1',
                    handler: function () {
                        var _row = _dgDocTransfer.datagrid('getSelected');
                        if (_row != null) {
                            _printDocTransfer({elem: this, id: _row.id});
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
                    {field: 'num', title: '#',
                        styler: function (value, row, index) {
                            return row.row_status;

                        }},
                    {field: 'doc_date', title: 'Огноо', width: 80},
                    {field: 'doc_number', title: 'Дугаар'},
                    {field: 'department', title: 'Хаанаас, хэнээс ирсэн', width: 300},
                    {field: 'docTypeTitle', title: 'Төрөл', width: 150},
                    {field: 'description', title: 'Товч агуулга', width: 300},
                    {field: 'transfer_description', title: 'Хариу албан бичгийн төсөл', width: 200}
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
                $('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
            }, onDblClickRow: function () {
                var _row = _dgDocTransfer.datagrid('getSelected');
                _editFormDocTransfer({elem: this, id: _row.id, docId: _row.doc_id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _editFormDocTransfer(param) {

    /*Анх мэдээлэл оруулсан user update хийх эрхтэй*/
    if (!$(_docTransferDialogId).length) {
        $('<div id="' + _docTransferDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _docTransferModRootPath + 'edit',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _docTransferModId, id: param.id},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_docTransferDialogId).empty().html(data.html);
            $(_docTransferDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_docTransferDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_docTransferDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            $('textarea[name="transferDescription"]').val(CKEDITOR.instances.transferDescription.getData());
                            var _form = $(_docTransferDialogId).find('form');
                            $(_form).validate({errorPlacement: function () {
                                }});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _docTransferModRootPath + 'update',
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
                                        _initDocTransfer({page: 0, searchQuery:{}});
                                        $.unblockUI();
                                        $(_docTransferDialogId).empty().dialog('close');
                                    }
                                });
                            }

                        }}
                ]
            });
            $(_docTransferDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        CKEDITOR.replace('transferDescription');

    });

}
function _deleteDocTransfer(param) {
    var _this = $(param.elem);
    var _root = _this.parent().parent();
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
                        url: _docTransferModRootPath + 'delete',
                        dataType: "json",
                        data: {docTransferId: _root.find('input[name="docTransferId[]"]').val()},
                        beforeSend: function () {
                            $.blockUI({
                                message: _jqueryBlockUiMessage,
                                overlayCSS: _jqueryBlockUiOverlayCSS,
                                css: _jqueryBlockUiMessageCSS
                            });
                        },
                        success: function (data) {
                            _PNotify({status: data.status, message: data.message});
                            _addDeleteListDocTransfer({docDetialId: _root.find('input[name="docDetialId[]"]').val()});
                        }
                    });
                    $(_dialogAlertDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
function _printDocTransfer(param) {
    $.ajax({
        type: 'post',
        url: _docTransferModRootPath + 'printFile',
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
            if (data) {
                mywindow = window.open('', 'PRINT', 'height=900,width=1000');
                mywindow.document.write('<html><head><title>' + data.title + '</title></head><body style="font-family:arial;">' + data.html + '</body></html>');
                mywindow.document.close(); // necessary for IE >= 10
//                mywindow.focus(); // necessary for IE >= 10*/
//                mywindow.print();
//                mywindow.close();
                $.unblockUI();
            }

        }
    });
}
function _showDocTransfer(param) {
    var _this = $(param.elem);
    var _root = _this.parent().parent();


    if (!$(_docTransferDialogId).length) {
        $('<div id="' + _docTransferDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: _docTransferModRootPath + 'show',
        dataType: "json",
        data: {selectedId: _root.find('input[name="docTransferId[]"]').val()},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_docTransferDialogId).empty().html(data.html);
            $(_docTransferDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_docTransferDialogId).empty().dialog('close');
                }
            });
            $(_docTransferDialogId).dialog('open');
            $.unblockUI();
        }
    });
}
function _addDocTransfer(param) {

    if (!$(_docTransferDialogId).length) {
        $('<div id="' + _docTransferDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _docTransferModRootPath + 'add',
        type: 'POST',
        data: {docDetialId: param.docDetialId},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_docTransferDialogId).empty().html(data.html);
            $(_docTransferDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_docTransferDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_docTransferDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            var _form = $(_docTransferDialogId).find('form');
                            $(_form).validate({errorPlacement: function () {
                                }});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _docTransferModRootPath + 'insert',
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
                                        $(_docTransferDialogId).empty().dialog('close');
                                        _PNotify({status: data.status, message: data.message});
                                        _addDeleteListDocTransfer({docDetialId: param.docDetialId, root: _dialogAlertDialogId});
                                        $.unblockUI();
                                    }
                                });
                            }

                        }}
                ]
            });
            $(_docTransferDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {

        $('#fromDepartmentId').on('change', function () {
            var _this = $(this);
            if (_this.val() == 60) {
                $.ajax({
                    url: _partnerModRootPath + 'controlPartnerDropdown',
                    type: 'GET',
                    dataType: 'json',
                    data: {modId: _partnerModId, selectedId: 0, name: 'fromPartnerId'},
                    beforeSend: function () {
                        $.blockUI({
                            message: _jqueryBlockUiMessage,
                            overlayCSS: _jqueryBlockUiOverlayCSS,
                            css: _jqueryBlockUiMessageCSS
                        });
                    },
                    success: function (data) {
                        $('#init-control-from-partner-people-doc-in-html').html(data + '<input type="hidden" name="fromPeopleId" value="0">');
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
            } else {
                $.ajax({
                    url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
                    type: 'POST',
                    dataType: 'json',
                    data: {name: 'fromPeopleId', departmentId: _this.val(), selectedId: 0},
                    beforeSend: function () {
                        $.blockUI({
                            message: _jqueryBlockUiMessage,
                            overlayCSS: _jqueryBlockUiOverlayCSS,
                            css: _jqueryBlockUiMessageCSS
                        });
                    },
                    success: function (data) {
                        $('#init-control-from-partner-people-doc-in-html').html(data + '<input type="hidden" name="fromPartnerId" value="0">');
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
            }
        });

        $('#toDepartmentId').on('change', function () {
            var _this = $(this);
            if (_this.val() == 60) {
                $.ajax({
                    url: _partnerModRootPath + 'controlPartnerDropdown',
                    type: 'GET',
                    dataType: 'json',
                    data: {modId: _partnerModId, selectedId: 0, name: 'toPartnerId'},
                    beforeSend: function () {
                        $.blockUI({
                            message: _jqueryBlockUiMessage,
                            overlayCSS: _jqueryBlockUiOverlayCSS,
                            css: _jqueryBlockUiMessageCSS
                        });
                    },
                    success: function (data) {
                        $('#init-control-to-partner-people-doc-in-html').html(data + '<input type="hidden" name="toPeopleId" value="0">');
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
            } else {
                $.ajax({
                    url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
                    type: 'POST',
                    dataType: 'json',
                    data: {name: 'toPeopleId', departmentId: _this.val(), selectedId: 0},
                    beforeSend: function () {
                        $.blockUI({
                            message: _jqueryBlockUiMessage,
                            overlayCSS: _jqueryBlockUiOverlayCSS,
                            css: _jqueryBlockUiMessageCSS
                        });
                    },
                    success: function (data) {
                        $('#init-control-to-partner-people-doc-in-html').html(data + '<input type="hidden" name="toPartnerId" value="0">');
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
            }
        });

        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        $('input[name="docNumber"]').focus();
        _initDate({loadName: '.init-date'});
    });
}
function _addDeleteListDocTransfer(param) {

    $.ajax({
        url: _docTransferModRootPath + 'addDeleteList',
        type: 'POST',
        data: {docDetialId: param.docDetialId, disabled: 'false'},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('body').find('.init-doc-transfer').html(data);
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    });
}