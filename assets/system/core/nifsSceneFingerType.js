
$(document).ready(function () {
    if (_getUrlModule() == 'snifsSceneFingerType') {
        _initNifsSceneFingerType({page: 0, searchQuery: {}});
        $.contextMenu({selector: '.context-menu-nifs-scene-finger-type-selected-row', items: _loadContextMenuNifsSceneFingerType()});
    }

});
$(document).bind('keydown', 'f2', function () {
    _addFormNifsSceneType({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchNifsSceneFingerType({elem: this});
});

function _initNifsSceneFingerType(param) {
    $.ajax({
        type: 'get',
        url: _nifsSceneFingerTypeModRootPath + 'lists',
        data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsSceneFingerTypeModId + '&per_page=' + param.page,
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
    });
}
function _removeNifsSceneFingerType(param) {
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace("#", "") + '"></div>').appendTo('body');
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
                        url: _nifsSceneFingerTypeModRootPath + 'delete',
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

                            _initNifsSceneFingerType({page: 0});
                        }
                    });
                    $(_dialogAlertDialogId).empty().dialog('close');
                }}

        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
function _advensedSearchNifsSceneFingerType(param) {
    if (!$(_nifsSceneFingerTypeDialogId).length) {
        $('<div id="' + _nifsSceneFingerTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsSceneFingerTypeModRootPath + 'searchForm',
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
            $(_nifsSceneFingerTypeDialogId).html(data.html);
            $(_nifsSceneFingerTypeDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsSceneFingerTypeDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsSceneFingerTypeDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initNifsSceneFingerType({page: 0, searchQuery: $(_nifsSceneFingerTypeDialogId).find('form').serialize()});
                            $(_nifsSceneFingerTypeDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_nifsSceneFingerTypeDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
    });

}
function _loadContextMenuNifsSceneFingerType() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "plus",
            callback: function () {
                _addFormNifsSceneFingerType({elem: this, modId: _nifsSceneFingerTypeModId});
            },
            disabled: function (key, opt) {
                if ($('input[name="our[\'create\']"]').val() == 1) {
                    return this.data('');
                } else {
                    return !this.data('');
                }
            }
        },
        "edit": {
            name: "Засах",
            icon: "edit",
            callback: function () {
                var _tr = $(this).parents('tr');
                _editFormNifsSceneFingerType({elem: this, id: _tr.attr('data-id')});
            },
            disabled: function (key, opt) {
                var _tr = $(this).parents('tr');

                if (($('input[name="our[\'update\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'update\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                    return this.data('');
                } else {
                    return !this.data('');
                }
                return !this.data('');
            }
        },
        "separator1": '---------',
        "delete": {
            name: "Устгах",
            icon: "trash",
            callback: function () {
                var _tr = $(this).parents('tr');
                _removeNifsSceneFingerType({elem: this, modId: _nifsSceneFingerTypeModId, id: _tr.attr('data-id')});
            },
            disabled: function (key, opt) {
                var _tr = $(this).parents('tr');
                if (($('input[name="our[\'delete\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'delete\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                    return this.data('');
                } else {
                    return !this.data('');
                }
            }
        }
    }
}
function _addFormNifsSceneFingerType(param) {
    if (!$(_nifsSceneFingerTypeDialogId).length) {
        $('<div id="' + _nifsSceneFingerTypeDialogId.replace("#", "") + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsSceneFingerTypeModRootPath + 'add',
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
            $(_nifsSceneFingerTypeDialogId).empty().html(data.html);

            $(_nifsSceneFingerTypeDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 700,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsSceneFingerTypeDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsSceneFingerTypeDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            var _form = $(_nifsSceneFingerTypeDialogId).find('form' + _nifsSceneFingerTypeFormMainId);
                            $(_form).validate({
                                errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsSceneFingerTypeModRootPath + 'insert',
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
                                        if (data.status === 'success') {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-success'
                                            });
                                            _initNifsSceneFingerType({page: 0});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsSceneFingerTypeDialogId).empty().dialog('close');
                                });
                            }
                        }}
                ]
            });
            $(_nifsSceneFingerTypeDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.select2').select2();
    });
}
function _editFormNifsSceneFingerType(param) {
    var _dialogId = 'partner-edit-form';
    if (!$('#' + _dialogId).length) {
        $('<div id="' + _dialogId + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsSceneFingerTypeModRootPath + 'edit',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsSceneFingerTypeModId, id: param.id},
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
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 700,
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

                            var _form = $('#' + _dialogId).find('form');
                            $(_form).validate({errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsSceneFingerTypeModRootPath + 'update',
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
                                        if (data.status === 'success') {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-success'
                                            });
                                            _initNifsSceneFingerType({page: 0});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $('#' + _dialogId).empty().dialog('close');
                                });
                            }
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
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.select2').select2();
    });

}