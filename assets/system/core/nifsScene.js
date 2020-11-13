var _dgNifsScene = '';
var _getNifsSceneUrlModule = _getUrlModule();
var _permissionNifsScene = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getNifsSceneUrlModule == 'snifsScene') {
        _initNifsScene({page: 0, searchQuery: $(_rootContainerId).find(_nifsSceneFormMainId + '-init').serialize()});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getNifsCrimeUrlModule == 'snifsScene') {
        _addFormNifsScene({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getNifsCrimeUrlModule == 'snifsScene') {
        var _row = _dgNifsScene.datagrid('getSelected');
        if (_row != null) {
            _editFormNifsScene({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getNifsCrimeUrlModule == 'snifsScene') {
        var _row = _dgNifsScene.datagrid('getSelected');
        if (_row != null) {
            _deleteNifsScene({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
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
    if (_getNifsCrimeUrlModule == 'snifsScene') {
        _exportNifsScene({elem: this});
    }

});
$(document).bind('keydown', 'f10', function () {
    if (_getNifsCrimeUrlModule == 'snifsScene') {
        _advensedSearchNifsScene({elem: this});
    }
});
function _exportNifsScene(param) {

    if (_permissionNifsScene.custom.export) {

        var _preparingFileModal = $("#file-download-preparing-file-modal");

        _preparingFileModal.dialog({modal: true});

        $.fileDownload('/' + _nifsSceneModRootPath + 'export', {
            httpMethod: 'GET',
            data: $(_rootContainerId).find(_nifsSceneFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
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
function _initNifsScene(param) {
    if (_permissionNifsScene.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-nifs-scene"><table id="dgNifsScene" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgNifsScene = $('#dgNifsScene').datagrid({
            url: _nifsSceneModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Хэргийн газрын үзлэг',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormNifsScene({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    handler: function () {
                        var _row = _dgNifsScene.datagrid('getSelected');
                        if (_row != null) {
                            _editFormNifsScene({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgNifsScene.datagrid('getSelected');
                        if (_row != null) {
                            _deleteNifsScene({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _exportNifsScene({elem: this});
                    }
                }, '-', {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchNifsScene({elem: this});
                    }
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'create_number', title: '#'},
                    {field: 'in_out_date', title: 'Огноо', align: 'center', width: 100},
                    {field: 'partner', title: 'Дүүрэг, цагдаагийн хэлтэс', width: 200},
                    {field: 'expert', title: 'Шинжээч', width: 150},
                    {field: 'type', title: 'Төрөл', width: 200},
                    {field: 'scene_value', title: 'Хэргийн утга', width: 150},
                    {field: 'object', title: 'Ул мөр', width: 150},
                    {field: 'description', title: 'Тайлбар', width: 150}
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
            }, onDblClickRow: function () {
                var _row = _dgNifsScene.datagrid('getSelected');
                _editFormNifsScene({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _deleteNifsScene(param) {

    if ((_permissionNifsScene.our.delete && param.userId == _uIdCurrent) || (_permissionNifsScene.your.delete && param.userId != _uIdCurrent)) {
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
                            url: _nifsSceneModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initNifsScene({page: 0, searchQuery: $(_rootContainerId).find(_nifsSceneFormMainId + '-init').serialize()});
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
function _advensedSearchNifsScene(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'read', moduleMenudId: _MODULE_MENU_ID, createdUserId: _uIdCurrent});
    if (_permission) {
        if (!$(_nifsSceneDialogId).length) {
            $('<div id="' + _nifsSceneDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsSceneModRootPath + 'searchForm',
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
                $(_nifsSceneDialogId).html(data.html);
                $(_nifsSceneDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsSceneDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsSceneDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initNifsScene({page: 0, searchQuery: $(_nifsSceneDialogId).find(_nifsSceneFormMainId + '-search').serialize()});
                                $(_nifsSceneDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_nifsSceneDialogId).dialog('open');
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

            _initPickatime({loadName: '.init-pickatime'});

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
                    _initNifsScene({page: 0, searchQuery: $(_nifsSceneDialogId).find(_nifsSceneFormMainId + '-search').serialize()});
                    $(_nifsSceneDialogId).empty().dialog('close');
                }
            });

        });
    } else {
        _pageDeny();
    }

}
function _addFormNifsScene(param) {
    if (_permissionNifsScene.our.create) {
        if (!$(_nifsSceneDialogId).length) {
            $('<div id="' + _nifsSceneDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsSceneModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsSceneModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsSceneDialogId).empty().html(data.html);
                $(_nifsSceneDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsSceneDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsSceneDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_nifsSceneDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsSceneModRootPath + 'insert',
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
                                            _initNifsScene({page: 0, searchQuery: $(_rootContainerId).find(_nifsSceneFormMainId + '-init').serialize()});
                                            $.unblockUI();
                                        }
                                    });
                                }
                                $(_nifsSceneDialogId).empty().dialog('close');
                            }}
                    ]
                });
                $(_nifsSceneDialogId).dialog('open');
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
            _initPickatime({loadName: '.init-pickatime'});

            $('._init-number').autoNumeric('init', {vMin: 0, vMax: 99999, aSep: ''});

            $('#otherPrint').tokenfield();
            $('#otherPrint').on('tokenfield:createdtoken', function (e) {
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
                $('input[name="otherPrintCount"]').val(_allObjectCount);
                $('.other-print-count').text('Бусад ул мөр, эд мөрийн баримт (' + _allObjectCount + '):');
            });
            $('#otherPrint').on('tokenfield:removedtoken', function (e) {
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
                $('input[name="otherPrintCount"]').val(_allObjectCount);
                if (_allObjectCount == 0) {
                    $('.other-print-count').text('Бусад ул мөр, эд мөрийн баримт:');
                } else {
                    $('.other-print-count').text('Бусад ул мөр, эд мөрийн баримт (' + _allObjectCount + '):');
                }
            });


            $('input[name="createNumber"]').focus();
            _createNumber();

            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    if ($(this).val() == '643' || $(this).val() == '644' || $(this).val() == '645' || $(this).val() == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#initSceneControlExpertHtmlExtra').removeClass('hide');
                    $('#initSceneControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initSceneControlExpertHtmlExtra').removeClass('show');
                    $('#initSceneControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });

            var availableTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/crimeValueLists",
                data: {modId: _nifsSceneModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    availableTags = data;
                }
            });
            $("#sceneValue").autocomplete({
                source: availableTags
            });

            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsSceneModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#sceneExpert").autocomplete({
                source: agentNameTags
            });
        });
    } else {
        _pageDeny();
    }
}
function _editFormNifsScene(param) {
    if ((_permissionNifsScene.our.update && param.userId == _uIdCurrent) || (_permissionNifsScene.your.update && param.userId != _uIdCurrent)) {
        if (!$(_nifsSceneDialogId).length) {
            $('<div id="' + _nifsSceneDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsSceneModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsSceneModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsSceneDialogId).empty().html(data.html);
                $(_nifsSceneDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_nifsSceneDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_nifsSceneDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_nifsSceneDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _nifsSceneModRootPath + 'update',
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
                                            _initNifsScene({page: 0, searchQuery: $(_rootContainerId).find(_nifsSceneFormMainId + '-init').serialize()});
                                        }
                                    });
                                }
                                $(_nifsSceneDialogId).empty().dialog('close');
                            }}
                    ]
                });
                $(_nifsSceneDialogId).dialog('open');
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

            $('._init-number').autoNumeric('init', {vMin: 0, vMax: 99999, aSep: ''});

            $('#otherPrint').tokenfield();
            $('#otherPrint').on('tokenfield:createdtoken', function (e) {
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
                $('input[name="otherPrintCount"]').val(_allObjectCount);
                $('.other-print-count').text('Бусад ул мөр, эд мөрийн баримт (' + _allObjectCount + '):');
            });
            $('#otherPrint').on('tokenfield:removedtoken', function (e) {
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
                $('input[name="otherPrintCount"]').val(_allObjectCount);
                if (_allObjectCount == 0) {
                    $('.other-print-count').text('Бусад ул мөр, эд мөрийн баримт:');
                } else {
                    $('.other-print-count').text('Бусад ул мөр, эд мөрийн баримт (' + _allObjectCount + '):');
                }
            });

            $('input[name="createNumber"]').focus();
            _createNumber();
            _initPickatime({loadName: '.init-pickatime'});
            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    if ($(this).val() == '643' || $(this).val() == '644' || $(this).val() == '645' || $(this).val() == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#initSceneControlExpertHtmlExtra').removeClass('hide');
                    $('#initSceneControlExpertHtmlExtra').addClass('show');
                } else {
                    $('#initSceneControlExpertHtmlExtra').removeClass('show');
                    $('#initSceneControlExpertHtmlExtra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });


            var availableTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/crimeValueLists",
                data: {modId: _nifsSceneModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    availableTags = data;
                }
            });
            $("#sceneValue").autocomplete({
                source: availableTags
            });

            var agentNameTags = [];
            $.ajax({
                dataType: "json",
                url: "snifsKeywords/agentNameLists",
                data: {modId: _nifsSceneModId, departmentId: data.departmentId},
                async: false,
                success: function (data) {
                    agentNameTags = data;
                }
            });
            $("#sceneExpert").autocomplete({
                source: agentNameTags
            });

        });
    } else {
        _pageDeny();
    }
}
function _reportNifsSceneWorkInformation(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsSceneModRootPath + 'getReportWorkInformationData',
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
function _reportNifsSceneWeight(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsSceneModRootPath + 'getReportWeightData',
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
function _reportNifsScenePartner(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsSceneModRootPath + 'getReportPartnerData',
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
