var _dgAddress = '';
var _getAddressUrlModule = _getUrlModule();
var _permission = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getAddressUrlModule == 'saddress') {
        _initAddress({page: 0, searchQuery: {}});
    }

});
$(document).bind('keydown', 'f2', function () {
    _addFormAddress({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchAddress({elem: this});
});

function _initAddress(param) {
    if (_permission.isModule) {
        $.ajax({
            type: 'get',
            url: _addressModRootPath + 'lists',
            data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _addressModId + '&per_page=' + param.page,
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
            $.contextMenu({selector: '.context-menu-address-selected-row', items: _loadContextMenuAddress()});
        });
    } else {
        _pageDeny();
    }
}

function _deleteAddress(param) {
    if (!$(_addressDialogId).length) {
        $('<div id="' + _addressDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_addressDialogId).empty().html(_dialogAlertDeleteMessage);
    $(_addressDialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_addressDialogId).dialog('close').empty();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_addressDialogId).dialog('close').empty();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _addressModRootPath + 'delete',
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

                            _initAddress({page: 0});
                        }
                    });
                    $(_addressDialogId).empty().dialog('close');
                }}

        ]
    });
    $(_addressDialogId).dialog('open');
}
function _advensedSearchAddress(param) {
    if (!$(_addressDialogId).length) {
        $('<div id="' + _addressDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _addressModRootPath + 'searchForm',
        type: 'get',
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_addressDialogId).html(data.html);
            $(_addressDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_addressDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_addressDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initAddress({page: 0, searchQuery: $(_addressDialogId).find('form').serialize()});
                            $(_addressDialogId).empty().dialog('close');
                        }}

                ]
            });
            $(_addressDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initAddress({page: 0, searchQuery: $(_addressDialogId).find('form').serialize()});
                $(_addressDialogId).empty().dialog('close');
            }
        });
    });

}
function _loadContextMenuAddress() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "add",
            callback: function () {
                _addFormAddress({elem: this, modId: _partnerModId});
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
                _editFormAddress({elem: this, id: _tr.attr('data-id'), userId: _tr.attr('data-uid')});
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
                _deleteAddress({elem: this, modId: _addressModId, id: _tr.attr('data-id')});
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
function _addFormAddress(param) {
    if ((_permission.our.update && param.userId == _uIdCurrent) || (_permission.your.update && param.userId != _uIdCurrent)) {
        if (!$(_addressDialogId).length) {
            $('<div id="' + _addressDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _addressModRootPath + 'add',
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
                $(_addressDialogId).empty().html(data.html);

                $(_addressDialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 800,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_addressDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_addressDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                var _form = $(_addressDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _addressModRootPath + 'insert',
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
                                            _initAddress({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_addressDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_addressDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.radio, .checkbox').uniform();
            $('.select2').select2();
        });
    } else {
        _pageDeny();
    }
}
function _editFormAddress(param) {
    if ((_permission.our.update && param.userId == _uIdCurrent) || (_permission.your.update && param.userId != _uIdCurrent)) {
        if (!$(_addressDialogId).length) {
            $('<div id="' + _addressDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _addressModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _addressModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_addressDialogId).html(data.html);

                $(_addressDialogId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 800,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_addressDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_addressDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_addressDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _addressModRootPath + 'update',
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
                                            _initAddress({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_addressDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_addressDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.radio, .checkbox').uniform();
            $('.select2').select2();
        });
    } else {
        _pageDeny();
    }

}
function _initSoumControlDropDown(param) {
    $.ajax({
        url: _addressModRootPath + 'controlAddressDropDown',
        type: 'POST',
        dataType: 'json',
        data: {
            parentId: param.parentId,
            selectedId: param.selectedId,
            name: param.name},
        async: false,
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('.' + param.initClass).html(data + param.description);
            $('.address-street-html').html('<select class="select2" disabled><option value="0"> - Сонгох - </option></select><input type="hidden" name="streetId" value="0"><span class="help-block">Хороо, баг</span>');
        }
    }).done(function () {
        $.unblockUI();
        $('.select2').select2();

        $('#soumId').on('change', function () {
            _initStreetControlDropDown({
                parentId: $(this).val(),
                selectedId: 0,
                name: 'streetId',
                initClass: 'address-street-html',
                description: '<span class="help-block">Хороо, баг</span>'});
        });
    });
}
function _initStreetControlDropDown(param) {
    $.ajax({
        url: _addressModRootPath + 'controlAddressDropDown',
        type: 'POST',
        dataType: 'json',
        data: {
            parentId: param.parentId,
            selectedId: param.selectedId,
            name: param.name},
        async: false,
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('.' + param.initClass).html(data + param.description);
        }
    }).done(function () {
        $.unblockUI();
        $('.select2').select2();
    });
}