function _imageBigUpload(param) {

    var _imageCropDialogId = '#imageUploadDialog';
    var _oldPic = 'oldPic';
    var _pic = 'pic';

    if (param.uploadType == 'cover') {
        var _oldPic = 'oldCover';
        var _pic = 'cover';
    }
    if (!$(_imageCropDialogId).length) {
        $('<div id="' + _imageCropDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $(param.formId).ajaxSubmit({
        type: 'post',
        url: _imageModRootPath + 'imageBigUpload',
        dataType: 'json',
        data: {uploadPath: param.uploadPath, uploadType: param.uploadType},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {

            if (data.status === 'success') {

                _PNotify({status: data.status, message: data.message});

                $(param.formId).find('input[name="' + _pic + '"]', document).val(data.pic);
                $(param.formId).find(param.appendHtmlClass).attr('data-image', data.pic);

                $(param.formId).find('input[name="' + _oldPic + '"]', document).val('');
                $(param.formId).find(param.appendHtmlClass).attr('src', param.uploadPath + CROP_SMALL + data.pic);
                $.unblockUI();


            } else {
                new PNotify({
                    text: data.response,
                    addclass: 'bg-danger'
                });
            }
            $.unblockUI();
        }
    });
}

function _imageUpload(param) {
    var _imageCropDialogId = '#imageUploadDialog';
    var _html = _htmlUploadImage = '';

    if (!$(_imageCropDialogId).length) {
        $('<div id="' + _imageCropDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $(param.formId).ajaxSubmit({
        type: 'post',
        url: _imageModRootPath + 'imageUpload',
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

            if (data.status === 'success') {

                _html = '<div class="btn-group btn-group-justified demo-cropper-ratio" data-toggle="buttons">';
                _html += '<label class="btn btn-primary active" style="padding: 3px !important; margin: 0 !important; line-height:22px !important;"><input type="radio" class="sr-only" id="aspectRatio0" name="aspectRatio" value="1.7777777777777777">Хөндлөн урт</label>';
                _html += '<label class="btn btn-primary" style="padding: 3px !important; margin: 0 !important; line-height:22px !important;"><input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.3333333333333333">Хөндлөн 3/4</label>';
                _html += '<label class="btn btn-primary" style="padding: 3px !important; margin: 0 !important; line-height:22px !important;"><input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1">Дөрвөлжин</label>';
                _html += '<label class="btn btn-primary" style="padding: 3px !important; margin: 0 !important; line-height:22px !important;"><input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="0.6666666666666666">Босоо</label>';
                _html += '<label class="btn btn-primary" style="padding: 3px !important; margin: 0 !important; line-height:22px !important;"><input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="NaN">Дурын хэлбэр сонгох</label>';
                //_html += '<div class="btn btn-primary form-check form-check-inline" style="padding-bottom: 0 !important; margin-bottom: 0 !important; line-height:22px;"><label onclick="_watermark({_this:this});" class="form-check-label"><input type="checkbox" checked="true" class="checkbox"> Зураг тамгалах</label></div>';
                _html += '</div>';
                _html += '</div>';
                _html += '<input type="hidden" name="cropX">';
                _html += '<input type="hidden" name="cropY">';
                _html += '<input type="hidden" name="cropWidth">';
                _html += '<input type="hidden" name="cropHeight">';
                _html += '<div style="height: ' + data.height + 'px; width: 100%; overflow: hidden;">';
                _html += '<img src="' + param.uploadPath + data.pic + '" id="crop-main-photo" style="max-width:' + data.width + 'px;">';

                $(_imageCropDialogId).empty().html(_html);
                $(_imageCropDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: 'Хайчлах хэлбэрээ сонгоно уу',
                    width: parseFloat(data.width),
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_imageCropDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: 'Устгах', class: 'btn btn-default danger', click: function () {
                                _imageDelete({
                                    modId: 0,
                                    uploadImage: data.response,
                                    uploadPath: param.uploadPath,
                                    formId: param.formId,
                                    appendHtmlClass: param.appendHtmlClass,
                                    fieldName: param.fieldName});
                                $(_imageCropDialogId).empty().dialog('close');
                            }},
                        {text: 'Хайчлах', class: 'btn btn-primary active legitRipple', click: function () {

                                $.ajax({
                                    type: 'post',
                                    url: _imageModRootPath + 'imageCrop',
                                    data: {
                                        pic: data.pic,
                                        uploadPath: param.uploadPath,
                                        cropX: parseFloat($('input[name="cropX"]').val()),
                                        cropY: parseFloat($('input[name="cropY"]').val()),
                                        cropWidth: parseFloat($('input[name="cropWidth"]').val()),
                                        cropHeight: parseFloat($('input[name="cropHeight"]').val())},
                                    dataType: 'json',
                                    beforeSend: function () {
                                        $.blockUI({
                                            message: _jqueryBlockUiMessage,
                                            overlayCSS: _jqueryBlockUiOverlayCSS,
                                            css: _jqueryBlockUiMessageCSS
                                        });
                                    },
                                    success: function (uploadData) {

                                        _PNotify({status: data.status, message: data.message});

                                        $(param.formId).find('input[name="' + param.prefix + 'Pic"]', document).val(uploadData.pic);
                                        $(param.formId).find('input[name="' + param.prefix + 'PicMimeType"]', document).val(uploadData.fileType);
                                        $(param.formId).find('input[name="' + param.prefix + 'PicSize"]', document).val(uploadData.fileSize);

                                        $(param.formId).find('input[name="' + param.prefix + 'OldPic"]', document).val('');
                                        $(param.formId).find('input[name="' + param.prefix + 'OldPicMimeType"]', document).val('');
                                        $(param.formId).find('input[name="' + param.prefix + 'OldPicSize"]', document).val('');

                                        $(param.formId).find(param.appendHtmlClass).attr('src', param.uploadPath + CROP_SMALL + data.pic);
                                        $(_imageCropDialogId).empty().dialog('close');
                                        $.unblockUI();

                                    }
                                });
                                //$("#" + dialogId).dialog('close');
                            }}
                    ]
                });
                $(_imageCropDialogId).dialog('open');

                var _cropImage = $(_imageCropDialogId).find('#crop-main-photo');
                $(param.formId).find('input[name="pic"]', document).val(data.pic);
                _cropImage.cropper({
                    aspectRatio: 1.7777777777777777,
                    preview: '.preview',
                    crop: function (e) {
                        $('input[name="cropX"]').val(e.x);
                        $('input[name="cropY"]').val(e.y);
                        $('input[name="cropWidth"]').val(e.width);
                        $('input[name="cropHeight"]').val(e.height);
                    }});

                $('.demo-cropper-ratio').on('change', 'input[type=radio]', function () {
                    _cropImage.cropper('destroy').cropper({
                        aspectRatio: $(this).val(),
                        preview: '.preview',
                        crop: function (e) {
                            $('input[name="cropX"]').val(e.x);
                            $('input[name="cropY"]').val(e.y);
                            $('input[name="cropWidth"]').val(e.width);
                            $('input[name="cropHeight"]').val(e.height);
                        }});
                });

                $('.radio, .checkbox').uniform();

            } else {
                new PNotify({
                    text: data.response,
                    addclass: 'bg-danger'
                });
            }
            $.unblockUI();
        }
    });
}

function _imageDelete(param) {

    if (!$(_imageDialogId).length) {
        $('<div id="' + _imageDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_imageDialogId).empty().html(_dialogAlertDeleteMessage);
    $(_imageDialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_imageDialogId).empty().dialog('close');
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_imageDialogId).empty().dialog('close');
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {

                    $.ajax({
                        type: 'post',
                        url: _imageModRootPath + 'imageDelete',
                        data: $.parseParams($(param.formId).serialize() + '&uploadPath=' + param.uploadPath + '&prefix=' + param.prefix + '&table=' + param.prefix + '&selectedId=' + param.selectedId),
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

                            $(param.formId).find('input[name="' + param.prefix + 'Pic"]').val('');
                            $(param.formId).find('input[name="' + param.prefix + 'PicMimeType"]').val('');
                            $(param.formId).find('input[name="' + param.prefix + 'PicSize"]').val('');
                            
                            $(param.formId).find('input[name="' + param.prefix + 'OldPic"]').val('');
                            $(param.formId).find('input[name="' + param.prefix + 'OldPicMimeType"]').val('');
                            $(param.formId).find('input[name="' + param.prefix + 'OldPicSize"]').val('');
                            
                            $(param.formId).find(param.appendHtmlClass).attr('src', data.pic);
                            $.unblockUI();
                        }
                    });
                    $(_imageDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_imageDialogId).dialog('open');

}

function _imageProfileUpload(param) {

    var _imageCropDialogId = '#imageUploadDialog' + _DATE.getTime();
    var _html = _htmlUploadImage = '';

    if (!$(_imageCropDialogId).length) {
        $('<div id="' + _imageCropDialogId.replace('#', '') + '"></div>').appendTo('body');
    }

    $(param.formId).ajaxSubmit({
        type: 'post',
        url: _imageModRootPath + 'imageUpload',
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
            if (data.status === 'success') {
                _html = '<style>.ui-dialog .ui-dialog-content {padding: 0 !important;}</style>';
                _html += '<input type="hidden" name="aspectRatio" value="1">';
                _html += '<input type="hidden" name="isWatermark" value="0">';
                _html += '<input type="hidden" name="cropX">';
                _html += '<input type="hidden" name="cropY">';
                _html += '<input type="hidden" name="cropWidth">';
                _html += '<input type="hidden" name="cropHeight">';
                _html += '<div style="height: ' + data.height + 'px; width: 100%; overflow: hidden;">';
                _html += '<img src="' + param.uploadPath + data.pic + '" id="crop-main-photo" style="max-width:' + data.width + 'px;">';

                $(_imageCropDialogId).empty().html(_html);
                $(_imageCropDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: 'Та зураг хайчлах хэмжээгээ сонгоно уу',
                    width: parseFloat(data.width),
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_imageCropDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: 'Устгах', class: 'btn btn-default danger', click: function () {
                                _imageDelete({
                                    selectedId: param.selectedId,
                                    uploadPath: param.uploadPath,
                                    formId: param.formId,
                                    appendHtmlClass: param.appendHtmlClass,
                                    fieldName: param.fieldName});
                                $(_imageCropDialogId).empty().dialog('close');
                            }},
                        {text: 'Хайчлах', class: 'btn btn-primary active legitRipple', click: function () {

                                $.ajax({
                                    type: 'post',
                                    url: _imageModRootPath + 'imageCrop',
                                    data: {
                                        pic: data.pic,
                                        uploadPath: param.uploadPath,
                                        cropX: parseFloat($('input[name="cropX"]').val()),
                                        cropY: parseFloat($('input[name="cropY"]').val()),
                                        cropWidth: parseFloat($('input[name="cropWidth"]').val()),
                                        cropHeight: parseFloat($('input[name="cropHeight"]').val())},
                                    dataType: 'json',
                                    beforeSend: function () {
                                        $.blockUI({
                                            message: _jqueryBlockUiMessage,
                                            overlayCSS: _jqueryBlockUiOverlayCSS,
                                            css: _jqueryBlockUiMessageCSS
                                        });
                                    },
                                    success: function (uploadData) {

                                        _PNotify({status: data.status, message: data.message});

                                        var _uploadImage = data.pic;
                                        $(param.formId).find('input[name="' + param.prefix + 'Pic"]', document).val(uploadData.pic);
                                        $(param.formId).find('input[name="' + param.prefix + 'OldPic"]', document).val('');

                                        $(param.formId).find(param.appendHtmlClass).attr('src', param.uploadPath + CROP_SMALL + data.pic);
                                        $(_imageCropDialogId).empty().dialog('close');
                                        $.unblockUI();

                                        $(param.formId).find(param.appendHtmlClass).attr('src', param.uploadPath + CROP_SMALL + _uploadImage);
                                        $(param.formId).find(param.appendHtmlClass).attr('data-image', _uploadImage);
                                        $(_imageCropDialogId).empty().dialog('close');
                                        $.unblockUI();

                                    }
                                });
                                //$("#" + dialogId).dialog('close');
                            }}
                    ]
                });
                $(_imageCropDialogId).dialog('open');

                var _cropImage = $(_imageCropDialogId).find('#crop-main-photo');
                
                _cropImage.cropper({
                    aspectRatio: 1,
                    preview: '.preview',
                    crop: function (e) {
                        $('input[name="cropX"]').val(e.x);
                        $('input[name="cropY"]').val(e.y);
                        $('input[name="cropWidth"]').val(e.width);
                        $('input[name="cropHeight"]').val(e.height);
                    }});

            } else {
                new PNotify({
                    text: data.response,
                    addclass: 'bg-danger'
                });
            }
            $.unblockUI();
        }
    });
}

function _removeUploadImage(param) {
    $.ajax({
        type: 'post',
        url: _imageModRootPath + 'removeUploadImage',
        data: {
            uploadImage: param.uploadImage,
            uploadPath: param.uploadPath},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            _PNotify({status: 'success', message: data.message});
            $(param.formId).find('input[name="' + param.fieldName + '"]', document).val('');
            $(param.formId).find(param.appendHtmlClass, document).html('<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">');

            $.unblockUI();
        }
    });
}

function _removeBaseImage(param) {

    $.ajax({
        type: 'post',
        url: _imageModRootPath + 'removeBaseImage',
        data: {
            modId: param.modId,
            selectedId: param.selectedId,
            uploadPath: param.uploadPath,
            dbFieldName: param.dbFieldName,
            isMedia: param.isMedia},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {

            new PNotify({
                text: data.message,
                addclass: 'bg-success'
            });
            $(param.formId).find('input[name="' + param.oldFieldName + '"]', document).val('');
            $(param.formId).find(param.appendHtmlClass, document).html('<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">');

            $.unblockUI();
        }
    });
}

function _watermark(param) {

    var _this = $(param._this);
    var _checkBox = _this.find('input[type="checkbox"]');

    if (_checkBox.attr(':checked')) {
        $('input[name="isWatermark"]').val(1);
    } else {
        $('input[name="isWatermark"]').val(0);
    }

}