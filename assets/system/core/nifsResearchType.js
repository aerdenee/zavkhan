$(document).ready(function () {
    _initNifsResearchType({page: 0, searchQuery: {}});
    $.contextMenu({selector: '.context-menu-nifs-research-type-selected-row', items: _loadContextMenuNifsResearchType()});
});
$(document).bind('keydown', 'f2', function () {
    _addFormNifsResearchType({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchNifsResearchType({elem: this});
});

function _initNifsResearchType(param) {
    $.ajax({
        type: 'get',
        url: _nifsResearchTypeModRootPath + 'lists',
        data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsResearchTypeModId + '&per_page=' + param.page,
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
function _removeNifsResearchType(param) {
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
            $("#" + _dialogId).dialog('close').empty();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $("#" + _dialogId).dialog('close').empty();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _nifsResearchTypeModRootPath + 'delete',
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

                            _initNifsResearchType({page: 0});
                        }
                    });
                    $("#" + _dialogId).empty().dialog('close');
                }}

        ]
    });
    $("#" + _dialogId).dialog('open');
}
function _advensedSearchNifsResearchType(param) {
    if (!$(_nifsResearchTypeDialogId).length) {
        $('<div id="' + _nifsResearchTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsResearchTypeModRootPath + 'searchForm',
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
            $(_nifsResearchTypeDialogId).html(data.html);
            $(_nifsResearchTypeDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsResearchTypeDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsResearchTypeDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initNifsResearchType({page: 0, searchQuery: $(_nifsResearchTypeDialogId).find('form').serialize()});
                            $(_nifsResearchTypeDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_nifsResearchTypeDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
    });

}
function _loadContextMenuNifsResearchType() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "plus",
            callback: function () {
                _addFormNifsResearchType({elem: this, modId: _nifsResearchTypeModId});
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
                _editFormNifsResearchType({elem: this, id: _tr.attr('data-id')});
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
                _removeNifsResearchType({elem: this, modId: _nifsResearchTypeModId, id: _tr.attr('data-id')});
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
function _addFormNifsResearchType(param) {
    if (!$(_nifsResearchTypeDialogId).length) {
        $('<div id="' + _nifsResearchTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsResearchTypeModRootPath + 'add',
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
            $(_nifsResearchTypeDialogId).empty().html(data.html);

            $(_nifsResearchTypeDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsResearchTypeDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsResearchTypeDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            var _form = $(_nifsResearchTypeDialogId).find('form');
                            $(_form).validate({
                                errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsResearchTypeModRootPath + 'insert',
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
                                            _initNifsResearchType({page: 0});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsResearchTypeDialogId).empty().dialog('close');
                                });
                            }
                        }}

                ]
            });
            $(_nifsResearchTypeDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        _orderNum();
    });
}
function _editFormNifsResearchType(param) {
    if (!$(_nifsResearchTypeDialogId).length) {
        $('<div id="' + _nifsResearchTypeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsResearchTypeModRootPath + 'edit',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsResearchTypeModId, id: param.id},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsResearchTypeDialogId).html(data.html);

            $(_nifsResearchTypeDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsResearchTypeDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsResearchTypeDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_nifsResearchTypeDialogId).find('form');
                            $(_form).validate({errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsResearchTypeModRootPath + 'update',
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
                                            _initNifsResearchType({page: 0});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsResearchTypeDialogId).empty().dialog('close');
                                });
                            }
                        }}
                ]
            });
            $(_nifsResearchTypeDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        _orderNum();
    });

}