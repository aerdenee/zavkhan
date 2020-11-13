function _initReaction(param) {
    $.ajax({
        type: 'post',
        url: _reactionModRootPath + 'lists',
        data: {modId: param.modId, contId: param.contId},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('.' + param.initHtml).html(data);
        }
    }).done(function () {
        $.unblockUI();
    });
}