function _themeInitComment(param) {
    $.ajax({
        type: 'post',
        url: 'tcomment/lists',
        data: {modId: param.modId, contId: param.contId, sortType: param.sortType},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('._theme-comment-count').html(data.commentCount);
            $('.' + param.initHtml).html(data.html);
        }
    }).done(function () {
        $('.maxlength-textarea').maxlength({
            alwaysShow: true
        });
        $.unblockUI();
    });
}

function _themeSaveComment(param) {
    var _form = $(param.elem).parents('form');
    $(_form).validate({errorPlacement: function () {
        }});
    if ($(_form).valid()) {
        $.ajax({
            type: 'post',
            url: 'tcomment/insert',
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
                if (data.status == 'success') {
                    _themeInitComment({modId: _form.find('input[name="modId"]').val(), contId: _form.find('input[name="contId"]').val(), sortType: 'DESC', initHtml: 'themeInitCommentHtml'});
                }
            }
        }).done(function () {
            _form.find('input[name="title"]').val('Зочин');
            _form.find('textarea[name="comment"]').val('');
            $.unblockUI();
        });
    }

}