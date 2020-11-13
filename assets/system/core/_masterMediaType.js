var _permissionMasterMediaType = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).bind('keydown', 'f2', function () {
    _addFormMasterMediaType({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchMasterMediaType({elem: this});
});

function _initMasterMediaType(param) {

    var _root = $(_masterMediaTypeWindowId);

    $.ajax({
        url: _masterMediaTypeModRootPath + 'lists',
        type: 'GET',
        dataType: 'json',
        data: {
            moduleMenuId: _MODULE_MENU_ID,
            contId: _root.attr('data-cont-id'),
            modId: _root.attr('data-mod-id')
        },
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            _root.html(data);
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {


    });

}
function _deleteMasterMediaType(param) {
    if ((_permissionMasterMediaType.our.delete && param.createdUserId == _uIdCurrent) || (_permissionMasterMediaType.your.delete && param.createdUserId != _uIdCurrent)) {
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
                            url: _masterMediaTypeModRootPath + 'delete',
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
                                _initMasterMediaType({page: 0});
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
function _addFormMasterMediaType(param) {

    if (_permissionMasterMediaType.our.create) {
        if (!$(_masterMediaTypeDialogId).length) {
            $('<div id="' + _masterMediaTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _masterMediaTypeModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, contId: $(_masterMediaTypeWindowId).attr('data-cont-id'), modId: $(_masterMediaTypeWindowId).attr('data-mod-id')},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_masterMediaTypeDialogId).empty().html(data.html);

                $(_masterMediaTypeDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_masterMediaTypeDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_masterMediaTypeDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="mediaIntroText"]').val(CKEDITOR.instances.mediaIntroText.getData());
                                var _form = $(_masterMediaTypeDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _masterMediaTypeModRootPath + 'insert',
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
                                            _initMasterMediaType();
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_masterMediaTypeDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_masterMediaTypeDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            CKEDITOR.replace('mediaIntroText');

        });
    } else {
        _pageDeny();
    }

}
function _editFormMasterMediaType(param) {
    if ((_permissionMasterMediaType.our.update && param.createdUserId == _uIdCurrent) || (_permissionMasterMediaType.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_masterMediaTypeDialogId).length) {
            $('<div id="' + _masterMediaTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _masterMediaTypeModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, contId: $(_masterMediaTypeWindowId).attr('data-cont-id'), modId: $(_masterMediaTypeWindowId).attr('data-mod-id'), id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_masterMediaTypeDialogId).html(data.html);

                $(_masterMediaTypeDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_masterMediaTypeDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_masterMediaTypeDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="mediaIntroText"]').val(CKEDITOR.instances.mediaIntroText.getData());
                                var _form = $(_masterMediaTypeDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _masterMediaTypeModRootPath + 'update',
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
                                            _initMasterMediaType({page: 0, searchQuery: {}});
                                        }
                                    }).done(function () {
                                        $(_masterMediaTypeDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_masterMediaTypeDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            CKEDITOR.replace('mediaIntroText');

        });
    } else {
        _pageDeny();
    }

}