function _initNifsPreCrime(param) {
    $.ajax({
        type: 'get',
        url: _nifsPreCrimeModRootPath + 'lists',
        data: {modId: param.modId, contId: param.contId},
        dataType: 'json',
        success: function (data) {
            $(_nifsPreCrimeWindowId).html(data);
        }
    });
}
function _removeNifsPreCrime(param) {

    var _this = $(param.elem);
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_dialogAlertDialogId).empty().html(_dialogAlertDeleteMessage);
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
                    $(_dialogAlertDialogId).empty().dialog('close');
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    _this.parent('.token').remove();
                    $(_dialogAlertDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
function _addFormNifsPreCrime(param) {

    if (!$(_nifsPreCrimeDialogId).length) {
        $('<div id="' + _nifsPreCrimeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $.ajax({
        url: _nifsPreCrimeModRootPath + 'add',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsPreCrimeModId},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsPreCrimeDialogId).empty().html(data.html);

            $(_nifsPreCrimeDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsPreCrimeDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsPreCrimeDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_nifsPreCrimeDialogId).find('form' + _nifsPreCrimeFormMainId);

                            $(_form).validate({

                                errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $(_nifsPreCrimeWindowId).append(
                                        '<span class="token" data-key="' + $.now() + '" ondblclick="_editFormNifsPreCrime({elem:this})"><span class="token-label">' +
                                        '<input type="hidden" value="' + _form.find('#createNumber').val() + '" name="preCrimeCreateNumber[]">' + _form.find('#createNumber').val() + ' - ' +
                                        '<input type="hidden" value="' + _form.find('#expert').val() + '" name="preCrimeExpert[]">' + _form.find('#expert').val() + ', ' +
                                        '<input type="hidden" value="' + _form.find('#crimeValue').val() + '" name="preCrimeCrimeValue[]">' + _form.find('#crimeValue').val() +
                                        '</span><a href="javascript:;" onclick="_removeNifsPreCrime({elem: this})" class="close" tabindex="-1" aria-label="Remove">×</a></span>');
                                $(_nifsPreCrimeDialogId).empty().dialog('close');

                            }
                        }}
                ]
            });
            $(_nifsPreCrimeDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {

        _createNumber();

    });

}
function _editFormNifsPreCrime(param) {
    var _this = $(param.elem);
    var _key = _this.attr('data-key');

    if (!$(_nifsPreCrimeDialogId).length) {
        $('<div id="' + _nifsPreCrimeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $.ajax({
        url: _nifsPreCrimeModRootPath + 'edit',
        type: 'POST',
        dataType: 'json',
        data: {preCrimeKey: _this.find('input[name="preCrimeKey[]"]').val(), preCrimeCreateNumber: _this.find('input[name="preCrimeCreateNumber[]"]').val(), preCrimeExpert: _this.find('input[name="preCrimeExpert[]"]').val(), preCrimeCrimeValue: _this.find('input[name="preCrimeCrimeValue[]"]').val()},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsPreCrimeDialogId).html(data.html);

            $(_nifsPreCrimeDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsPreCrimeDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsPreCrimeDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            var _form = $(_nifsPreCrimeDialogId).find('form');
                            
                            var _controlKeyValue = _form.find('input[name="preCrimeKey"]').val();
                            var _controlCreateNumber = _form.find('input[name="preCrimeCreateNumber"]').val();
                            var _controlExpert = _form.find('input[name="preCrimeExpert"]').val();
                            var _controlValue = _form.find('textarea[name="preCrimeCrimeValue"]').val();
                            
                            $(_form).validate({

                                errorPlacement: function () {}});
                            if ($(_form).valid()) {
                                $(_nifsPreCrimeWindowId).find('span[data-key="' + _key + '"]').html(
                                    '<span class="token-label">' + 
                                    '<input type="hidden" value="' + _controlKeyValue + '" name="preCrimeKey[]">' +
                                    '<input type="hidden" value="' + _controlCreateNumber + '" name="preCrimeCreateNumber[]">' + _controlCreateNumber + ' - ' +
                                    '<input type="hidden" value="' + _controlExpert + '" name="preCrimeExpert[]">' + _controlExpert + ', ' +
                                    '<input type="hidden" value="' + _controlValue + '" name="preCrimeCrimeValue[]">' + _controlValue + 
                                    '</span><a href="javascript:;" onclick="_removeNifsPreCrime({elem: this})" class="close" tabindex="-1" aria-label="Remove">×</a>');
                                $(_nifsPreCrimeDialogId).empty().dialog('close');

                            }

                        }}

                ]
            });
            $(_nifsPreCrimeDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    });
}