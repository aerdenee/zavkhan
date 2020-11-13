function _nifsIsMixx(param) {
    var _this = $(param.elem);
    if (_this.prop('checked')) {
        $('input[name="isMixx"]').val(1);
        $('button[name="' + param.addButtonName + '"]').attr('disabled', false);
    } else {
        if ($('#' + param.initControlHtml).html().length > 0) {
            if (!$(_dialogAlertDialogId).length) {
                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
            }

            $(_dialogAlertDialogId).empty().html('<div class="_alert _alert-delete _alert-styled-left _alert-dismissible"><span>Та шинжээчийн мэдээллийг хасахдаа итгэлтэй байна уу?</span></div>');
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
                            $('input[name="isMixx"]').val(0);
                            $('button[name="' + param.addButtonName + '"]').attr('disabled', true);
                            $('#' + param.initControlHtml).empty();
                            $(_dialogAlertDialogId).empty().dialog('close');
                        }}
                ]
            });
            $(_dialogAlertDialogId).dialog('open');
        }
        $('input[name="isMixx"]').val(0);
        $('button[name="' + param.addButtonName + '"]').attr('disabled', true);
    }
}