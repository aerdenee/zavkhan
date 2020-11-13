$(function () {
    var currentURL = (document.location.pathname); // returns http://myplace.com/abcd
    var part = currentURL.split("/");
    if (part[2] == 'index') {
        _initComment({sortType: $(_commentWindowId).attr('data-sort-type')});
    }
    if (part[2] == 'show') {
        _initComment({sortType: $(_commentWindowId).attr('data-sort-type')});
    }
});
function _replyFormComment(param) {
    $.ajax({
        type: 'post',
        url: _commentModRootPath + 'replyForm',
        data: $(_commentFormMainId).serialize(),
        dataType: 'json',
        beforeSend: function () {
            _replyClose();
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('.reply-form-html-' + param.parentId).html(data.html);
        }
    }).done(function () {
        $('.maxlength-textarea').maxlength({
            alwaysShow: true
        });
        $(_commentFormMainId).find('input[name="replyComment"]').val('');
        $(_commentFormMainId).find('textarea[name="comment"]').val('');
        $(_commentFormMainId).find('input[name="parentId"]').val(param.parentId);
        $.unblockUI();
    });
}
function _replySaveComment(param) {

    var _replyComment = $(param.elem).parents('.reply-form-close').find('textarea[name="reply"]');
    $(_commentFormMainId).find('input[name="replyComment"]').val(_replyComment.val());
    _saveComment();

}
function _replyClose(param) {
    $(_rootContainerId).find('.reply-form-close').empty();
}
function _saveComment(param) {
    $.ajax({
        type: 'post',
        url: _commentModRootPath + 'insert',
        data: $('body').find(_commentFormMainId).serialize(),
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
            $('body').find(_commentFormMainId).find('textarea[name="comment"]').val('');
            $('body').find(_commentFormMainId).find('input[name="replyComment"]').val('');
            _initComment();
        }
    }).done(function (data) {
        $.unblockUI();

    });
}
function _initComment(param) {
    $.ajax({
        type: 'get',
        url: _commentModRootPath + 'lists',
        data: {modId: $(_commentWindowId).attr('data-mod-id'), contId: $(_commentWindowId).attr('data-cont-id'), sortType: $(_commentWindowId).attr('data-sort-type')},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('#window-comment-list').html(data.lists);
            $('#window-comment-form').html(data.form);
            $(_rootContainerId).find('.comment-count-html').html(data.count);
        }
    }).done(function () {
        $(_commentFormMainId).find('textarea[name="comment"]').val('');
        $('.maxlength-textarea').maxlength({
            alwaysShow: true
        });
        $.unblockUI();
    });
}
function _sortByComment(param) {
    $(_commentWindowId).attr('data-sort-type', $(param.elem).val());
    _initComment();
}
function _deleteComment(param) {
    if (!$(_commentDialogId).length) {
        $('<div id="' + _commentDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_commentDialogId).empty().html(_dialogAlertDeleteMessage);
    $(_commentDialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_commentDialogId).empty().dialog('close');
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_commentDialogId).empty().dialog('close');
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _commentModRootPath + 'delete',
                        dataType: "json",
                        data: {id: param.id, modId: param.modId, contId: param.contId},
                        success: function (data) {
                            _PNotify({status: data.status, message: data.message});
                            _initComment({page: 0});
                        }
                    });
                    $(_commentDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_commentDialogId).dialog('open');
}