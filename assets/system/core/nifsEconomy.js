var _dgNifsEconomy = '';
var _getNifsEconomyUrlModule = _getUrlModule();
var _permissionNifsEconomy = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getNifsEconomyUrlModule == 'snifsEconomy') {
        _initNifsEconomy({searchQuery:'selectedId=' + (getUrlParameter('selectedId') === undefined ? '' : getUrlParameter('selectedId')) + '&inDate=' + (getUrlParameter('inDate') === undefined ? '' : getUrlParameter('inDate')) + '&outDate=' + (getUrlParameter('outDate') === undefined ? '' : getUrlParameter('outDate')) + '&departmentId=' + (getUrlParameter('departmentId') === undefined ? '' : getUrlParameter('departmentId')) + '&keyword=' + (getUrlParameter('keyword') === undefined ? '' : getUrlParameter('keyword'))});
    }
});
$(document).bind('keydown', 'f2', function () {
    if (_getNifsEconomyUrlModule == 'snifsEconomy') {
        _addFormNifsEconomy({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsEconomyUrlModule == 'snifsEconomy') {
        var _row = _dgNifsEconomy.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsEconomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsEconomyUrlModule == 'snifsEconomy') {
        var _row = _dgNifsEconomy.datagrid('getSelected');
        if (_row != null) {
            _closeNifsEconomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsEconomyUrlModule == 'snifsEconomy') {
        var _row = _dgNifsEconomy.datagrid('getSelected');
        if (_row != null) {
            _deleteNifsEconomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsEconomyUrlModule == 'snifsEconomy') {
        _exportNifsEconomy({elem: this});
    }

});
$(document).bind('keydown', 'f10', function () {
    if (_getNifsEconomyUrlModule == 'snifsEconomy') {
        _advensedSearchNifsEconomy({elem: this});
    }
});

function _exportNifsEconomy(param) {
    if (_permissionNifsEconomy.custom.export) {

        var _preparingFileModal = $("#file-download-preparing-file-modal");

        _preparingFileModal.dialog({modal: true});

        $.fileDownload('/' + _nifsEconomyModRootPath + 'export', {
            httpMethod: 'GET',
            data: $(_rootContainerId).find(_nifsEconomyFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
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
function _initNifsEconomy(param) {
    if (_permissionNifsEconomy.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-economy"><table id="dgNifsEconomy" style="width:100%;"></table></div></div></div>');

        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsEconomy = $('#dgNifsEconomy').datagrid({
            url: _nifsEconomyModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Эдийн засгийн шинжилгээ',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormNifsEconomy({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgNifsEconomy.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsEconomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgNifsEconomy.datagrid('getSelected');
                        if (_row != null) {
                            _closeNifsEconomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgNifsEconomy.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsEconomy({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _exportNifsEconomy({elem: this});
                    }
                }, '-', {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchNifsEconomy({elem: this});
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
                    {field: 'partner_agent_date', title: 'Томилсон байгууллага, огноо', width: 150},
                    {field: 'protocol_value', title: 'Хэргийн утга', width: 200},
                    {field: 'object', title: 'Объект', width: 150},
                    {field: 'question', title: 'Асуулт', width: 100},
                    {field: 'expert', title: 'Шинжээч', width: 150,
                        styler: function (value, row, index) {
                            return row.expert_status;

                        }},
                    {field: 'weight', title: 'Ач', align: 'center', width: 20},
                    {field: 'report', title: 'Дүгнэлт', width: 150},
                    {field: 'description', title: 'Тайлбар', align: 'center'}
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
                var _row = _dgNifsEconomy.datagrid('getSelected');
                _editFormNifsEconomy({elem: this, id: _row.id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _closeNifsEconomy(param) {
    if (_permissionNifsEconomy.custom.close) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: _nifsEconomyModRootPath + 'closeFrom',
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
                                    url: _nifsEconomyModRootPath + 'close',
                                    dataType: "json",
                                    data: _form.serialize(),
                                    success: function (data) {
                                        _PNotify({status: data.status, message: data.message});
                                        _initNifsEconomy({page: 0, searchQuery: {}});
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
        });
    } else {
        _pageDeny();
    }
}
function _deleteNifsEconomy(param) {
    if ((_permissionNifsEconomy.our.delete && param.createdUserId == _uIdCurrent) || (_permissionNifsEconomy.your.delete && param.createdUserId != _uIdCurrent)) {
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
                            url: _nifsEconomyModRootPath + 'delete',
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
                                _initNifsEconomy({page: 0, searchQuery: {}});
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
function _advensedSearchNifsEconomy(param) {
    if (_permissionNifsEconomy.isModule) {
        var _dialogId = 'nifsEconomyAdvencedSearchDialog';
        if (!$('#' + _dialogId).length) {
            $('<div id="' + _dialogId + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsEconomyModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsEconomyModId},
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
                    height: "auto",
                    modal: true,
                    close: function () {
                        $('#' + _dialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $('#' + _dialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                _initNifsEconomy({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                                $('#' + _dialogId).empty().dialog('close');
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

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initNifsEconomy({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                    $('#' + _dialogId).empty().dialog('close');
                }
            });

        });
    } else {
        _pageDeny();
    }
}
function _addFormNifsEconomy(param) {
    if (_permissionNifsEconomy.our.create) {
        if (!$(_nifsEconomyDialogId).length) {
            $('<div id="' + _nifsEconomyDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _nifsEconomyModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsEconomyModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsEconomyDialogId).empty().html(data.html);

                $(_nifsEconomyDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsEconomyDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsEconomyDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsEconomyDialogId).find('form' + _nifsEconomyFormMainId);
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsEconomyModRootPath + 'insert',
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
                                            _initNifsEconomy({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsEconomyDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_nifsEconomyDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2({});
            $('.radio, .checkbox').uniform({radioClass: 'choice'});

            var _from = $("#inDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _to.datepicker("option", "minDate", _getDate(this));
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-economy-in-out-date-diff-work-day'});
            });
            var _to = $("#outDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _from.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-economy-in-out-date-diff-work-day'});
            });

            var _fromOut = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _toOut.datepicker("option", "minDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-economy-protocol-in-out-date-diff-work-day'});
            });
            var _toOut = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _fromOut.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-economy-protocol-in-out-date-diff-work-day'});
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
                });
                $('input[name="objectCount"]').val(_allObjectCount);
                $('.object-count').text('Ирүүлсэн обьект (' + _allObjectCount + '):');
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
                });
                $('input[name="objectCount"]').val(_allObjectCount);
                if (_allObjectCount == 0) {
                    $('.object-count').text('Ирүүлсэн обьект:');
                } else {
                    $('.object-count').text('Ирүүлсэн обьект (' + _allObjectCount + '):');
                }
            });

            $('#questionId').on('change', function () {
                if ($(this).val() == 31) {
                    $('#nifs-economy-additional-question').html('<div class="form-group row"><label for="Нэмэлт асуулт" required="required" class="col-md-4 control-label text-right" defined="1">Нэмэлт асуулт: </label><div class="col-md-8"><textarea name="question" cols="40" rows="2" id="question" class="form-control"></textarea></div></div><div class="clearfix"></div>');
                } else {
                    $('#nifs-economy-additional-question').html('<input type="hidden" name="question" value="">');
                }
            });
            _createNumber();

            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    var _thisVal = $(this).val();
                    if (_thisVal == '643' || _thisVal == '644' || _thisVal == '645' || _thisVal == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#initEconomyControlExpertHtmlExtra').removeClass('hide');
                    $('#initEconomyControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initEconomyControlExpertHtmlExtra').removeClass('show');
                    $('#initEconomyControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            var _crimeValueTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/crimeValueLists",
                data: {modId: _nifsEconomyModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    _crimeValueTags = data;
                }
            });
            $("#protocolValue").autocomplete({
                source: _crimeValueTags
            });
            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsEconomyModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#agentName").autocomplete({
                source: agentNameTags
            });

            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-economy-in-out-date-diff-work-day'});
            _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-economy-protocol-in-out-date-diff-work-day'});

        });
    } else {
        _pageDeny();
    }

}
function _editFormNifsEconomy(param) {

    if ((_permissionNifsEconomy.our.update && param.createdUserId == _uIdCurrent) || (_permissionNifsEconomy.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_nifsEconomyDialogId).length) {
            $('<div id="' + _nifsEconomyDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsEconomyModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsEconomyModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsEconomyDialogId).html(data.html);

                $(_nifsEconomyDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsEconomyDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsEconomyDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_nifsEconomyDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsEconomyModRootPath + 'update',
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
                                            _initNifsEconomy({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsEconomyDialogId).empty().dialog('close');
                                    });
                                }
                            }},
                        {text: data.btn_save_close, class: 'btn btn-success active legitRipple', click: function () {

                                var _form = $(_nifsEconomyDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsEconomyModRootPath + 'update',
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
                                            _closeNifsEconomy({elem: this, id: _form.find('input[name="id"]').val()});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_nifsEconomyDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_nifsEconomyDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.radio, .checkbox').uniform({radioClass: 'choice'});
            $('.select2').select2();

            var _from = $("#inDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _to.datepicker("option", "minDate", _getDate(this));
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-economy-in-out-date-diff-work-day'});
            });
            var _to = $("#outDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _from.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-economy-in-out-date-diff-work-day'});
            });

            var _fromOut = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _toOut.datepicker("option", "minDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-economy-protocol-in-out-date-diff-work-day'});
            });
            var _toOut = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _fromOut.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-economy-protocol-in-out-date-diff-work-day'});
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
                });
                $('input[name="objectCount"]').val(_allObjectCount);
                $('.object-count').text('Ирүүлсэн обьект (' + _allObjectCount + '):');
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
                });
                $('input[name="objectCount"]').val(_allObjectCount);
                if (_allObjectCount == 0) {
                    $('.object-count').text('Ирүүлсэн обьект:');
                } else {
                    $('.object-count').text('Ирүүлсэн обьект (' + _allObjectCount + '):');
                }
            });
            $('#questionId').on('change', function () {
                if ($(this).val() == 31) {
                    $('#nifs-economy-additional-question').html('<div class="form-group row"><label for="Нэмэлт асуулт" required="required" class="col-md-4 control-label text-right" defined="1">Нэмэлт асуулт: </label><div class="col-md-8"><textarea name="question" cols="40" rows="2" id="question" class="form-control"></textarea></div></div><div class="clearfix"></div>');
                } else {
                    $('#nifs-economy-additional-question').html('<input type="hidden" name="question" value="">');
                }
            });

            _createNumber();

            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    var _thisVal = $(this).val();
                    if (_thisVal == '643' || _thisVal == '644' || _thisVal == '645' || _thisVal == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#initEconomyControlExpertHtmlExtra').removeClass('hide');
                    $('#initEconomyControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initEconomyControlExpertHtmlExtra').removeClass('show');
                    $('#initEconomyControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            var _crimeValueTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/crimeValueLists",
                data: {modId: _nifsEconomyModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    _crimeValueTags = data;
                }
            });
            $("#protocolValue").autocomplete({
                source: _crimeValueTags
            });
            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsEconomyModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#agentName").autocomplete({
                source: agentNameTags
            });

            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-economy-in-out-date-diff-work-day'});
            _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-economy-protocol-in-out-date-diff-work-day'});

        });
    } else {
        _pageDeny();
    }
}
function _reportNifsEconomyWorkInformation(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsEconomyModRootPath + 'getReportWorkInformationData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsEconomyModId + '&reportModId=' + param.reportModId + '&reportMenuId=' + param.reportMenuId,
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
function _reportNifsEconomyWeight(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsEconomyModRootPath + 'getReportWeightData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsEconomyModId + '&reportModId=' + param.reportModId + '&reportMenuId=' + param.reportMenuId,
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
function _reportNifsEconomyPartner(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsEconomyModRootPath + 'getReportPartnerData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsEconomyModId + '&reportModId=' + param.reportModId + '&reportMenuId=' + param.reportMenuId,
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
