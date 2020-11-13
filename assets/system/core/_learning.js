$(document).ready(function () {
    var currentURL = (document.location.pathname); // returns http://myplace.com/abcd
    var part = currentURL.split("/");
    if (part[2] == 'index') {
        _initCategoryLearning({page: 0, searchQuery: {}});
    }
    
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchContent({elem: this});
});
function _initCategoryLearning(param) {
    $.ajax({
        type: 'get',
        url: _learningModRootPath + 'catLists',
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
        $.unblockUI();
    });
}
function _initLearning(param) {
    $.ajax({
        type: 'get',
        url: _learningModRootPath + 'lists',
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
        $.unblockUI();
    });
}
function _showLearning(param) {
    $.ajax({
        type: 'get',
        url: _learningModRootPath + 'show',
        data: {moduleMenuId: _MODULE_MENU_ID, selectedId: param.selectedId},
        dataType: 'json',
        beforeSend: function () {
            $('body').addClass('has-detached-left');
            $('body').find('.page-container').removeClass('page-container');
            $('body').find('.page-content').removeClass('page-content');
            window.location.href = _learningModRootPath + 'show/' + param.selectedId;
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
function _advensedSearchContent(param) {

    var _dialogId = 'contentAdvencedSearchDialog';
    if (!$('#' + _dialogId).length) {
        $('<div id="' + _dialogId + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _learningModRootPath + 'searchForm',
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
            $('#' + _dialogId).html(data.html);
            $('#' + _dialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
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

                            _initLearning({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                            $('#' + _dialogId).empty().dialog('close');
                            $.unblockUI();
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

        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initLearning({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                $('#' + _dialogId).empty().dialog('close');
            }
        });
    });

}