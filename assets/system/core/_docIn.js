var _dgDocIn = '';
var _getDocInUrlModule = _getUrlModule();
var _permissionDocIn = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getDocInUrlModule == 'sdocIn') {
        _initDocIn({page: 0, searchQuery: $(_rootContainerId).find(_docInFormMainId + '-init').serialize()});
    }
});
$(document).bind('keydown', 'f2', function () {
    if (_getDocInUrlModule == 'sdocIn') {
        _addFormDocIn({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getDocInUrlModule == 'sdocIn') {
        var _row = _dgDocIn.datagrid('getSelected');
        if (_row != null) {
            _editFormDocIn({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getDocInUrlModule == 'sdocIn') {
        var _row = _dgDocIn.datagrid('getSelected');
        if (_row != null) {
            _initDocClose({elem: this, docDetialId: _row.id, docCloseId: _row.doc_close_id, type: 'doc-in'});
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
    if (_getDocInUrlModule == 'sdocIn') {
        var _row = _dgDocIn.datagrid('getSelected');
        if (_row != null) {
            _deleteDocIn({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    _advensedSearchDocIn({elem: this});
});
function _initDocIn(param) {
    if (_permissionDocIn.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-doc-in"><table id="dgDocIn" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgDocIn = $('#dgDocIn').datagrid({
            url: _docInModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Ирсэн бичгийн бүртгэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormDocIn({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgDocIn.datagrid('getSelected');
                        if (_row != null) {
                            _editFormDocIn({elem: this, id: _row.id, docId: _row.doc_id, createdUserId: _row.created_user_id, fromDepartmentId: _row.from_department_id});
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
                    text: 'Хариу бүртгэл (F4)',
                    iconCls: 'dg-icon-lock-1',
                    handler: function () {

                        var _row = _dgDocIn.datagrid('getSelected');
                        if (_row != null) {
                            _initDocClose({elem: this, docDetialId: _row.id, docCloseId: _row.doc_close_id, type: 'doc-in'});
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
                        var _row = _dgDocIn.datagrid('getSelected');
                        if (_row != null) {
                            _deleteDocIn({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _advensedSearchDocIn({elem: this});
                    }
                }, '-', {
                    text: 'Маягт хэвлэх (F11)',
                    iconCls: 'dg-icon-print-1',
                    handler: function () {
                        var _row = _dgDocIn.datagrid('getSelected');
                        if (_row != null) {
                            _printBlankDocIn({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                    {field: 'from_department', title: 'Хаанаас, хэнээс ирсэн', width: 300},
                    {field: 'description', title: 'Товч агуулга', width: 300},
                    {field: 'docTypeTitle', title: 'Төрөл', width: 150},
                    {field: 'transfer', title: 'Хэнд байгаа', width: 200},
                    {field: 'close', title: 'Хаасан байдал', width: 150},
                    {field: 'user', title: 'Бүртгэл хийсэн', width: 120}
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
                var _row = _dgDocIn.datagrid('getSelected');
                _editFormDocIn({elem: this, id: _row.id, docId: _row.doc_id, createdUserId: _row.created_user_id, fromDepartmentId: _row.from_department_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _deleteDocIn(param) {
    if ((_permissionDocIn.our.delete && param.userId == _uIdCurrent) || (_permissionDocIn.your.delete && param.userId != _uIdCurrent)) {
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
                            url: _docInModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initDocIn({page: 0, searchQuery: $(_rootContainerId).find(_docInFormMainId + '-init').serialize()});
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
function _advensedSearchDocIn(param) {

    if (_permissionDocIn.isModule) {
        if (!$(_docInDialogId).length) {
            $('<div id="' + _docInDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docInModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _docInModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_docInDialogId).html(data.html);
                $(_docInDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docInDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docInDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initDocIn({page: 0, searchQuery: $(_docInDialogId).find(_docInFormMainId + '-search').serialize()});
                                $(_docInDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_docInDialogId).dialog('open');
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

            _createNumber();

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initDocIn({page: 0, searchQuery: $(_docInDialogId).find(_docInFormMainId + '-search').serialize()});
                    $(_docInDialogId).empty().dialog('close');
                }
            });

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
                            $('div[data-partner-people-doc-out-bind="init"]').html(data + '<input type="hidden" name="fromPeopleId" value="0">');
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
                            $('div[data-partner-people-doc-out-bind="init"]').html(data + '<input type="hidden" name="fromPartnerId" value="0">');
                        }
                    }).done(function () {
                        $('.select2').select2();
                        $.unblockUI();
                    });
                }
            });

        });
    } else {
        _pageDeny();
    }

}
function _addFormDocIn(param) {
    if (_permissionDocIn.our.create) {
        if (!$(_docInDialogId).length) {
            $('<div id="' + _docInDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docInModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _docInModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_docInDialogId).empty().html(data.html);
                $(_docInDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docInDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docInDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_docInDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _docInModRootPath + 'insert',
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
                                            $(_docInDialogId).empty().dialog('close');
                                            _PNotify({status: data.status, message: data.message});
                                            _initDocIn({page: 0, searchQuery: $(_rootContainerId).find(_docInFormMainId + '-init').serialize()});
                                            $.unblockUI();
                                        }
                                    });
                                }

                            }}
                    ]
                });
                $(_docInDialogId).dialog('open');
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
    } else {
        _pageDeny();
    }
}
function _editFormDocIn(param) {
    if ((_permissionDocIn.our.update && param.createdUserId == _uIdCurrent) || (_permissionDocIn.your.update && param.createdUserId != _uIdCurrent)) {
        /*Анх мэдээлэл оруулсан user update хийх эрхтэй*/
        if (!$(_docInDialogId).length) {
            $('<div id="' + _docInDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docInModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _docInModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_docInDialogId).empty().html(data.html);
                $(_docInDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docInDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docInDialogId).empty().dialog('close');
                            }},
                        {text: 'Маягт хэвлэх', class: 'btn btn-success active', click: function () {
                                _printBlankDocIn({id: param.id});
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_docInDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _docInModRootPath + 'update',
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
                                            _initDocIn({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                            $(_docInDialogId).empty().dialog('close');
                                        }
                                    });
                                }

                            }}
                    ]
                });
                $(_docInDialogId).dialog('open');
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
    } else if ((!_permissionDocIn.our.update && param.createdUserId == _uIdCurrent) || (!_permissionDocIn.your.update && param.createdUserId != _uIdCurrent)) {
        /*Оруулсан мэдээллийг өөр газар хэлтэс дээр нээж үзэх үед ажиллана*/
        if (!$(_docInDialogId).length) {
            $('<div id="' + _docInDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docInModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _docInModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_docInDialogId).empty().html(data.html);
                $(_docInDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docInDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docInDialogId).empty().dialog('close');
                            }},
                        {text: 'Маягт хэвлэх', class: 'btn btn-success active', click: function () {
                                _printBlankDocIn({id: param.id});
                            }}
                    ]
                });
                $(_docInDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            _initDate({loadName: '.init-date'});
            $('input[name="docNumber"]').focus();

            _initDocInTransfer({docId: param.docId, disabled: 1});

        });
    }

}
function _closeDocIn(param) {
    if ((_permissionDocIn.our.update && param.createdUserId == _uIdCurrent) || (_permissionDocIn.your.update && param.createdUserId != _uIdCurrent)) {
        /*Анх мэдээлэл оруулсан user update хийх эрхтэй*/
        if (!$(_docInDialogId).length) {
            $('<div id="' + _docInDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docInModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _docInModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_docInDialogId).empty().html(data.html);
                $(_docInDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docInDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docInDialogId).empty().dialog('close');
                            }},
                        {text: 'Маягт хэвлэх', class: 'btn btn-success active', click: function () {
                                _printBlankDocIn({id: param.id});
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_docInDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _docInModRootPath + 'update',
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
                                            _initDocIn({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                            $(_docInDialogId).empty().dialog('close');
                                        }
                                    });
                                }

                            }}
                    ]
                });
                $(_docInDialogId).dialog('open');
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

            _initDocInTransfer({docId: param.docId, disabled: 0});

        });
    } else if ((!_permissionDocIn.our.update && param.createdUserId == _uIdCurrent) || (!_permissionDocIn.your.update && param.createdUserId != _uIdCurrent)) {
        /*Оруулсан мэдээллийг өөр газар хэлтэс дээр нээж үзэх үед ажиллана*/
        if (!$(_docInDialogId).length) {
            $('<div id="' + _docInDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docInModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _docInModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_docInDialogId).empty().html(data.html);
                $(_docInDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docInDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docInDialogId).empty().dialog('close');
                            }},
                        {text: 'Маягт хэвлэх', class: 'btn btn-success active', click: function () {
                                _printBlankDocIn({id: param.id});
                            }}
                    ]
                });
                $(_docInDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            _initDate({loadName: '.init-date'});
            $('input[name="docNumber"]').focus();

            _initDocInTransfer({docId: param.docId, disabled: 1});

        });
    }

}
function _setIsReply(param) {
    var _this = $(param.this);

    if (_this.prop("checked")) {
        $('input[name="isReply"]').val(1);
        $('input[name="replyDate"]').prop("disabled", false);
    } else {
        $('input[name="isReply"]').val(0);
        $('input[name="replyDate"]').prop("disabled", true);
    }

}
function _printBlankDocIn(param) {
    if ((_permissionDocIn.our.read && param.createdUserId == _uIdCurrent) || (_permissionDocIn.your.read && param.createdUserId != _uIdCurrent)) {
        $.ajax({
            type: 'post',
            url: _docInModRootPath + 'printBlank',
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
                mywindow = window.open('', 'PRINT', 'height=900,width=1000');
                mywindow.document.write('<html><head><title>' + data.title + '</title></head><body style="font-family:arial;">' + data.html + '</body></html>');
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/
                mywindow.print();
                mywindow.close();
                $.unblockUI();
            }
        });
    } else {
        _pageDeny();
    }
}
