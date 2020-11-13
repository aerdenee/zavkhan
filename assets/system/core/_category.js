var _dgNifsCrime = '';
var _getCategoryUrlModule = _getUrlModule();
var _permissionCategory = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getCategoryUrlModule == 'scategory') {
        _initCategory({page: 0, searchQuery: {}});
        
    }
});
$(document).bind('keydown', 'f2', function () {
    if (_getCategoryUrlModule == 'scategory') {
        _addFormCategory({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getCategoryUrlModule == 'scategory') {
        _advensedSearchCategory({elem: this});
    }
});
function _initCategory(param) {
    $.ajax({
        type: 'get',
        url: _categoryModRootPath + 'lists',
        data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&per_page=' + param.page,
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
        $('.select2').select2();
        $('#departmentId').on('change', function () {
            _initCategory({page: 0, searchQuery: $(_rootContainerId).find(_categoryFormMainId + '-init').serialize()});
        });
        $.unblockUI();
        $.contextMenu({selector: '.context-menu-category-selected-row', items: _loadContextMenuCategory()});
    });
}
function _deleteCategory(param) {
    if (!$(_categoryDialogId).length) {
        $('<div id="' + _categoryDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_categoryDialogId).empty().html(_dialogAlertDeleteMessage);
    $(_categoryDialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_categoryDialogId).empty().dialog('close');
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_categoryDialogId).empty().dialog('close');
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _categoryModRootPath + 'delete',
                        dataType: "json",
                        data: {id: param.id},
                        success: function (data) {
                            _PNotify({status: data.status, message: data.message});
                            _initCategory({page: 0});
                        }
                    });
                    $(_categoryDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_categoryDialogId).dialog('open');
}
function _advensedSearchCategory(param) {
    if (!$(_categoryDialogId).length) {
        $('<div id="' + _categoryDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _categoryModRootPath + 'searchForm',
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
            $(_categoryDialogId).html(data.html);
            $(_categoryDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: 108,
                modal: true,
                close: function () {
                    $(_categoryDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_categoryDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            _initCategory({page: 0, searchQuery: $(_nifsCrimeDialogId).find(_categoryDialogId + '-search').serialize()});
                            $(_categoryDialogId).empty().dialog('close');

                        }}

                ]
            });
            $(_categoryDialogId).dialog('open');
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
                _initCategory({page: 0, searchQuery: $(_categoryDialogId).find(_categoryFormMainId + '-search').serialize()});
                $(_categoryDialogId).empty().dialog('close');
            }
        });
    });

}
function _loadContextMenuCategory() {
    return {
        "add": {
            name: "Шинэ бүртгэл",
            icon: "add",
            callback: function () {
                _addFormCategory({elem: this});
            }
        },
        "edit": {
            name: "Засварлах",
            icon: "edit",
            callback: function () {
                var _tr = $(this).parents('tr');
                _editFormCategory({elem: this, id: _tr.attr('data-id')});
            }
        },
        "separator1": '---------',
        "delete": {
            name: "Устгах",
            icon: "delete",
            callback: function () {
                var _tr = $(this).parents('tr');
                _deleteCategory({elem: this, id: _tr.attr('data-id')});
            }
        }
    }
}
function _addFormCategory(param) {

    if (!$(_categoryDialogId).length) {
        $('<div id="' + _categoryDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $.ajax({
        url: _categoryModRootPath + 'add',
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
            $(_categoryDialogId).empty().html(data.html);
            $(_categoryDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                show: {
                    effect: 'fade',
                    duration: 500
                },
                hide: {
                    effect: 'fade',
                    duration: 500
                },
                close: function () {
                    $(_categoryDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_categoryDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            $('textarea[name="introText"]').val(CKEDITOR.instances.introText.getData());

                            var _form = $(_categoryDialogId).find('form');
                            $(_form).validate({errorPlacement: function () {
                                }});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _categoryModRootPath + 'insert',
                                    data: $(_form).serialize(),
                                    dataType: 'json',
                                    beforeSend: function () {
                                        $.blockUI({message: null});
                                    },
                                    success: function (data) {
                                        _PNotify({status: data.status, message: data.message});
                                        _initCategory({page: 0, searchQuery: {}});
                                        $(_categoryDialogId).empty().dialog('close');
                                        $.unblockUI();
                                    }
                                });
                            }
                        }}
                ]
            });
            $(_categoryDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        $('.init-date').pickadate({
            labelMonthNext: _globalDatePickerNextMonth,
            labelMonthPrev: _globalDatePickerPrevMonth,
            labelMonthSelect: _globalDatePickerChooseMonth,
            labelYearSelect: _globalDatePickerChooseYear,
            selectMonths: true,
            selectYears: true,
            monthsFull: _globalDatePickerListMonth,
            weekdaysShort: _globalDatePickerListWeekDayShort,
            today: _globalDatePickerChooseToday,
            clear: _globalDatePickerChooseClear,
            close: _globalDatePickerChooseClose,
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd'
        });

        $('.pickatime-limits').pickatime({
            min: [7, 30],
            max: [14, 0],
            formatSubmit: 'HH:i',
            hiddenName: true
        });
        CKEDITOR.replace('introText');

    });
}
function _editFormCategory(param) {

    if (!$(_categoryDialogId).length) {
        $('<div id="' + _categoryDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _categoryModRootPath + 'edit',
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
            $(_categoryDialogId).empty().html(data.html);
            $(_categoryDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_categoryDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_categoryDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            $('textarea[name="introText"]').val(CKEDITOR.instances.introText.getData());

                            $(_categoryFormMainId).validate({errorPlacement: function () {
                                }});
                            if ($(_categoryFormMainId).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _categoryModRootPath + 'update',
                                    data: $(_categoryFormMainId).serialize(),
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
                                        _initCategory({page: 0, searchQuery: {}});
                                        $(_categoryDialogId).empty().dialog('close');
                                    }
                                });
                            }

                        }}
                ]
            });
            $(_categoryDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        $('.init-date').pickadate({
            labelMonthNext: _globalDatePickerNextMonth,
            labelMonthPrev: _globalDatePickerPrevMonth,
            labelMonthSelect: _globalDatePickerChooseMonth,
            labelYearSelect: _globalDatePickerChooseYear,
            selectMonths: true,
            selectYears: true,
            monthsFull: _globalDatePickerListMonth,
            weekdaysShort: _globalDatePickerListWeekDayShort,
            today: _globalDatePickerChooseToday,
            clear: _globalDatePickerChooseClear,
            close: _globalDatePickerChooseClose,
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd'
        });

        $('.pickatime-limits').pickatime({
            min: [7, 30],
            max: [14, 0],
            formatSubmit: 'HH:i',
            hiddenName: true
        });
        CKEDITOR.replace('introText');
    });

}
function _readFormCategory(param) {

    if (!$(_categoryDialogId).length) {
        $('<div id="' + _categoryDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _categoryModRootPath + 'read',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, id: param.id},
        beforeSend: function () {
            $.blockUI({message: null});
        },
        success: function (data) {
            $(_categoryDialogId).html(data.html);
            $(_categoryDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 1000,
                height: "auto",
                modal: true,
                close: function () {
                    $(_categoryDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn  btn-default btn-xs', click: function () {
                            $(_categoryDialogId).empty().dialog('close');
                        }}
                ]
            });
            $(_categoryDialogId).dialog('open');
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