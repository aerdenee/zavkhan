/* ------------------------------------------------------------------------------
 *
 *  # Login form with validation
 *
 *  Specific JS code additions for login_validation.html page
 *
 *  Version: 1.0
 *  Latest update: Aug 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function () {
    $('input[type="text"]').keypress(function () {
        if (event.keyCode == 13) {
            _login();
        }
    });
    $('button[name="authentication"]').on('click', function () {
        _login();
    });

    // Style checkboxes and radios
    $('.form-check-input-styled').uniform();


});

function _login() {
    var _form = $(".login-form");
    $(_form).validate({
        errorPlacement: function () {
        }});
    if ($(_form).valid()) {
        $.ajax({
            type: 'post',
            url: 'systemowner/login',
            data: $(_form).serialize(),
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: '<i class="icon-spinner4 spinner"></i>',
                    fadeIn: 800,
                    timeout: 2000, //unblock after 2 seconds
                    overlayCSS: {
                        backgroundColor: '#1b2024',
                        opacity: 0.8,
                        zIndex: 1200,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        color: '#fff',
                        zIndex: 1201,
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });
            },
            success: function (data) {

                if (data.isLogin) {
                    window.location.href = 'dashboard';

                } else {
                    window.location.href = 'systemowner';
                }

                $.unblockUI();
            }
        });
    }
}
function _resetPassword() {

    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $.ajax({
        type: 'post',
        url: 'systemowner/passwordResetForm',
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
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
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $('form#password-reset');
                            //$(_form).validate({errorPlacement: function () {}});

                            $(_form).validate({
                                errorPlacement: function () {
                                },
                                rules: {
                                    'email': {
                                        required: true,
                                        email: true
                                    }
                                }
                            });

                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'systemowner/passwordReset',
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
                                        if (data.status == 'success') {
                                            $(_dialogAlertDialogId).dialog('close').empty();
                                        }
                                        _PNotify({status: data.status, message: data.message});
                                        $.unblockUI();

                                    }
                                });
                            }

                            //$(_dialogAlertDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_dialogAlertDialogId).dialog('open');
            $.unblockUI();
        }
    });

}