var _dgNifsSendDocument = '';
var _getNifsSendDocumentUrlModule = _getUrlModule();
var _permissionNifsSendDocument = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

var _sendDocumentIsOldUrl = window.location.pathname;
_sendDocumentIsOldUrl = _sendDocumentIsOldUrl.split('/');
var _isLoadNew = true;
if (_sendDocumentIsOldUrl['3'] == 69) {
    _isLoadNew = false;
}
$(document).ready(function () {
    if (_getNifsSendDocumentUrlModule == 'snifsSendDocument' && !_isLoadNew) {
        _oldInitNifsSendDocument({page: 0, searchQuery: {}});
    }

    if (_getNifsSendDocumentUrlModule == 'snifsSendDocument' && _isLoadNew) {
        _initNifsSendDocument({searchQuery:'selectedId=' + (getUrlParameter('selectedId') === undefined ? '' : getUrlParameter('selectedId')) + '&inDate=' + (getUrlParameter('inDate') === undefined ? '' : getUrlParameter('inDate')) + '&outDate=' + (getUrlParameter('outDate') === undefined ? '' : getUrlParameter('outDate')) + '&departmentId=' + (getUrlParameter('departmentId') === undefined ? '' : getUrlParameter('departmentId')) + '&keyword=' + (getUrlParameter('keyword') === undefined ? '' : getUrlParameter('keyword'))});
    }
});

$(document).bind('keydown', 'f10', function () {
    if (_getNifsSendDocumentUrlModule == 'snifsSendDocument') {
        _advensedSearchNifsSendDocument({elem: this});
    }
});

function _oldInitNifsSendDocument(param) {
    if (_permissionNifsSendDocument.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-send-document"><table id="dgNifsSendDocumentOld" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsSendDocument = $('#dgNifsSendDocumentOld').datagrid({
            url: _nifsSendDocumentModRootPath + 'oldLists',
            method: 'get',
            queryParams: _param[0],
            title: '2019 онд бүртгэгдсэн илгээх бичиг',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Хаалт (F4)',
                    iconCls: 'dg-icon-lock-1',
                    handler: function () {
                        var _row = _dgNifsSendDocument.datagrid('getSelected');
                        if (_row != null) {
                            _oldCloseNifsSendDocument({elem: this, id: _row.id});
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
                        _oldAdvensedSearchNifsSendDocument({elem: this});
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
                    {field: 'in_out_date', title: 'Бүртгэл', width: 60},
                    {field: 'doctor', title: 'Эмч', width: 60},
                    {field: 'full_name', title: 'Овог, нэр, РД', width: 140},
                    {field: 'partner', title: 'Тогтоол ИГ', width: 150},
                    {field: 'short_value', title: 'БХТ', width: 100},
                    {field: 'expert', title: 'Шинжээч', width: 80,
                        styler: function (value, row, index) {
                            return row.expert_status;
                        }},
                    {field: 'close_type', title: 'Гэмтэл', width: 70},
                    {field: 'close_description', title: 'Тайлбар', width: 100}
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
            }
        });
    } else {
        _pageDeny();
    }
}
function _oldCloseNifsSendDocument(param) {
    if (_permissionNifsSendDocument.custom.close) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: _nifsSendDocumentModRootPath + 'oldCloseFrom',
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
                                    url: _nifsSendDocumentModRootPath + 'oldClose',
                                    dataType: "json",
                                    data: _form.serialize(),
                                    success: function (data) {
                                        _PNotify({status: data.status, message: data.message});
                                        _oldInitNifsSendDocument({page: 0, searchQuery: {}});
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
function _oldAdvensedSearchNifsSendDocument(param) {
    if (_permissionNifsSendDocument.isModule) {
        if (!$(_nifsSendDocumentDialogId).length) {
            $('<div id="' + _nifsSendDocumentDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsSendDocumentModRootPath + 'oldSearchForm',
            type: 'POST',
            dataType: 'json',
            data: $(_rootContainerId).find('form#form-nifs-send-document').serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsSendDocumentDialogId).html(data.html);
                $(_nifsSendDocumentDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsSendDocumentDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsSendDocumentDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _oldInitNifsSendDocument({modId: _nifsDoctorViewModId, page: 0, searchQuery: $(_nifsSendDocumentDialogId).find('form').serialize()});
                                $(_nifsSendDocumentDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_nifsSendDocumentDialogId).dialog('open');
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
                        url: _nifsSendDocumentModRootPath + 'controlNifsIsSpermDropdown',
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
                    _initNifsDoctorView({page: 0, searchQuery: $(_nifsSendDocumentDialogId).find(_nifsDoctorViewFormMainId + '-search').serialize()});
                    $(_nifsSendDocumentDialogId).empty().dialog('close');
                }

            });
        });
    } else {
        _pageDeny();
    }

}
function _addFormNifsSendDocument(param) {

    if (!$(_nifsSendDocumentDialogId).length) {
        $('<div id="' + _nifsSendDocumentDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsSendDocumentModRootPath + 'add',
        type: 'POST',
        dataType: 'json',
        data: {contId: param.contId, modId: param.modId, moduleMenuId: _MODULE_MENU_ID, typeId: param.typeId},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsSendDocumentDialogId).empty().html(data.html);

            $(_nifsSendDocumentDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 850,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsSendDocumentDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsSendDocumentDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            var _form = $(_nifsSendDocumentDialogId).find('form');

                            $(_form).validate({errorPlacement: function () {
                                }});

                            if ($(_form).valid()) {

                                $.ajax({
                                    type: 'post',
                                    url: _nifsSendDocumentModRootPath + 'insert',
                                    data: $(_form).serialize(),
                                    dataType: 'json',
                                    beforeSend: function () {
                                        $.blockUI({message: null});
                                    },
                                    success: function (data) {
                                        _PNotify({status: data.status, message: data.message});
                                        $(_nifsSendDocumentDialogId).dialog('close').empty();
                                        param.reloadDataGrid.datagrid('reload');
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsSendDocumentDialogId).dialog('close').empty();
                                });
                            }
                        }}

                ]
            });
            $(_nifsSendDocumentDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        _initPickatime({loadName: '.init-pickatime'});
        var _from = $("#inDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _to.datepicker("option", "minDate", _getDate(this));
            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-send-document-in-out-date-diff-work-day'});
        });
        var _to = $("#outDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _from.datepicker("option", "maxDate", _getDate(this));
            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-send-document-in-out-date-diff-work-day'});
        });
        $('#sendObject').tokenfield();
        $('#sendObject').on('tokenfield:createdtoken', function (e) {
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
        $('#sendObject').on('tokenfield:removedtoken', function (e) {
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

        $('#departmentId').on('change', function () {
            var _this = $(this);
            
            $.ajax({
                url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
                type: 'POST',
                dataType: 'json',
                data: {name: 'expertId[]', departmentId: _this.val(), selectedId: 0},
                beforeSend: function () {
                    $.blockUI({
                        message: _jqueryBlockUiMessage,
                        overlayCSS: _jqueryBlockUiOverlayCSS,
                        css: _jqueryBlockUiMessageCSS
                    });
                },
                success: function (data) {
                    $('.init-default-hr-people').html(data);
                    $('#initSendDocumentControlExpertHtml').html('');
                }
            }).done(function () {
                $('.select2').select2();
                $.unblockUI();
            });
        });

    });
}
function _editFormNifsSendDocument(param) {

    if (!$(_nifsSendDocumentDialogId).length) {
        $('<div id="' + _nifsSendDocumentDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsSendDocumentModRootPath + 'edit',
        type: 'POST',
        dataType: 'json',
        data: {id: param.id, moduleMenuId: _MODULE_MENU_ID, typeId: param.typeId},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsSendDocumentDialogId).empty().html(data.html);

            $(_nifsSendDocumentDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 850,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsSendDocumentDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsSendDocumentDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_delete, class: 'btn btn-danger', click: function () {
                            var _this = $(this);
                            _deleteNifsSendDocument({id: param.id, contId: _this.find('input[name="contId"]').val(), modId: _this.find('input[name="modId"]').val(), createdUserId: _this.find('input[name="createdUserId"]').val(), reloadDataGrid: param.reloadDataGrid});
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            var _form = $(_nifsSendDocumentDialogId).find('form');

                            $(_form).validate({errorPlacement: function () {
                                }});

                            if ($(_form).valid()) {

                                $.ajax({
                                    type: 'post',
                                    url: _nifsSendDocumentModRootPath + 'update',
                                    data: $(_form).serialize(),
                                    dataType: 'json',
                                    beforeSend: function () {
                                        $.blockUI({message: null});
                                    },
                                    success: function (data) {
                                        _PNotify({status: data.status, message: data.message});
                                        param.reloadDataGrid.datagrid('reload');
                                        $(_nifsSendDocumentDialogId).dialog('close').empty();
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsSendDocumentDialogId).dialog('close').empty();
                                });
                            }
                        }}

                ]
            });
            $(_nifsSendDocumentDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        _initPickatime({loadName: '.init-pickatime'});
        var _from = $("#inDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _to.datepicker("option", "minDate", _getDate(this));
            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-send-document-in-out-date-diff-work-day'});
        });
        var _to = $("#outDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _from.datepicker("option", "maxDate", _getDate(this));
            _dateDiffDay({inDate: $("#inDate").val(), outDate: $("#outDate").val(), initName: '#nifs-send-document-in-out-date-diff-work-day'});
        });

        $('#sendObject').tokenfield();
        $('#sendObject').on('tokenfield:createdtoken', function (e) {
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
        $('#sendObject').on('tokenfield:removedtoken', function (e) {
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
    });
}
function _deleteNifsSendDocument(param) {
    if ((_permissionNifsSendDocument.our.delete && param.createdUserId == _uIdCurrent) || (_permissionNifsSendDocument.your.delete && param.createdUserId != _uIdCurrent)) {
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
                            url: _nifsSendDocumentModRootPath + 'delete',
                            dataType: "json",
                            data: {moduleMenuId: _MODULE_MENU_ID, id: param.id, createdUserId: param.createdUserId, modId: param.modId},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                param.reloadDataGrid.datagrid('reload');
                                $(_nifsSendDocumentDialogId).dialog('close').empty();
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
function _initNifsSendDocument(param) {
    if (_permissionNifsSendDocument.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-send-document"><table id="dgNifsSendDocument" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsSendDocument = $('#dgNifsSendDocument').datagrid({
            url: _nifsSendDocumentModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Илгээх бичиг',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Хаалт (F4)',
                    iconCls: 'dg-icon-lock-1',
                    handler: function () {
                        var _row = _dgNifsSendDocument.datagrid('getSelected');
                        if (_row != null) {
                            _closeNifsSendDocument({elem: this, id: _row.id});
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
                        _advensedSearchNifsSendDocument({elem: this});
                    }
                }, '-', {
                    text: 'Устгах (F6)',
                    iconCls: 'dg-icon-remove',
                    handler: function () {
                        var _row = _dgNifsSendDocument.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsSendDocument({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id, reloadDataGrid: _dgNifsSendDocument});
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
                        _exportNifsSendDocument({elem: this});
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
                    {field: 'in_out_date', title: 'Лаборатори', width: 80},
                    {field: 'dir_create_number', title: 'Бүртгэл', width: 80},
                    {field: 'partner', title: 'Тогтоол ИГ', width: 100},
                    {field: 'crime_value', title: 'БХТ', width: 150},
                    {field: 'dir_full_name', title: 'Шинжлүүлэгч', width: 120},
                    {field: 'send_object', title: 'Объект', width: 150},
                    {field: 'question', title: 'Асуулт', width: 140},
                    {field: 'expert', title: 'Эмч', width: 80,
                        styler: function (value, row, index) {
                            return row.expert_status;
                        }},
                    {field: 'close_type', title: 'Хариу', width: 70}
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
            }
        });
    } else {
        _pageDeny();
    }
}
function _closeNifsSendDocument(param) {
    if (_permissionNifsSendDocument.custom.close) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: _nifsSendDocumentModRootPath + 'closeFrom',
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
                                    url: _nifsSendDocumentModRootPath + 'close',
                                    dataType: "json",
                                    data: _form.serialize(),
                                    success: function (data) {
                                        _PNotify({status: data.status, message: data.message});
                                        _initNifsSendDocument({page: 0, searchQuery: {}});
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
function _advensedSearchNifsSendDocument(param) {
    if (_permissionNifsSendDocument.isModule) {
        if (!$(_nifsSendDocumentDialogId).length) {
            $('<div id="' + _nifsSendDocumentDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsSendDocumentModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: $(_rootContainerId).find('form#form-nifs-send-document').serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsSendDocumentDialogId).html(data.html);
                $(_nifsSendDocumentDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsSendDocumentDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsSendDocumentDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initNifsSendDocument({modId: _nifsSendDocumentModId, page: 0, searchQuery: $(_nifsSendDocumentDialogId).find('form').serialize()});
                                $(_nifsSendDocumentDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_nifsSendDocumentDialogId).dialog('open');
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
                        url: _nifsSendDocumentModRootPath + 'controlNifsIsSpermDropdown',
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
                    _initNifsSendDocument({page: 0, searchQuery: $(_nifsSendDocumentDialogId).find(_nifsSendDocumentFormMainId + '-search').serialize()});
                    $(_nifsSendDocumentDialogId).empty().dialog('close');
                }

            });
        });
    } else {
        _pageDeny();
    }

}
function _readResultNifsSendDocument(param) {
    
    var _dialogAlertDialogId = '#sendDocumentAlertDialog';
    
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    
    $.ajax({
        type: 'post',
        url: _nifsSendDocumentModRootPath + 'readResult',
        dataType: "json",
        data: {moduleMenuId: _MODULE_MENU_ID, contId: param.contId, moduleId: param.moduleId, typeId: param.typeId},
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
                width: 400,
                height: "auto",
                modal: true,
                close: function () {
                    $(_dialogAlertDialogId).dialog('close').empty();
                }
            });
            $(_dialogAlertDialogId).dialog('open');
            $.unblockUI();
        }
    });
}
function _reportNifsSendDocumentWorkInformation(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsSendDocumentModRootPath + 'getReportWorkInformationData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + param.reportModId + '&reportMenuId=' + param.reportMenuId,
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
function _reportNifsSendDocumentWeight(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsSendDocumentModRootPath + 'getReportWeightData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + param.reportModId + '&reportMenuId=' + param.reportMenuId,
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
function _exportNifsSendDocument(param) {

    if (_permissionNifsSendDocument.custom.export) {

        var _preparingFileModal = $("#file-download-preparing-file-modal");

        _preparingFileModal.dialog({modal: true});

        $.fileDownload('/' + _nifsSendDocumentModRootPath + 'export', {
            httpMethod: 'GET',
            data: $(_rootContainerId).find(_nifsSendDocumentFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
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