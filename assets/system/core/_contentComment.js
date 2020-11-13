function _initContentComment(param) {

    $.ajax({
        type: 'get',
        url: '/scontentComment/lists',
        data: {modId: $(_contentCommentWindowId).attr('data-mod-id'), contId: $(_contentCommentWindowId).attr('data-cont-id'), sortType: $(_contentCommentWindowId).attr('data-sort-type')},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_contentCommentWindowId).html(data);
        }
    }).done(function () {
        $.unblockUI();
    });
}

function _deleteContentComment(param) {
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
            $(_dialogAlertDialogId).dialog('close').empty();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_dialogAlertDialogId).dialog('close').empty();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: '/scontentComment/delete',
                        dataType: "json",
                        data: {id: param.id},
                        success: function (data) {
                            _PNotify({status: data.status, message: data.message});
                            _initContentComment();
                        }
                    });
                    $(_dialogAlertDialogId).dialog('close').empty();
                }}

        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}