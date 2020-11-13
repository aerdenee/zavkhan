function _nifsReportGeneral(param) {

    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});

    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsReportGeneralModRootPath + 'getReportGeneralData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_reportGeneralInitWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _nifsReportCrimeGeneral(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsReportGeneralModRootPath + 'getReportCrimeGeneralData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_nifsReportGeneralWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _nifsReportAnatomyGeneral(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsReportGeneralModRootPath + 'getReportAnatomyGeneralData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_reportGeneralInitWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _nifsReportEconomyGeneral(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsReportGeneralModRootPath + 'getReportEconomyGeneralData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_reportGeneralInitWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _nifsReportDoctorViewGeneral(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsReportGeneralModRootPath + 'getReportDoctorViewGeneralData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_reportGeneralInitWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _nifsReportForensicMedicineDateInterval(param) {
    $(_reportGeneralFormMainId).validate({errorPlacement: function () {
        }});
    if ($(_reportGeneralFormMainId).valid()) {
        $.ajax({
            type: 'get',
            url: _nifsReportGeneralModRootPath + 'getReportForensicMedicineDateIntervalData',
            data: $(_reportGeneralFormMainId).serialize() + '&moduleMenuId=' + _MODULE_MENU_ID + '&reportMenuId=' + param.reportMenuId + '&reportModId=' + param.reportModId,
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_reportGeneralInitWindowId).html(data);
            }
        }).done(function () {
            $.unblockUI();
        });
    }
}
function _reportSetControlIsClose(param) {
    var _this = $(param.elem);
    if (_this.is(":checked")) {
        $('input[name="reportIsClose"]').val(1);
    } else {
        $('input[name="reportIsClose"]').val(0);
    }

}
function _reportUpdateAllData(param) {
    $.ajax({
        type: 'post',
        url: _nifsReportGeneralModRootPath + 'updateAllData',
        data: $(_reportGeneralFormMainId).serialize(),
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_reportGeneralInitWindowId).html(data);
        }
    }).done(function () {
        $.unblockUI();
    });
}
function _reportCrimeFieldCheck(param) {
    var _this = $(param.elem);

    if (_this.attr('data-type') == 'plus') {
        _this.attr('data-type', 'minus');
        _this.find('i').attr('class', 'icon-minus2');
        $('.' + param.prefix + 'report-crime-field-check').css('display', 'inline-block');
    } else {
        _this.attr('data-type', 'plus');
        _this.find('i').attr('class', 'icon-plus2');
        $('.' + param.prefix + 'report-crime-field-check').css('display', 'none');
    }
}
function _nifsReportShowHideRow(param) {
    var _this = $(param.elem);

    if (_this.attr('class') == 'icon-plus2') {
        _this.attr('class', 'icon-minus2');
        $('.' + param.class).show();
    } else {
        _this.attr('class', 'icon-plus2');
        $('.' + param.class).hide();
    }

}
function _nifsReportCheckRow(param) {
    var _this = $(param.elem);

    if (_this.attr('class') == 'icon-plus2') {
        _this.attr('class', 'icon-minus2');
        $('.' + param.class).show();
    } else {
        _this.attr('class', 'icon-plus2');
        $('.' + param.class).hide();
    }

}

function _nifsReportGeneralDetail(param) {
    var _form = $(_reportGeneralFormMainId);

    $.ajax({
        type: 'get',
        url: _nifsReportGeneralModRootPath + 'getReportGeneralDetail',
        data: {
            inDate: $(_form.find('input#inDate')).val(),
            outDate: $(_form.find('input#outDate')).val(),
            reportIsClose: $(_form.find('input[name="reportIsClose"]')).val(),
            reportModId: $(_form.find('input[name="reportModId"]')).val(),
            reportMenuId: $(_form.find('input[name="reportMenuId"]')).val(),
            modId: param.modId,
            departmentId: param.departmentId
        },
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            if (data.status) {
                if (!$(_dialogAlertDialogId).length) {
                    $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
                }
                $(_dialogAlertDialogId).empty().html(data.html);
                $(_dialogAlertDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_dialogAlertDialogId).dialog('close').empty();
                    },
                    buttons: [
                        {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                                $(_dialogAlertDialogId).dialog('close').empty();
                            }}

                    ]
                });
                $(_dialogAlertDialogId).dialog('open');
            }
            console.log(data);
            //$(_reportGeneralInitWindowId).html(data);
        }
    }).done(function () {
        $.unblockUI();
    });
}