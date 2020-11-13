
$(document).ready(function () {
    if (_getUrlModule() == 'snifsWhere') {
        _initNifsWhere({page: 0, searchQuery: {}});
        $.contextMenu({selector: '.context-menu-nifs-where-selected-row', items: _loadContextMenuNifsWhere()});
    }

});
$(document).bind('keydown', 'f2', function () {
    _addFormNifsWhere({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchNifsWhere({elem: this});
});

function _initNifsWhere(param) {
    $.ajax({
        type: 'get',
        url: _nifsWhereModRootPath + 'lists',
        data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsWhereModId + '&per_page=' + param.page,
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
function _removeNifsWhere(param) {

    if (!$(_nifsWhereDialogId).length) {
        $('<div id="' + _nifsWhereDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_nifsWhereDialogId).empty().html(_dialogAlertDeleteMessage);
    $(_nifsWhereDialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_nifsWhereDialogId).dialog('close').empty();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_nifsWhereDialogId).dialog('close').empty();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _nifsWhereModRootPath + 'delete',
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

                            _initNifsWhere({page: 0});
                        }
                    });
                    $(_nifsWhereDialogId).dialog('close').empty();
                }}

        ]
    });
    $(_nifsWhereDialogId).dialog('open');
}
function _advensedSearchNifsWhere(param) {
    
    if (!$(_nifsWhereDialogId).length) {
        $('<div id="' + _nifsWhereDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsWhereModRootPath + 'searchForm',
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
            $(_nifsWhereDialogId).html(data.html);
            $(_nifsWhereDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsWhereDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsWhereDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initNifsWhere({page: 0, searchQuery: $(_nifsWhereDialogId).find('form').serialize()});
                            $(_nifsWhereDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_nifsWhereDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();
    });

}
function _loadContextMenuNifsWhere() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "plus",
            callback: function () {
                _addFormNifsWhere({elem: this, modId: _nifsWhereModId});
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
                _editFormNifsWhere({elem: this, id: _tr.attr('data-id')});
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
                _removeNifsWhere({elem: this, modId: _nifsWhereModId, id: _tr.attr('data-id')});
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
function _addFormNifsWhere(param) {
    if (!$(_nifsWhereDialogId).length) {
        $('<div id="' + _nifsWhereDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsWhereModRootPath + 'add',
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
            $(_nifsWhereDialogId).empty().html(data.html);

            $(_nifsWhereDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 700,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsWhereDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsWhereDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            var _form = $(_nifsWhereDialogId).find('form');
                            $(_form).validate({
                                errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsWhereModRootPath + 'insert',
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
                                            _initNifsWhere({page: 0});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsWhereDialogId).dialog('close').empty();
                                });
                            }
                        }}

                ]
            });
            $(_nifsWhereDialogId).dialog('open');
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
function _editFormNifsWhere(param) {
    if (!$(_nifsWhereDialogId).length) {
        $('<div id="' + _nifsWhereDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsWhereModRootPath + 'edit',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsWhereModId, id: param.id},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsWhereDialogId).html(data.html);

            $(_nifsWhereDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 700,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsWhereDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsWhereDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_nifsWhereDialogId).find('form');
                            $(_form).validate({errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsWhereModRootPath + 'update',
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
                                            _initNifsWhere({page: 0});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsWhereDialogId).dialog('close').empty();
                                });
                            }
                        }}
                ]
            });
            $(_nifsWhereDialogId).dialog('open');
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