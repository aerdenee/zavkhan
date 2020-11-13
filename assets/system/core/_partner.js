var _dgPartner = '';
var _getPartnerUrlModule = _getUrlModule();
var _permission = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getPartnerUrlModule == 'spartner') {
        _initPartner({page: 0, searchQuery: {}});
    }
});
$(document).bind('keydown', 'f2', function () {
    if (_getPartnerUrlModule == 'partner') {
        _addFormPartner({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getPartnerUrlModule == 'partner') {
        _advensedSearchPartner({elem: this});
    }
});

function _initPartner(param) {
    if (_permission.isModule) {
        $.ajax({
            type: 'get',
            url: _partnerModRootPath + 'lists',
            data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _partnerModId + '&per_page=' + param.page,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_rootContainerId).html(data);
            }
        }).done(function () {
            $.unblockUI();
            $.contextMenu({selector: '.context-menu-partner-selected-row', items: _loadContextMenuPartner()});
        });
    } else {
        _pageDeny();
    }
}
function _deletePartner(param) {
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
                    $(_dialogAlertDialogId).dialog('close').remove();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _partnerModRootPath + 'delete',
                        dataType: "json",
                        data: {id: param.id},
                        success: function (data) {
                            if (data.status === 'success') {
                                new PNotify({
                                    text: data.message,
                                    addclass: 'bg-success'
                                });
                            } else {
                                new PNotify({
                                    text: data.message,
                                    addclass: 'bg-danger'
                                });
                            }

                            _initPartner({page: 0});
                        }
                    });
                    $(_dialogAlertDialogId).empty().dialog('close');
                }}

        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
function _advensedSearchPartner(param) {
    if (!$(_partnerDialogId).length) {
        $('<div id="' + _partnerDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _partnerModRootPath + 'searchForm',
        type: 'POST',
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_partnerDialogId).html(data.html);
            $(_partnerDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_partnerDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_partnerDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initPartner({page: 0, searchQuery: $(_partnerDialogId).find('form').serialize()});
                            $(_partnerDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_partnerDialogId).dialog('open');
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
    });

}
function _loadContextMenuPartner() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "add",
            callback: function () {
                _addFormPartner({elem: this, modId: _partnerModId});
            },
            disabled: function (key, opt) {
                if (_permission.our.create) {
                    return false;
                }
                return true;
            }
        },
        "edit": {
            name: "Засах",
            icon: "edit",
            callback: function () {
                var _tr = $(this).parents('tr');
                _editFormPartner({elem: this, id: _tr.attr('data-id'), userId: _tr.attr('data-uid')});
            },
            disabled: function (key, opt) {
                var _tr = $(this).parents('tr');

                if ((_permission.our.update && _tr.attr('data-uid') == _uIdCurrent) || (_permission.your.update && _tr.attr('data-uid') != _uIdCurrent)) {
                    return false;
                }

                return true;
            }
        },
        "separator1": '---------',
        "delete": {
            name: "Устгах",
            icon: "delete",
            callback: function () {
                var _tr = $(this).parents('tr');
                _deletePartner({elem: this, modId: _partnerModId, id: _tr.attr('data-id')});
            },
            disabled: function (key, opt) {
                var _tr = $(this).parents('tr');
                if ((_permission.our.delete && _tr.attr('data-uid') == _uIdCurrent) || (_permission.your.update && _tr.attr('data-uid') != _uIdCurrent)) {
                    return false;
                }
                return true;
            }
        }
    }
}
function _addFormPartner(param) {
    if ((_permission.our.update && param.userId == _uIdCurrent) || (_permission.your.update && param.userId != _uIdCurrent)) {

        if (!$(_partnerDialogId).length) {
            $('<div id="' + _partnerDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _partnerModRootPath + 'add',
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
                $(_partnerDialogId).empty().html(data.html);

                $(_partnerDialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_partnerDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_partnerDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_partnerDialogId).find('form' + _partnerFormMainId);
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _partnerModRootPath + 'insert',
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
                                            _initPartner({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_partnerDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_partnerDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.radio, .checkbox').uniform();
            $('.select2').select2();
            CKEDITOR.replace('description');

            $('#cityId').on('change', function () {
                _initSoumControlDropDown({
                    parentId: $(this).val(),
                    selectedId: 0,
                    name: 'soumId',
                    initClass: 'address-soum-html',
                    description: '<span class="help-block">Дүүрэг, сум</span>'});
            });
        });
    } else {
        _pageDeny();
    }
}
function _editFormPartner(param) {

    if ((_permission.our.update && param.userId == _uIdCurrent) || (_permission.your.update && param.userId != _uIdCurrent)) {

        if (!$(_partnerDialogId).length) {
            $('<div id="' + _partnerDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _partnerModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _partnerModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_partnerDialogId).html(data.html);

                $(_partnerDialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_partnerDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_partnerDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_partnerDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _partnerModRootPath + 'update',
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
                                            _initPartner({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_partnerDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_partnerDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.radio, .checkbox').uniform();
            $('.select2').select2();
            CKEDITOR.replace('description');
            $('#cityId').on('change', function () {
                _initSoumControlDropDown({
                    parentId: $(this).val(),
                    selectedId: 0,
                    name: 'soumId',
                    initClass: 'address-soum-html',
                    description: '<span class="help-block">Дүүрэг, сум</span>'});
            });
            $('#soumId').on('change', function () {
                _initSoumControlDropDown({
                    parentId: $(this).val(),
                    selectedId: 0,
                    name: 'steetId',
                    initClass: 'address-street-html',
                    description: '<span class="help-block">Хороо, баг</span>'});
            });
        });

    } else {
        _pageDeny();
    }

}
function _addPartnerControl(param) {
    var _notSelectedId = $(param.elem).parents('.input-group').find('select').val();
    if (_notSelectedId != 0) {
        $.ajax({
            type: 'post',
            url: _partnerModRootPath + 'controlPartnerMultiListDropdown',
            data: {
                modId: 0,
                contId: 0,
                name: 'partnerId[]',
                readonly: param.readonly,
                disabled: param.disabled,
                required: param.required,
                isDeleteButton: 1,
                isExtraValue: param.isExtraValue,
                removeFunction: '_removePartnerControl({elem:this});',
                removeButtonName: 'removePartnerButton',
                extraExpertValue: param.extraExpertValue
            },
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $('#' + param.initControlHtml).append(data);
            }
        }).done(function () {
            $('.select2').select2();
            $.unblockUI();
            $('select[name="partnerId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    if ($(this).val() == '1000001' || $(this).val() == '1000002' || $(this).val() == '1000003') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#init-control-crime-extra-expert-value-html').removeClass('hide');
                    $('#init-control-crime-extra-expert-value-html').addClass('show');
                } else {
                    $('#init-control-crime-extra-expert-value-html').removeClass('show');
                    $('#init-control-crime-extra-expert-value-html').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });
        });
    } else {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html("Байгууллага сонгоогүй байна");
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
                {text: _dialogAlertBtnClose, class: 'btn btn-primary active legitRipple', click: function () {
                        $(_dialogAlertDialogId).dialog('close').empty();
                    }}

            ]
        });
        $(_dialogAlertDialogId).dialog('open');
    }
}
function _removePartnerControl(param) {

    var _this = $(param.elem);
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_dialogAlertDialogId).empty().html("Байгууллагын мэдээллийг хасахдаа итгэлтэй байна уу?");
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
                    _this.parents('[data-expert-row="expert-row"]').remove();
                    $(_dialogAlertDialogId).dialog('close').empty();
                }}

        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}