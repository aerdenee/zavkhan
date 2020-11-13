var _permissionContentMedia = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).bind('keydown', 'f2', function () {
    _addFormContentMedia({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchContentMedia({elem: this});
});

function _initContentMedia(param) {

    var _contentMediaRoot = $(_contentMediaWindowId);
    $.ajax({
        url: _contentMediaModRootPath + 'lists',
        type: 'GET',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, contId: _contentMediaRoot.attr('data-cont-id'), modId: _contentMediaRoot.attr('data-mod-id')},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            _contentMediaRoot.html(data);
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {


    });
}
function _deleteContentMedia(param) {
    if ((_permissionContentMedia.our.delete && param.createdUserId == _uIdCurrent) || (_permissionContentMedia.your.delete && param.createdUserId != _uIdCurrent)) {
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
                            url: _contentMediaModRootPath + 'delete',
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
                                _initContentMedia({page: 0});
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
function _addFormContentMedia(param) {

    if (_permissionContentMedia.our.create) {
        if (!$(_contentMediaDialogId).length) {
            $('<div id="' + _contentMediaDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _contentMediaModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, contId: $(_contentMediaWindowId).attr('data-cont-id'), modId: $(_contentMediaWindowId).attr('data-mod-id')},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_contentMediaDialogId).empty().html(data.html);

                $(_contentMediaDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_contentMediaDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_contentMediaDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="contentMediaIntroText"]').val(CKEDITOR.instances.contentMediaIntroText.getData());
                                var _form = $(_contentMediaDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _contentMediaModRootPath + 'insert',
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
                                            _initContentMedia({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_contentMediaDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_contentMediaDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            CKEDITOR.replace('contentMediaIntroText');

        });
    } else {
        _pageDeny();
    }

}
function _editFormContentMedia(param) {
    if ((_permissionContentMedia.our.update && param.createdUserId == _uIdCurrent) || (_permissionContentMedia.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_contentMediaDialogId).length) {
            $('<div id="' + _contentMediaDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _contentMediaModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_contentMediaDialogId).html(data.html);

                $(_contentMediaDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_contentMediaDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_contentMediaDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="contentMediaIntroText"]').val(CKEDITOR.instances.contentMediaIntroText.getData());
                                var _form = $(_contentMediaDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _contentMediaModRootPath + 'update',
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
                                            _initContentMedia({page: 0, searchQuery: {}});
                                        }
                                    }).done(function () {
                                        $(_contentMediaDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_contentMediaDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            CKEDITOR.replace('contentMediaIntroText');

        });
    } else {
        _pageDeny();
    }

}
function _showContentMedia(param) {
    var _this = $(param.elem);

    if (!$(_docTransferDialogId).length) {
        $('<div id="' + _docTransferDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: _contentMediaModRootPath + 'show',
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