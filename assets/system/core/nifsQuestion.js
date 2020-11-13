$(document).ready(function () {
    if (_getUrlModule() == 'snifsQuestion') {
        _initNifsQuestion({page: 0, searchQuery: {}});
        $.contextMenu({selector: '.context-menu-nifs-question-selected-row', items: _loadContextMenuNifsQuestion()});
    }

});
$(document).bind('keydown', 'f2', function () {
    _addFormNifsQuestion({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchNifsQuestion({elem: this});
});

function _initNifsQuestion(param) {
    $.ajax({
        type: 'get',
        url: _nifsQuestionModRootPath + 'lists',
        data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&modId=' + _nifsQuestionModId + '&per_page=' + param.page,
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
function _removeNifsQuestion(param) {
    if (!$(_nifsQuestionDialogId).length) {
        $('<div id="' + _nifsQuestionDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_nifsQuestionDialogId).empty().html(_dialogAlertDeleteMessage);
    $(_nifsQuestionDialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_nifsQuestionDialogId).dialog('close').empty();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_nifsQuestionDialogId).dialog('close').empty();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _nifsQuestionModRootPath + 'delete',
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

                            _initNifsQuestion({page: 0});
                        }
                    });
                    $(_nifsQuestionDialogId).empty().dialog('close');
                }}

        ]
    });
    $(_nifsQuestionDialogId).dialog('open');
}
function _advensedSearchNifsQuestion(param) {
    if (!$(_nifsQuestionDialogId).length) {
        $('<div id="' + _nifsQuestionDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsQuestionModRootPath + 'searchForm',
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
            $(_nifsQuestionDialogId).html(data.html);
            $(_nifsQuestionDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsQuestionDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsQuestionDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initNifsQuestion({page: 0, searchQuery: $(_nifsQuestionDialogId).find('form').serialize()});
                            $(_nifsQuestionDialogId).empty().dialog('close');
                        }}

                ]
            });
            $(_nifsQuestionDialogId).dialog('open');
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
                _initNifsQuestion({page: 0, searchQuery: $(_nifsQuestionDialogId).find('form').serialize()});
                $(_nifsQuestionDialogId).empty().dialog('close');
            }
        });
    });

}
function _loadContextMenuNifsQuestion() {
    return {
        "add": {
            name: "Нэмэх (F2)",
            icon: "plus",
            callback: function () {
                _addFormNifsQuestion({elem: this, modId: _nifsQuestionModId});
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
                _editFormNifsQuestion({elem: this, id: _tr.attr('data-id')});
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
                _removeNifsQuestion({elem: this, modId: _nifsQuestionModId, id: _tr.attr('data-id')});
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
function _addFormNifsQuestion(param) {
    if (!$(_nifsQuestionDialogId).length) {
        $('<div id="' + _nifsQuestionDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsQuestionModRootPath + 'add',
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
            $(_nifsQuestionDialogId).empty().html(data.html);

            $(_nifsQuestionDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 800,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsQuestionDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsQuestionDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            var _form = $(_nifsQuestionDialogId).find('form');
                            $(_form).validate({
                                errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsQuestionModRootPath + 'insert',
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
                                            _initNifsQuestion({page: 0});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsQuestionDialogId).empty().dialog('close');
                                });
                            }
                        }}

                ]
            });
            $(_nifsQuestionDialogId).dialog('open');
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
function _editFormNifsQuestion(param) {
    if (!$(_nifsQuestionDialogId).length) {
        $('<div id="' + _nifsQuestionDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsQuestionModRootPath + 'edit',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsQuestionModId, id: param.id},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsQuestionDialogId).html(data.html);

            $(_nifsQuestionDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 800,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsQuestionDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsQuestionDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_nifsQuestionDialogId).find('form');
                            $(_form).validate({errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _nifsQuestionModRootPath + 'update',
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
                                            _initNifsQuestion({page: 0});
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                addclass: 'bg-danger'
                                            });
                                        }
                                        $.unblockUI();
                                    }
                                }).done(function () {
                                    $(_nifsQuestionDialogId).empty().dialog('close');
                                });
                            }
                        }}
                ]
            });
            $(_nifsQuestionDialogId).dialog('open');
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
function _addControlNifsQuestion(param) {
    var _notSelectedId = $(param.elem).parents('.input-group').find('select').val();
    if (_notSelectedId != 0) {
        $.ajax({
            type: 'post',
            url: _nifsQuestionModRootPath + 'controlNifsQuestionMultipleDropDown',
            data: {modId: param.modId, contId: param.contId, catId: param.catId, isDeleteButton: 1, initControlHtml: param.initControlHtml},
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

        });
    } else {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html("Та асуулт сонгоогүй байна");
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
function _removeControlNifsQuestion(param) {

    var _this = $(param.elem);
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_dialogAlertDialogId).empty().html("Та асуулт хасахдаа итгэлтэй байна уу?");
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
                    _this.parents('[data-question-row="question-row"]').remove();
                    $(_dialogAlertDialogId).dialog('close').empty();
                }}

        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
