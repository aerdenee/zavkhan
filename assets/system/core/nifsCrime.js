var _dgNifsCrime = '';
var _getNifsCrimeUrlModule = _getUrlModule();
var _permissionNifsCrime = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getNifsCrimeUrlModule == 'snifsCrime') {
        _initNifsCrime({searchQuery:'selectedId=' + (getUrlParameter('selectedId') === undefined ? '' : getUrlParameter('selectedId')) + '&inDate=' + (getUrlParameter('inDate') === undefined ? '' : getUrlParameter('inDate')) + '&outDate=' + (getUrlParameter('outDate') === undefined ? '' : getUrlParameter('outDate')) + '&departmentId=' + (getUrlParameter('departmentId') === undefined ? '' : getUrlParameter('departmentId')) + '&keyword=' + (getUrlParameter('keyword') === undefined ? '' : getUrlParameter('keyword'))});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getNifsCrimeUrlModule == 'snifsCrime') {
        _addFormNifsCrime({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsCrimeUrlModule == 'snifsCrime') {
        var _row = _dgNifsCrime.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsCrime({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsCrimeUrlModule == 'snifsCrime') {
        var _row = _dgNifsCrime.datagrid('getSelected');
        if (_row != null) {
            _closeNifsCrime({elem: this, id: _row.id});
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
    if (_getNifsCrimeUrlModule == 'snifsCrime') {
        var _row = _dgNifsCrime.datagrid('getSelected');
        if (_row != null) {
            _deleteNifsCrime({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
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
    if (_getNifsCrimeUrlModule == 'snifsCrime') {
        _exportNifsCrime({elem: this});
    }

});
$(document).bind('keydown', 'f10', function () {
    if (_getNifsCrimeUrlModule == 'snifsCrime') {
        _advensedSearchNifsCrime({elem: this});
    }
});
function _initNifsCrime(param) {
    if (_permissionNifsCrime.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-crime"><table id="dgNifsCrime" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsCrime = $('#dgNifsCrime').datagrid({
            url: _nifsCrimeModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Криминалистикийн шинжилгээний бүртгэл',
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
                        _addFormNifsCrime({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    disabled: false,
                    handler: function () {
                        var _row = _dgNifsCrime.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsCrime({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgNifsCrime.datagrid('getSelected');
                        if (_row != null) {
                            _closeNifsCrime({elem: this, id: _row.id});
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
                        var _row = _dgNifsCrime.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsCrime({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
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
                        _exportNifsCrime({elem: this});
                    }
                }, '-', {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchNifsCrime({elem: this});
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
                    {field: 'resolution', title: 'Тогтоол', width: 150},
                    {field: 'crime_value', title: 'Хэргийн утга', width: 200},
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
            onRowContextMenu: function (e, rowIndex, rowData) {
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
                var _row = _dgNifsCrime.datagrid('getSelected');
                _editFormNifsCrime({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _exportNifsCrime(param) {

    if (_permissionNifsCrime.custom.export) {

        var _preparingFileModal = $("#file-download-preparing-file-modal");

        _preparingFileModal.dialog({modal: true});

        $.fileDownload('/' + _nifsCrimeModRootPath + 'export', {
            httpMethod: 'GET',
            data: $(_rootContainerId).find(_nifsCrimeFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
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
function _closeNifsCrime(param) {
    if (_permissionNifsCrime.custom.close) {
        if (!$(_nifsCrimeDialogId).length) {
            $('<div id="' + _nifsCrimeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: _nifsCrimeModRootPath + 'closeFrom',
            dataType: "json",
            data: {id: param.id, moduleMenuId: _MODULE_MENU_ID},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                if (data.is_page_deny) {
                    _pageDeny();
                } else {
                    $(_nifsCrimeDialogId).empty().html(data.html);
                    $(_nifsCrimeDialogId).dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: false,
                        autoOpen: false,
                        title: data.title,
                        width: 500,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $(_nifsCrimeDialogId).dialog('close').remove();
                        },
                        buttons: [
                            {text: data.btn_no, class: 'btn btn-default', click: function () {
                                    $(_nifsCrimeDialogId).dialog('close').remove();
                                }},
                            {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                    var _form = $(_nifsCrimeDialogId).find('form');
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsCrimeModRootPath + 'close',
                                        dataType: "json",
                                        data: _form.serialize(),
                                        success: function (data) {
                                            _PNotify({status: data.status, message: data.message});
                                            _initNifsCrime({page: 0, searchQuery: $(_rootContainerId).find(_nifsCrimeFormMainId + '-init').serialize()});
                                            $.unblockUI();
                                        }
                                    });
                                    $(_nifsCrimeDialogId).dialog('close').remove();
                                }}
                        ]
                    });
                    $(_nifsCrimeDialogId).dialog('open');
                }

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
function _deleteNifsCrime(param) {

    if ((_permissionNifsCrime.our.delete && param.createdUserId == _uIdCurrent) || (_permissionNifsCrime.your.delete && param.createdUserId != _uIdCurrent)) {
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
                            url: _nifsCrimeModRootPath + 'delete',
                            dataType: "json",
                            data: {moduleMenuId: _MODULE_MENU_ID, id: param.id, createdUserId: param.createdUserId, modId: param.modId},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initNifsCrime({page: 0, searchQuery: $(_rootContainerId).find(_nifsCrimeFormMainId + '-init').serialize()});

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
function _advensedSearchNifsCrime(param) {
    if (_permissionNifsCrime.isModule) {
        if (!$(_nifsCrimeDialogId).length) {
            $('<div id="' + _nifsCrimeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsCrimeModRootPath + 'searchForm',
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
                $(_nifsCrimeDialogId).html(data.html);
                $(_nifsCrimeDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsCrimeDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsCrimeDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initNifsCrime({page: 0, searchQuery: $(_nifsCrimeDialogId).find(_nifsCrimeFormMainId + '-search').serialize()});
                                $(_nifsCrimeDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_nifsCrimeDialogId).dialog('open');
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

            $('#latentPrintDepartmentId').on('change', function () {
                $.ajax({
                    type: 'post',
                    url: _hrPeopleModRootPath + 'controlHrPeopleDepartmentPeopleListDropdown',
                    data: {name: 'latentPrintExpertId', departmentId: $(this).val(), selectedId: 0},
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI({message: ''});
                    },
                    success: function (data) {
                        $('#nifs-latent-print-expert').html(data);
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
            });

            _createNumber();

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initNifsCrime({page: 0, searchQuery: $(_nifsCrimeDialogId).find(_nifsCrimeFormMainId + '-search').serialize()});
                    $(_nifsCrimeDialogId).empty().dialog('close');
                }
            });

        });
    } else {
        _pageDeny();
    }
}
function _addFormNifsCrime(param) {

    if (_permissionNifsCrime.our.create) {

        if (!$(_nifsCrimeDialogId).length) {
            $('<div id="' + _nifsCrimeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsCrimeModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsCrimeModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                if (data.is_page_deny) {
                    _pageDeny();
                } else {
                    $(_nifsCrimeDialogId).empty().html(data.html);
                    $(_nifsCrimeDialogId).dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: false,
                        autoOpen: false,
                        title: data.title,
                        width: 1000,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $(_nifsCrimeDialogId).empty().dialog('close');
                        },
                        buttons: [
                            {text: data.btn_no, class: 'btn btn-default', click: function () {
                                    $(_nifsCrimeDialogId).empty().dialog('close');
                                }},
                            {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                    var _form = $(_nifsCrimeDialogId).find('form');
                                    $(_form).validate({errorPlacement: function () {
                                        }});
                                    if ($(_form).valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: _nifsCrimeModRootPath + 'insert',
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
                                                //_initNifsCrime({page: 0, searchQuery: $(_rootContainerId).find(_nifsCrimeFormMainId + '-init').serialize()});
                                                _initNifsCrime({page: 0, searchQuery: {}});
                                                $.unblockUI();
                                            }
                                        });
                                    }
                                    $(_nifsCrimeDialogId).empty().dialog('close');
                                }}
                        ]
                    });
                    $(_nifsCrimeDialogId).dialog('open');
                }

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
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-crime-in-out-date-diff-work-day'});
            });
            var _to = $("#outDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _from.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-crime-in-out-date-diff-work-day'});
            });

            var _fromOut = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _toOut.datepicker("option", "minDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-crime-protocol-in-out-date-diff-work-day'});

            });
            var _toOut = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {

                _fromOut.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-crime-protocol-in-out-date-diff-work-day'});

            });

            $('#crimeObject').tokenfield();
            $('#crimeObject').on('tokenfield:createdtoken', function (e) {
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
            $('#crimeObject').on('tokenfield:removedtoken', function (e) {
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

            $('#latentPrintDepartmentId').on('change', function () {
                $.ajax({
                    type: 'post',
                    url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
                    data: {name: 'latentPrintExpertId', departmentId: $(this).val(), positionId: _nifsCrimePositionId, selectedId: 0},
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI({message: ''});
                    },
                    success: function (data) {
                        $('#nifs-latent-print-expert').html(data);
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
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
                    $('#initCrimeControlExpertHtmlExtra').removeClass('hide');
                    $('#initCrimeControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initCrimeControlExpertHtmlExtra').removeClass('show');
                    $('#initCrimeControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            $('select[name="researchTypeId"]').on('change', function () {

                if ($(this).val() == '4') {
                    $('button[name="addNifsCrimeExpertButton"]').attr('disabled', false);
                } else {
                    $('button[name="addNifsCrimeExpertButton"]').attr('disabled', true);
                }

            });

            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-crime-in-out-date-diff-work-day'});
            _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-crime-protocol-in-out-date-diff-work-day'});


            var _crimeValueTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/crimeValueLists",
                data: {modId: _nifsCrimeModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    _crimeValueTags = data;
                }
            });

            $("#crimeValue").autocomplete({
                source: _crimeValueTags
            });

            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsCrimeModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#agentName").autocomplete({
                source: agentNameTags
            });

        });
    } else {
        _pageDeny();
    }
}
function _editFormNifsCrime(param) {
    if ((_permissionNifsCrime.our.update && param.createdUserId == _uIdCurrent) || (_permissionNifsCrime.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_nifsCrimeDialogId).length) {
            $('<div id="' + _nifsCrimeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsCrimeModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsCrimeModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsCrimeDialogId).empty().html(data.html);
                if (data.is_page_deny) {
                    _pageDeny();
                } else {
                    $(_nifsCrimeDialogId).dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: false,
                        autoOpen: false,
                        title: data.title,
                        width: 1000,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $(_nifsCrimeDialogId).empty().dialog('close');
                        },
                        buttons: [
                            {text: data.btn_no, class: 'btn btn-default', click: function () {
                                    $(_nifsCrimeDialogId).empty().dialog('close');
                                }},
                            {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                    var _form = $(_nifsCrimeDialogId).find('form');
                                    $(_form).validate({errorPlacement: function () {
                                        }});
                                    if ($(_form).valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: _nifsCrimeModRootPath + 'update',
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
                                                $.unblockUI();
                                                _initNifsCrime({page: 0, searchQuery: $(_rootContainerId).find(_nifsCrimeFormMainId + '-init').serialize()});
                                            }
                                        });
                                    }
                                    $(_nifsCrimeDialogId).empty().dialog('close');
                                }},
                            {text: data.btn_save_close, class: 'btn btn-success active legitRipple', click: function () {
                                    var _form = $(_nifsCrimeDialogId).find('form');
                                    $(_form).validate({errorPlacement: function () {
                                        }});
                                    if ($(_form).valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: _nifsCrimeModRootPath + 'update',
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
                                                _closeNifsCrime({elem: this, id: _form.find('input[name="id"]').val()});
                                                $.unblockUI();
                                            }
                                        });
                                    }
                                    $(_nifsCrimeDialogId).empty().dialog('close');
                                }}
                        ]
                    });
                    $(_nifsCrimeDialogId).dialog('open');
                }

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
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-crime-in-out-date-diff-work-day'});
            });
            var _to = $("#outDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _from.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-crime-in-out-date-diff-work-day'});
            });

            var _fromOut = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _toOut.datepicker("option", "minDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-crime-protocol-in-out-date-diff-work-day'});

            });
            var _toOut = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {

                _fromOut.datepicker("option", "maxDate", _getDate(this));
                _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-crime-protocol-in-out-date-diff-work-day'});

            });

            $('#crimeObject').tokenfield();
            $('#crimeObject').on('tokenfield:createdtoken', function (e) {
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
            $('#crimeObject').on('tokenfield:removedtoken', function (e) {
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

            $('#latentPrintDepartmentId').on('change', function () {
                $.ajax({
                    type: 'post',
                    url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
                    data: {name: 'latentPrintExpertId', departmentId: $(this).val(), positionId: _nifsCrimePositionId, selectedId: 0},
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI({message: ''});
                    },
                    success: function (data) {
                        $('#nifs-latent-print-expert').html(data);
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
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
                    $('#initCrimeControlExpertHtmlExtra').removeClass('hide');
                    $('#initCrimeControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initCrimeControlExpertHtmlExtra').removeClass('show');
                    $('#initCrimeControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            $('select[name="researchTypeId"]').on('change', function () {

                if ($(this).val() == '4') {
                    $('button[name="addNifsCrimeExpertButton"]').attr('disabled', false);
                } else {
                    $('button[name="addNifsCrimeExpertButton"]').attr('disabled', true);
                }

            });

            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-crime-in-out-date-diff-work-day'});
            _dateDiffDay({inDate: $("#protocolInDate").val(), outDate: $("#protocolOutDate").val(), initName: '#nifs-crime-protocol-in-out-date-diff-work-day'});

            var _crimeValueTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/crimeValueLists",
                data: {modId: _nifsCrimeModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    _crimeValueTags = data;
                }
            });

            $("#crimeValue").autocomplete({
                source: _crimeValueTags
            });

            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsCrimeModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#agentName").autocomplete({
                source: agentNameTags
            });

        });
    } else {
        _pageDeny();
    }
}
function _addQuestion(param) {
    if ($(param.elem).parents('div[class="input-group"]').find('input[name="question[]"]').val().length > 0) {
        _html = '<div class="form-group row dynamic-form-group">';
        _html += '<label for="" required="required" class="col-4 col-form-label text-right" defined="1"></label>';
        _html += '<div class="col-md-8">';
        _html += '<div class="input-group">';
        _html += '<input type="text" name="question[]" value="" maxlength="500" class="form-control border-right-0" required="required">';
        _html += '<span class="input-group-append">';
        _html += '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_removeQuestion({elem: this});"><i class="icon-cancel-circle2"></i></span>';
        _html += '</span>';
        _html += '</div>';
        _html += '</div>';
        _html += '<div class="clearfix"></div>';
        _html += '</div>';
        $('#quistionHtml').append(_html);
    } else {

        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html('Та асуултаа бичээгүй байна');
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

}
function _removeQuestion(param) {
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
                    $(param.elem).parents('.dynamic-form-group').remove();
                    $(_dialogAlertDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
function _reportNifsCrimeWorkInformation(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsCrimeModRootPath + 'getReportWorkInformationData',
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
function _reportNifsCrimeWeight(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsCrimeModRootPath + 'getReportWeightData',
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
function _reportNifsCrimePartner(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsCrimeModRootPath + 'getReportPartnerData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsCrimeModId,
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