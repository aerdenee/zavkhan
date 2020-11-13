function _deleteDocFile(param) {
    var _this = $(param.elem);
    var _root = _this.parent().parent();
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
                    $.ajax({
                        type: 'post',
                        url: _docFileModRootPath + 'delete',
                        dataType: "json",
                        data: {id: _root.find('input[name="docFileId[]"]').val(), attachFile: _root.find('input[name="attachFile[]"]').val()},
                        beforeSend: function () {
                            $.blockUI({
                                message: _jqueryBlockUiMessage,
                                overlayCSS: _jqueryBlockUiOverlayCSS,
                                css: _jqueryBlockUiMessageCSS
                            });
                        },
                        success: function (data) {
                            _PNotify({status: data.status, message: data.message});
                            _root.remove();
                            $.unblockUI();
                        }
                    });
                    $(_dialogAlertDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
function _printDocFile(param) {
    var _this = $(param.elem);
    var _root = _this.parent().parent();
    $.ajax({
        type: 'post',
        url: _docFileModRootPath + 'printFile',
        dataType: "json",
        data: {selectedId: _root.find('input[name="docFileId[]"]').val(), attachFile: _root.find('input[name="attachFile[]"]').val()},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            console.log(data);
            if (data) {
                mywindow = window.open('', 'PRINT', 'height=900,width=1000');
                mywindow.document.write('<html><head><title>' + data.title + '</title></head><body style="font-family:arial;">' + data.html + '</body></html>');
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/
                mywindow.print();
                mywindow.close();
                $.unblockUI();
            }

        }
    });
}
function _showDocFile(param) {
    var _this = $(param.elem);
    var _root = _this.parent().parent();
    $.ajax({
        type: 'post',
        url: _docFileModRootPath + 'show',
        dataType: "json",
        data: {selectedId: _root.find('input[name="docFileId[]"]').val(), attachFile: _root.find('input[name="attachFile[]"]').val()},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            mywindow = window.open('', 'PRINT', 'height=900,width=1000');
            mywindow.document.write('<html><head><title>' + data.title + '</title></head><body style="font-family:arial;">' + data.html + '</body></html>');
            mywindow.focus(); // necessary for IE >= 10*/
            $.unblockUI();
        }
    });
}
function _uploadDocFile(param) {
    $(param.formId).ajaxSubmit({
        type: 'post',
        url: _fileModRootPath + 'fileUpload',
        dataType: 'json',
        data: {uploadPath: param.uploadPath},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {

            _PNotify({status: data.status, message: data.message});
            var _file = '';
            if (data.fileType == 'image/jpeg' || data.fileType == 'image/jpg') {
                _file += '<div class="_user-drop-zone">';
                _file += '<input type="hidden" name="docFileId[]" value="0">';
                _file += '<input type="hidden" name="attachFile[]" value="' + data.fileName + '">';
                _file += '<input type="hidden" name="mimeType[]" value="' + data.fileType + '">';
                _file += '<input type="hidden" name="fileSize[]" value="' + data.fileSize + '">';
                _file += '<input type="hidden" name="filePath[]" value="' + param.uploadPath + '">';
                _file += '<div class="_user-drop-zone-viewer" style="background-image: url(\'' + param.uploadPath + data.fileName + '\');" onclick="_showDocFile({elem: this});">';
                _file += '<div class="_user-drop-zone-delete-button" onclick="_deleteDocFile({elem: this});"><i class="fa fa-trash-o"></i></div>';
                _file += '</div>';
                _file += '</div>';
            } else if (data.fileType == 'application/pdf') {
                _file += '<div class="_user-drop-zone">';
                _file += '<input type="hidden" name="docFileId[]" value="0">';
                _file += '<input type="hidden" name="attachFile[]" value="' + data.fileName + '">';
                _file += '<input type="hidden" name="mimeType[]" value="' + data.fileType + '">';
                _file += '<input type="hidden" name="fileSize[]" value="' + data.fileSize + '">';
                _file += '<input type="hidden" name="filePath[]" value="' + param.uploadPath + '">';
                _file += '<div class="_user-drop-zone-viewer" style="background-image: url(\'/assets/system/img/doc/pdf.png\');" onclick="_showDocFile({elem: this});">';
                _file += '<div class="_user-drop-zone-delete-button" onclick="_deleteDocFile({elem: this});"><i class="fa fa-trash-o"></i></div>';
                _file += '</div>';
                _file += '</div>';
            } else {
                if (!$(_fileDialogId).length) {
                    $('<div id="' + _fileDialogId.replace('#', '') + '"></div>').appendTo('body');
                }
                $(_fileDialogId).empty().html('Хавсралтад PDF, JPG төрлийн файл орох боломжтой.');
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
                    }
                });
                $(_fileDialogId).dialog('open');
            }

            $(param.formId).find('.' + param.appendHtmlClass).append(_file);
            $.unblockUI();
        }
    });
}