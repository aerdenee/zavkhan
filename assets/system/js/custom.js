// Collapsible functionality
// -------------------------

$(function () {
    $.ui.dialog.prototype._allowInteraction = function (e) {
        return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-dropdown, .picker__frame').length;
    };
});

//if ($.ui && $.ui.dialog && $.ui.dialog.prototype._allowInteraction) {
//    var ui_dialog_interaction = $.ui.dialog.prototype._allowInteraction;
//    $.ui.dialog.prototype._allowInteraction = function (e) {
//        if ($(e.target).closest('.select2-dropdown').length) return true;
//        return ui_dialog_interaction.apply(this, arguments);
//    };
//}

$(function () {

    $('.radio, .checkbox').uniform();
    $('.select2').select2();


    $.sessionTimeout({
        heading: 'h5',
        title: 'Санамж',
        message: 'Програм дээр 25 минут үйлдэл хийгээгүй тул холболт саллаа.',
        keepAliveUrl: '',
        keepAlive: true,
        keepAliveInterval: 5000,
        redirUrl: 'systemowner/logout',
        logoutUrl: 'systemowner/logout',
        warnAfter: 4800000, //25 minutes
        redirAfter: 4860000, //26 minutes
        keepBtnClass: 'btn btn-success',
        keepBtnText: 'Дахин холбогдох',
        logoutBtnClass: 'btn btn-light',
        logoutBtnText: 'Холболтыг салгах',
        ignoreUserActivity: false
    });

});

function _getDate(element) {
    var date;
    try {
        date = $.datepicker.parseDate(_dateFormat, element.value);
    } catch (error) {
        date = null;
    }

    return date;
}

function getQueryVariable(param) {
    var query = param._url;
    var vars = query.split('?');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == param._var) {
            return decodeURIComponent(pair[1]);
        }
    }
}

function _getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}

function _getUrlModule() {
    var _url = window.location.pathname;
    _url = _url.split('/');
    return _url['1'];
}

function _createNumber() {
    $('#createNumber').autoNumeric('init', {vMin: 0, vMax: 999999999999, aSep: ''});
}
function _weight() {
    $('#weight').autoNumeric('init', {vMin: 0, vMax: 99});
}

function _orderNum() {
    $('#orderNum, .order-num').autoNumeric('init', {vMin: 0, vMax: 9999999, aSep: ''});
}
function _age() {
    $('.init-control-age').autoNumeric('init', {vMin: 0.00, vMax: 120.9, aSep: '', aDec: ','});
}
//$(function () {
//    $.ui.dialog.prototype._allowInteraction = function (e) {
//        return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-dropdown').length;
//    };
//    console.log($.ui.dialog.prototype._allowInteraction);
//});

function _PNotify(param) {
    if (param.status == 'success') {
        new PNotify({
            text: param.message,
            addclass: 'stack-bottom-left bg-success',
            stack: {"dir1": "right", "dir2": "up", "push": "top"}
        });
    } else {
        new PNotify({
            text: param.message,
            addclass: 'stack-bottom-left bg-danger',
            stack: {"dir1": "right", "dir2": "up", "push": "top"}
        });
    }

}

function _linkType(param) {
    var _thisVal = $(param.elem).val();
    $('input[name="linkTypeId"]').val(_thisVal);
    if (_thisVal == '2') {
        $('#linkTypeOutput').addClass('show').removeClass('hide');
        $('#linkTypeInput').removeClass('show').addClass('hide');
    }

    if (_thisVal == '1') {
        $('#linkTypeInput').addClass('show').removeClass('hide');
        $('#linkTypeOutput').removeClass('show').addClass('hide');
    }
}

function _sendNotification(param) {
    var json = null;
    if (param.type == 'all')
        json = {"to": "/topics/all", "notification": {"title": param.title, "body": param.message, "sound": "default"}, "data": {"message": param.message}}
    else
        json = {"registration_ids": param.regnum, "notification": {"title": param.title, "body": param.message, "sound": "default"}, "data": {"message": param.message}}

    $.ajax({
        headers: {
            'Authorization': 'key=AAAAXzRBAIE:APA91bEvnetGnSdVKPtRJH3DS2jpAdlSVrgUI8lBOvVO4Z2eXinSYTeB_JfJZWexAiKuLI3q80SmCTzRNy60qpDZY1o3ZwqxdiUGNxjjs9b0RX73i5GEru24YevfmWI3KkijjEWwxMHT',
            'Content-Type': 'application/json'
        },
        type: "post",
        url: "https://android.googleapis.com/gcm/send",
        data: JSON.stringify(json),
        dataType: "json",
        async: false,
        success: function (response) {
            return response;
        },
        error: function (err) {
            return err;
        }
    });
}

function _changePassword() {

    var dialogId = '_changePasswordDialog';
    if (!$('#' + dialogId).length) {
        $('<div id="' + dialogId + '"></div>').appendTo('body');
    }

    $.ajax({
        url: _userModRootPath + 'formChangePassword',
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
            $('#' + dialogId).empty().html(data.html);

            $('#' + dialogId).dialog({
                cache: false,
                resizable: true,
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

                            var _form = $('#' + dialogId).find('form');
                            _form.validate({errorPlacement: function () {
                                }});
                            if (_form.valid()) {

                                if (_form.find('#newPassword').val() == _form.find('#confirmPassword').val()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _userModRootPath + 'changePassword',
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
                                            $('#' + dialogId).empty().dialog('close');
                                            $.unblockUI();

                                        }
                                    });

                                } else {

                                    _PNotify({status: 'success', message: 'Шинэ нууц үгийг баталгаажуулахдаа буруу бичсэн байна'});

                                }

                            }

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
        $('.select2').select2({dropdownParent: $('#' + dialogId)});

    });
}

function _dateDiffDay(param) {

    $.ajax({
        type: 'post',
        url: 'sdate/getNumWorkDay',
        data: {inDate: param.inDate, outDate: param.outDate},
        dataType: 'json',
        success: function (data) {
            $(param.initName).html(data);
        }
    });
}

function _pageDeny(param) {
    var d = new Date();
    var _dialogAlertDialogId = '#dialog' + d.getTime();
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $(_dialogAlertDialogId).empty().html('<div class="_alert"><i class="icon-alert _icon"></i><div class="_text">Та энэ үйлдлийг хийх эрхгүй байна.</div></div>');
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
        }
    });

    $(_dialogAlertDialogId).dialog('open');

}

function _deleteTableRow(param) {
    console.log(param);
}
function _initDate(param) {
    $(param.loadName).datepicker({
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: _dateFormat
    });
}

function _initPickatime(param) {
    $(param.loadName).formatter({
        pattern: '{{99}}:{{99}}'
    });
//    $(param.loadName).pickatime({
//        format: 'HH:i',
//        formatLabel: 'HH:i',
//        interval: 5,
//        min: [0, 5],
//        max: [23, 59],
//        formatSubmit: 'HH:i',
//        hiddenName: true
//    });
}

function _checkModulePermission(param) {

    var permissionData = {
        isModule: false,
        our: {create: false, read: false, update: false, delete: false},
        your: {create: false, read: false, update: false, delete: false},
        custom: {report: false, export: false, close: false}};

    $(param.data).each(function (key, row) {

        if (row.id == param.moduleMenudId) {


            if (row.isModule == 1) {
                permissionData.isModule = true;
            }

            $(row.crudOur).each(function (keyItem, rowOur) {

                if (rowOur.mode == 'create' && rowOur.status == 1) {
                    permissionData.our.create = true;
                }
                if (rowOur.mode == 'read' && rowOur.status == 1) {
                    permissionData.our.read = true;
                }
                if (rowOur.mode == 'update' && rowOur.status == 1) {
                    permissionData.our.update = true;
                }
                if (rowOur.mode == 'delete' && rowOur.status == 1) {
                    permissionData.our.delete = true;
                }
            });

            $(row.crudYour).each(function (keyItem, rowYour) {

                if (rowYour.mode == 'read' && rowYour.status == 1) {
                    permissionData.your.read = true;
                }
                if (rowYour.mode == 'update' && rowYour.status == 1) {
                    permissionData.your.update = true;
                }
                if (rowYour.mode == 'delete' && rowYour.status == 1) {
                    permissionData.your.delete = true;
                }
            });

            $(row.custom).each(function (keyItem, rowCustom) {

                if (rowCustom.mode == 'report' && rowCustom.status == 1) {
                    permissionData.custom.report = true;
                }
                if (rowCustom.mode == 'export' && rowCustom.status == 1) {
                    permissionData.custom.export = true;
                }
                if (rowCustom.mode == 'close' && rowCustom.status == 1) {
                    permissionData.custom.close = true;
                }
            });

        }
    });

    return permissionData;

}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    if (!$().stick_in_parent) {
        console.warn('Warning - sticky.min.js is not loaded.');
        return;
    }

    // Initialize
    $('.navbar-sticky').stick_in_parent();
});

