function _setUserPermissionForm(param) {
    var dialogId = '_userPermissionInitDailog';
    if (!$('#' + dialogId).length) {
        $('<div id="' + dialogId + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _permissionModRootPath + 'setPermissionForm',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, userId: param.userId, createdUserId: param.createdUserId},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('#' + dialogId).empty().html(data.html);
            $('#' + dialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $('#' + dialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $('#' + dialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _saveUserPermission();
                            $('#' + dialogId).empty().dialog('close');
                        }}
                ]
            });

            $('#' + dialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.select2').select2({
            containerCssClass: 'select-sm'
        });
        $('[data-popup="tooltip"]').tooltip({
            template: '<div class="popover bg-primary"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
        });
    });

}
function _saveUserPermission(param) {
    var _form = $(document).find('form#form-set-permission');
    $.ajax({
        type: 'post',
        url: _permissionModRootPath + 'savePermission',
        data: _form.serialize(),
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
            _initUser({page: 0, searchQuery: {}});
            $.unblockUI();
        }
    });
}
function _setPermissionControl(param) {
    var _this = $(param.elem);
    var _td = _this.parents('td');
    var _id = $(_td).attr('data-id');
    var _input = $('input[data-permission-' + _id + '="input"]');
    var _check = $('input[data-permission-' + _id + '="check"]');
    if (_this.prop('checked')) {
        $(_input).each(function (key, elem) {
            $(elem).val(1);
            $(_check[key]).val(1);
            $(_check[key]).prop('checked', true);
            $(_check[key]).parent('span').addClass('checked');
        });

    } else {
        $(_input).each(function (key, elem) {
            $(elem).val(0);
            $(_check[key]).val(0);
        });
        $(_check).each(function (key, elem) {
            var _elem = $(elem);
            _elem.prop('checked', false);
            $(_check[key]).prop('checked', false);
            $(_check[key]).parent('span').removeClass('checked');
        });
    }
}

function _removeUserPermission(param) {
    var d = new Date();
    var _dialogAlertDialogId = '#dialog' + d.getTime();
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_dialogAlertDialogId).empty().html("Та энэ хэрэглэгчийн бүх тохиргоог устгахдаа итгэлтэй байна уу?");
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
                    $("#" + _dialogAlertDialogId).empty().dialog('close');
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _permissionModRootPath + 'removeUserPermission',
                        dataType: "json",
                        data: {id: param.id},
                        success: function (data) {

                            _PNotify({status: data.status, message: data.message});
                            

                        }
                    });
                    $(_dialogAlertDialogId).dialog('close');
                }}

        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}