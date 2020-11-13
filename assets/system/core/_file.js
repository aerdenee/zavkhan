function _fileUpload(param) {
    console.log($.parseParams($(param.formId).serialize() + '&uploadPath=' + param.uploadPath + '&prefix=' + param.prefix));
    $(param.formId).ajaxSubmit({
        type: 'post',
        url: _fileModRootPath + 'fileUpload',
        dataType: 'json',
        data: $.parseParams($(param.formId).serialize() + '&uploadPath=' + param.uploadPath + '&prefix=' + param.prefix),
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {

            _PNotify({status: data.status, message: data.message});

            $(param.formId).find('input[name="' + param.prefix + 'AttachFile"]').val(data.fileName);
            $(param.formId).find('input[name="' + param.prefix + 'AttachFileMimeType"]').val(data.fileType);
            $(param.formId).find('input[name="' + param.prefix + 'AttachFileSize"]').val(data.fileSize);

            $(param.formId).find(param.appendHtmlClass).html(data.fileName + ' <span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'' + param.table + '\', formId: \'' + param.formId + '\', selectedId:' + param.selectedId + ', uploadPath: \'' + param.uploadPath + '\', appendHtmlClass: \'' + param.appendHtmlClass + '\', prefix: ' + param.prefix + '});"><i class="fa fa-close"></i></span>');
            $.unblockUI();
        }
    });
}

function _fileDelete(param) {
    if (!$(_fileDialogId).length) {
        $('<div id="' + _fileDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_fileDialogId).empty().html(_dialogAlertDeleteMessage);
    $(_fileDialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_fileDialogId).empty().dialog('close');
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_fileDialogId).empty().dialog('close');
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    $.ajax({
                        type: 'post',
                        url: _fileModRootPath + 'fileDelete',
                        data: {
                            fileName: param.fileName,
                            table: param.table,
                            selectedId: param.selectedId,
                            uploadPath: param.uploadPath
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
                            _PNotify({status: data.status, message: data.message});
                            $(param.formId).find('input[name="' + param.prefix + 'AttachFile"]', document).val('');
                            $(param.formId).find('input[name="' + param.prefix + 'AttachFileMimeType"]', document).val('');
                            $(param.formId).find('input[name="' + param.prefix + 'MimeType"]', document).val('');
                            $(param.formId).find('input[name="' + param.prefix + 'FileSize"]', document).val('');
                            
                            $(param.formId).find('input[name="' + param.prefix + 'OldAttachFile"]', document).val('');
                            $(param.formId).find('input[name="' + param.prefix + 'OldAttachFileMimeType"]', document).val('');
                            $(param.formId).find('input[name="' + param.prefix + 'OldMimeType"]', document).val('');
                            $(param.formId).find('input[name="' + param.prefix + 'OldFileSize"]', document).val('');
                            $(param.formId).find(param.appendHtmlClass).html('');
                            $.unblockUI();
                        }
                    });
                    $(_fileDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_fileDialogId).dialog('open');

}

