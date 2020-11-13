var _dgDocOut = '';
var _getDocOutUrlModule = _getUrlModule();
var _permissionDocOut = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getDocOutUrlModule == 'sdocOut') {
        _initDocOut({page: 0, searchQuery: $(_rootContainerId).find(_docOutFormMainId + '-init').serialize()});
    }
});
$(document).bind('keydown', 'f2', function () {
    if (_getDocOutUrlModule == 'sdocOut') {
        _addFormDocOut({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getDocOutUrlModule == 'sdocOut') {
        var _row = _dgDocOut.datagrid('getSelected');
        if (_row != null) {
            _editFormDocOut({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getDocOutUrlModule == 'sdocOut') {
        var _row = _dgDocOut.datagrid('getSelected');
        if (_row != null) {
            _deleteDocOut({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getDocOutUrlModule == 'sdocOut') {
        var _row = _dgDocOut.datagrid('getSelected');
        if (_row != null) {
            _initDocClose({elem: this, docDetialId: _row.id, docCloseId: _row.doc_close_id, type: 'doc-out'});
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
    _advensedSearchDocOut({elem: this});
});

function _initDocOut(param) {
    if (_permissionDocOut.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-doc-out"><table id="dgDocOut" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgDocOut = $('#dgDocOut').datagrid({
            url: _docOutModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Явсан бичгийн бүртгэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormDocOut({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgDocOut.datagrid('getSelected');
                        if (_row != null) {
                            _editFormDocOut({elem: this, id: _row.id, docId: _row.doc_id, createdUserId: _row.created_user_id});
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

                        var _row = _dgDocOut.datagrid('getSelected');
                        if (_row != null) {
                            _initDocClose({elem: this, docDetialId: _row.id, docCloseId: _row.doc_close_id, type: 'doc-out'});
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
                        var _row = _dgDocOut.datagrid('getSelected');
                        if (_row != null) {
                            _deleteDocOut({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _advensedSearchDocOut({elem: this});
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
                    {field: 'to_department', title: 'Хаашаа, хэнд илгээсэн', width: 300},
                    {field: 'description', title: 'Товч агуулга', width: 300},
                    {field: 'page_number', title: 'Хуудас', align: 'center'},
                    {field: 'docTypeTitle', title: 'Төрөл', width: 120},
                    {field: 'close', title: 'Хариу', width: 150},
                    {field: 'user', title: 'Бүртгэл хийсэн', width: 120}
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
                $('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');

            }, onDblClickRow: function () {
                var _row = _dgDocOut.datagrid('getSelected');
                _editFormDocOut({elem: this, id: _row.id, docId: _row.doc_id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _deleteDocOut(param) {
    if ((_permissionDocOut.our.delete && param.userId == _uIdCurrent) || (_permissionDocOut.your.delete && param.userId != _uIdCurrent)) {
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
                            url: _docOutModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initDocOut({page: 0, searchQuery: $(_rootContainerId).find(_docOutFormMainId + '-init').serialize()});
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
function _addFormDocOut(param) {
    if (_permissionDocOut.our.create) {
        if (!$(_docOutDialogId).length) {
            $('<div id="' + _docOutDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docOutModRootPath + 'add',
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
                $(_docOutDialogId).empty().html(data.html);
                $(_docOutDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docOutDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docOutDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_docOutDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _docOutModRootPath + 'insert',
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
                                            $(_docOutDialogId).empty().dialog('close');
                                            _PNotify({status: data.status, message: data.message});
                                            _initDocOut({page: 0, searchQuery: $(_rootContainerId).find(_docOutFormMainId + '-init').serialize()});
                                            $.unblockUI();
                                        }
                                    });
                                }

                            }}
                    ]
                });
                $(_docOutDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            $('input[name="docNumber"]').focus();
            _initDate({loadName: '.init-date'});
        });
    } else {
        _pageDeny();
    }
}
function _editFormDocOut(param) {

    if ((_permissionDocOut.our.update && param.createdUserId == _uIdCurrent) || (_permissionDocOut.your.update && param.createdUserId != _uIdCurrent)) {
        /*Анх мэдээлэл оруулсан user update хийх эрхтэй*/
        if (!$(_docOutDialogId).length) {
            $('<div id="' + _docOutDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docOutModRootPath + 'edit',
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
                $(_docOutDialogId).empty().html(data.html);
                $(_docOutDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docOutDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docOutDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_docOutDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _docOutModRootPath + 'update',
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
                                            _initDocOut({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                            $(_docOutDialogId).empty().dialog('close');
                                        }
                                    });
                                }

                            }}
                    ]
                });
                $(_docOutDialogId).dialog('open');
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

        });
    } else if ((!_permissionDocOut.our.update && param.createdUserId == _uIdCurrent) || (!_permissionDocOut.your.update && param.createdUserId != _uIdCurrent)) {
        /*Оруулсан мэдээллийг өөр газар хэлтэс дээр нээж үзэх үед ажиллана*/
        if (!$(_docOutDialogId).length) {
            $('<div id="' + _docOutDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docOutModRootPath + 'edit',
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
                $(_docOutDialogId).empty().html(data.html);
                $(_docOutDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docOutDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docOutDialogId).empty().dialog('close');
                            }}
                    ]
                });
                $(_docOutDialogId).dialog('open');
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

            _initDocFile({docId: param.docId, disabled: true});
            _initDocClose({docId: param.docId, disabled: 1});
            _initDocOutTransfer({docId: param.docId, disabled: 1});

        });
    }

}
function _addToDocOutCommonControl(param) {
    var _this = $(param.elem);
    var _num = _this.parent().parent().attr('data-row');
    var _row = $('div[data-row="' + _num + '"]');
    var _department = _row.find('select[name="toDepartmentId[]"]');
    var _partner = _row.find('select[name="toPartnerId[]"]');
    var _people = _row.find('select[name="toPeopleId[]"]');
    var _toNum = parseInt($('.doc-out-common-control-row').last().attr('data-row')) + 1;

    if (_department.val() > 0 && _department.val() == 51 && _partner.val() > 0) {
        _initToDocOutCommonControl({num: _toNum});

    } else if (_department.val() > 0 && _department.val() != 51 && _people.val() > 0) {
        _initToDocOutCommonControl({num: _toNum});

    } else {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectDepartmentMessage);
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
            }
        });
        $(_dialogAlertDialogId).dialog('open');
    }

}
function _initToDocOutCommonControl(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleDepartmentModRootPath + 'controlHrPeopleDepartmentDropdown',
        dataType: "json",
        data: {name: 'toDepartmentId[]', 'onlyMyDepartment': false, onchange: '_toPartnerPeopleDocOutControl({elem:this});'},
        success: function (data) {
            _html = '<div class="row doc-out-common-control-row mt-2" data-row="' + param.num + '">';
            _html += '<div class="col-md-5">';
            _html += data;
            _html += '</div>';
            _html += '<div class="col-md-5" data-partner-people-doc-out-bind="' + param.num + '">';
            _html += '<input type="hidden" name="toPartnerId[]"> ';
            _html += '<input type="hidden" name="toPeopleId[]">';
            _html += '<select class="select2" disabled="disabled"><option> - Сонгох -</option></select>';
            _html += '</div>';
            _html += '<div class="col-md-2">';
            _html += '<button name="button" type="button" id="button" value="true" class="btn btn-danger" onclick="_deleteToDocOutCommonControl({elem:this});"><i class="icon-x"></i></button>';
            _html += '</div>';
            _html += '</div>';
            $('.init-doc-out-common-control').append(_html);
        }
    }).done(function () {
        $('.select2').select2();
    });


}
function _searchToPartnerPeopleDocOutControl(param) {
    var _this = $(param.elem);

    if (_this.val() == 60) {
        $.ajax({
            type: 'post',
            url: _partnerModRootPath + 'controlPartnerDropdown',
            dataType: "json",
            data: {name: 'toPartnerId'},
            success: function (data) {
                $('div[data-partner-people-doc-out-bind="init"]').html(data + '<input type="hidden" name="toPeopleId">');
            }
        }).done(function () {
            $('.select2').select2();
        });
    } else {
        $.ajax({
            type: 'post',
            url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
            dataType: "json",
            data: {name: 'toPeopleId', departmentId: _this.val()},
            success: function (data) {
                $('div[data-partner-people-doc-out-bind="init"]').html(data + '<input type="hidden" name="toPartnerId">');
            }
        }).done(function () {
            $('.select2').select2();
        });
    }

}
function _toPartnerPeopleDocOutControl(param) {
    var _this = $(param.elem);
    var _num = _this.parent().parent().attr('data-row');
    if (_this.val() == 60) {
        $.ajax({
            type: 'post',
            url: _partnerModRootPath + 'controlPartnerDropdown',
            dataType: "json",
            data: {name: 'toPartnerId[]'},
            success: function (data) {
                $('div[data-partner-people-doc-out-bind="' + _num + '"]').html(data + '<input type="hidden" name="toPeopleId[]">');
            }
        }).done(function () {
            $('.select2').select2();
        });
    } else {
        $.ajax({
            type: 'post',
            url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
            dataType: "json",
            data: {name: 'toPeopleId[]', departmentId: _this.val()},
            success: function (data) {
                $('div[data-partner-people-doc-out-bind="' + _num + '"]').html(data + '<input type="hidden" name="toPartnerId[]">');
            }
        }).done(function () {
            $('.select2').select2();
        });
    }

}
function _deleteToDocOutCommonControl(param) {

    var _this = $(param.elem);
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
                    _this.parent().parent().remove();
                    $(_dialogAlertDialogId).dialog('close').empty();
                }}

        ]
    });
    $(_dialogAlertDialogId).dialog('open');
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
function _advensedSearchDocOut(param) {

    if (_permissionDocOut.isModule) {
        if (!$(_docOutDialogId).length) {
            $('<div id="' + _docOutDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _docOutModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _docOutModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_docOutDialogId).html(data.html);
                $(_docOutDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_docOutDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_docOutDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initDocOut({page: 0, searchQuery: $(_docOutDialogId).find(_docOutFormMainId + '-search').serialize()});
                                $(_docOutDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_docOutDialogId).dialog('open');
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
                    _initDocOut({page: 0, searchQuery: $(_docOutDialogId).find(_docOutFormMainId + '-search').serialize()});
                    $(_docOutDialogId).empty().dialog('close');
                }
            });

        });
    } else {
        _pageDeny();
    }

}