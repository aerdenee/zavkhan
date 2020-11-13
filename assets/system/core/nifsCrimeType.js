
$(document).ready(function () {
    if (_getUrlModule() == 'snifsCrimeType') {
        _initNifsCrimeType({page: 0, searchQuery: {}});
        $.contextMenu({selector: '.context-menu-nifs-crime-type-selected-row', items: _loadContextMenuNifsCrimeType()});
    }

});
$(document).bind('keydown', 'f2', function () {
    _addFormNifsCrimeType({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchNifsCrimeType({elem: this});
});

function _initNifsCrimeType(param) {
    $.ajax({
        type: 'get',
        url: _nifsCrimeTypeModRootPath + 'lists',
        data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsCrimeTypeModId + '&per_page=' + param.page,
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
function _removeNifsCrimeType(param) {
    var _dialogId = 'partner-remove';
    if (!$("#" + _dialogId).length) {
        $('<div id="' + _dialogId + '"></div>').appendTo('body');
    }
    $("#" + _dialogId).empty().html(_dialogAlertDeleteMessage);
    $("#" + _dialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $("#" + _dialogId).dialog('close').remove();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $("#" + _dialogId).dialog('close').remove();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _nifsCrimeTypeModRootPath + 'delete',
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

                            _initNifsCrimeType({page: 0});
                        }
                    });
                    $("#" + _dialogId).empty().dialog('close');
                }}

        ]
    });
    $("#" + _dialogId).dialog('open');
}
function _advensedSearchNifsCrimeType(param) {
    if (!$(_nifsCrimeTypeDialogId).length) {
        $('<div id="' + _nifsCrimeTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsCrimeTypeModRootPath + 'searchForm',
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
            $(_nifsCrimeTypeDialogId).html(data.html);
            $(_nifsCrimeTypeDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsCrimeTypeDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsCrimeTypeDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initNifsCrimeType({page: 0, searchQuery: $(_nifsCrimeTypeDialogId).find('form').serialize()});
                            $(_nifsCrimeTypeDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_nifsCrimeTypeDialogId).dialog('open');
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
function _loadContextMenuNifsCrimeType() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "plus",
            callback: function () {
                _addFormNifsCrimeType({elem: this, modId: _nifsCrimeTypeModId});
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
                _editFormNifsCrimeType({elem: this, id: _tr.attr('data-id')});
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
                _removeNifsCrimeType({elem: this, modId: _nifsCrimeTypeModId, id: _tr.attr('data-id')});
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
function _addFormNifsCrimeType(param) {
    var _dialogId = 'partner-add-form';
    if (!$('#' + _dialogId).length) {
        $('<div id="' + _dialogId + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsCrimeTypeModRootPath + 'add',
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
            $('#' + _dialogId).empty().html(data.html);

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
                            var _form = $('#' + _dialogId).find('form' + _partnerFormMainId);
                            $(_form).validate({
                                errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsCrimeTypeModRootPath + 'insert',
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
                                            _initNifsCrimeType({page: 0});
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
function _editFormNifsCrimeType(param) {
    var _dialogId = 'partner-edit-form';
    if (!$('#' + _dialogId).length) {
        $('<div id="' + _dialogId + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsCrimeTypeModRootPath + 'edit',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsCrimeTypeModId, id: param.id},
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
                                    url: _nifsCrimeTypeModRootPath + 'update',
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
                                            _initNifsCrimeType({page: 0});
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