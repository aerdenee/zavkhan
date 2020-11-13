<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-content-media', 'enctype' => 'multipart/form-data'));
echo form_hidden('contentMediaId', $row->id);
echo form_hidden('contId', $row->cont_id);
echo form_hidden('modId', $row->mod_id);

echo form_hidden('contentMediaPic', '');
echo form_hidden('contentMediaPicMimeType', '');
echo form_hidden('contentMediaPicSize', '');
if ($row->id != 0) {
    echo form_hidden('contentMediaOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH . CROP_SMALL));
} else {
    echo form_hidden('contentMediaOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH));
}
echo form_hidden('contentMediaOldPicMimeType', $row->pic_mime_type);
echo form_hidden('contentMediaOldPicSize', $row->pic_file_size);

echo form_hidden('contentMediaAttachFile', '');
echo form_hidden('contentMediaAttachFileMimeType', '');
echo form_hidden('contentMediaAttachFileSize', '');
echo form_hidden('contentMediaOldAttachFile', $row->attach_file);
echo form_hidden('contentMediaOldAttachFileMimeType', $row->attach_file_mime_type);
echo form_hidden('contentMediaOldAttachFileSize', $row->attach_file_size);

echo form_hidden('contentMediaOrderNum', $row->order_num);
?>
<div class="clearfix margin-top-20"></div>
<div class="form-group row">
    <?php echo form_label('Медиа төрөл', 'Медиа төрөл', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php echo $controlMasterMediaTypeRadioButton; ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('name' => 'contentMediaIsActive', 'class' => 'radio'), 1, ($row->is_active == 1 ? TRUE : FALSE)); ?>
                Нээх </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('name' => 'contentMediaIsActive', 'class' => 'radio'), 0, ($row->is_active == 0 ? TRUE : FALSE)); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Фото зураг', 'Фото зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
        echo '<a href="javascript:;">';
        echo '<img src="' . $row->pic . '" class="_content-media-image">';
        echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'content_media\', formId: _contentMediaFormMainId, appendHtmlClass: \'._content-media-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, prefix: \'contentMedia\'});">';
        echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
        echo '</span>';
        echo '<span class="_user-image-upload-button">';
        echo '<div class="uploader">';
        echo form_upload(array(
            'name' => 'contentMediaPicUpload',
            'id' => 'contentMediaPicUpload',
            'class' => 'pull-left file-styled',
            'onchange' => '_imageUpload({
                elem: this, 
                uploadPath: UPLOADS_CONTENT_PATH, 
                formId: _contentMediaFormMainId, 
                appendHtmlClass: \'._content-media-image\', 
                prefix: \'contentMedia\'});',
        ));
        echo '<i class="icon-camera" style="user-select: none;"> <span class="_icon-text">зураг хуулах</span></i>';
        echo '</div>';
        echo '</span>';
        echo '</a>';
        echo '</div>';
        ?>
        <span class="help-block">Хуулах зургийн хэмжээ: <?php echo formatInBytes(UPLOAD_PROFILE_PHOTO_MAX_SIZE); ?></span>
    </div>
</div>

<span id="html-attach-file">
    <?php
    if ($row->media_type_id == 2) {
        /** MP4 * */
        echo '<div class="form-group row">';
        echo '<label for="MP4" required="required" class="control-label col-md-2 text-right" defined="1">MP4: </label>';
        echo '<div class="col-md-7">';
        echo '<div class="media no-margin-top">';
        echo '<div class="media-body">';
        echo '<div class="uploader">';
        echo '<input type="file" name="contentMediaAttachFileUpload" id="contentMediaAttachFileUpload" class="pull-left file-styled" onchange="_fileUpload({elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _contentMediaFormMainId, appendHtmlClass: \'.attach-file-html\', prefix: \'contentMedia\'});">';
        echo '<span class="filename" style="user-select: none; display:none;">Файл сонгох</span>';
        echo '<span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
        echo '</div>';
        echo '<span class="help-block">';
        echo 'Хуулах боломжтой зураг: pdf, docx, doc, ppt, pptx, xls, xlsx, gif, jpg, png, jpeg, swf, mp4,  Хуулах файлын хэмжээ: 286.10 MB ';
        echo '<span class="attach-file-html">';
        echo $row->attach_file;
        echo ' <span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'content_media\', formId: _contentMediaFormMainId, selectedId:' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, appendHtmlClass: \'.attach-file-html\', prefix: \'contentMedia\'});"><i class="fa fa-close"></i></span>';
        echo '</span>';
        echo '</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else if ($row->media_type_id == 3) {
        /** Youtube * */
        if ($row->attach_file != '') {
            echo '<div class="form-group row">';
            echo '<label for="Video url" required="required" class="control-label col-md-2 text-right" defined="1">Video url: </label>';
            echo '<div class="col-md-7">';
            echo '<input type="text" onchange="_setVideoValue({elem:this});" name="contentMediaAttachFileUpload" value="' . $row->attach_file . '" id="contentMediaAttachFileUpload" maxlength="500" class="form-control" required="required">';
            echo '</div>';
            echo '</div>';
        }
    } else if ($row->media_type_id == 4) {
        /** Document * */
        echo '<div class="form-group row">';
        echo '<label for="Document" required="required" class="control-label col-md-2 text-right" defined="1">Document: </label>';
        echo '<div class="col-md-7">';
        echo '<div class="media no-margin-top">';
        echo '<div class="media-body">';
        echo '<div class="uploader">';
        echo '<input type="file" name="contentMediaAttachFileUpload" id="contentMediaAttachFileUpload" class="pull-left file-styled" onchange="_fileUpload({elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _contentMediaFormMainId, appendHtmlClass: \'.attach-file-html\', prefix: \'contentMedia\'});">';
        echo '<span class="filename" style="user-select: none; display:none;">Файл сонгох</span>';
        echo '<span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
        echo '</div>';
        echo '<span class="help-block">';
        echo 'Хуулах боломжтой зураг: pdf, docx, doc, ppt, pptx, xls, xlsx, gif, jpg, png, jpeg, swf, mp4,  Хуулах файлын хэмжээ: 286.10 MB ';
        echo '<span class="attach-file-html">';
        echo $row->attach_file;
        echo ' <span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'content_media\', formId: _contentMediaFormMainId, selectedId:' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, appendHtmlClass: \'.attach-file-html\', prefix: \'contentMedia\')});"><i class="fa fa-close"></i></span>';
        echo '</span>';
        echo '</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
    } else if ($row->media_type_id == 5) {
        /** Facebook * */
        if ($row->attach_file != '') {
            echo '<div class="form-group row">';
            echo '<label for="Video url" required="required" class="control-label col-md-2 text-right" defined="1">Video url: </label>';
            echo '<div class="col-md-7">';
            echo '<input type="text" onchange="_setVideoValue({elem:this});" name="contentMediaAttachFileUpload" value="' . $row->attach_file . '" id="contentMediaAttachFileUpload" maxlength="500" class="form-control" required="required">';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</span>

<div class="form-group row">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'contentMediaTitle',
            'id' => 'contentMediaTitle',
            'value' => $row->title,
            'maxlength' => '500',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12">
        <?php
        echo form_textarea(array(
            'name' => 'contentMediaIntroText',
            'id' => 'contentMediaIntroText',
            'value' => $row->intro_text,
            'rows' => 4,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">

    $(function () {
        $('input[name="masterMediaType"]').on('click', function () {

            var _this = $(this);

            if (_this.val() == 2) {

                $('#html-attach-file').html(_controlFileUpload({labelText: 'MP4 видео', fileName: '<?php echo $row->attach_file; ?>'}));

            } else if (_this.val() == 3) {

                $('#html-attach-file').html(_controlInputText({labelText: 'Video url', fileName: '<?php echo $row->attach_file; ?>'}));

            } else if (_this.val() == 4) {

                $('#html-attach-file').html(_controlFileUpload({labelText: 'Document файл', fileName: '<?php echo $row->attach_file; ?>'}));

            } else if (_this.val() == 5) {

                $('#html-attach-file').html(_controlInputText({labelText: 'Video url', fileName: '<?php echo $row->attach_file; ?>'}));

            } else {

                $('#html-attach-file').empty();

            }

        });

        $('input[name="mediaType"][value="<?php echo $row->media_type_id; ?>"]').prop('checked', true).trigger('click');

    });

    function _controlFileUpload(param) {

        var _stringHtml = '<div class="form-group row">';
        _stringHtml += '<label for="' + param.labelText + '" required="required" class="control-label col-md-2 text-right" defined="1">' + param.labelText + ': </label>';
        _stringHtml += '<div class="col-md-7">';
        _stringHtml += '<div class="media no-margin-top">';
        _stringHtml += '<div class="media-body">';
        _stringHtml += '<div class="uploader">';
        _stringHtml += '<input type="file" name="contentMediaAttachFileUpload" id="contentMediaAttachFileUpload" class="pull-left file-styled" onchange="_fileUpload({elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _contentMediaFormMainId, appendHtmlClass: \'.attach-file-html\', prefix: \'contentMedia\'});">';
        _stringHtml += '<span class="filename" style="user-select: none; display:none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
        _stringHtml += '</div>';
        _stringHtml += '<span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_ALL_FILE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?> <span class="attach-file-html">' + (param.fileName != '' ? param.fileName + ' <span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'content_media\', formId: _contentMediaFormMainId, selectedId:<?php echo $row->id;?>, uploadPath: UPLOADS_CONTENT_PATH, appendHtmlClass: \'attach-file-html\', prefix: \'contentMedia\')});"><i class="fa fa-close"></i></span>' : '') + '</span></span>';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        return _stringHtml;

    }
    function _controlInputText(param) {

        var _stringHtml = '<div class="form-group row">';
        _stringHtml += '<label for="' + param.labelText + '" required="required" class="control-label col-md-2 text-right" defined="1">' + param.labelText + ': </label>';
        _stringHtml += '<div class="col-md-7">';
        _stringHtml += '<input type="text" onchange="_setVideoValue({elem:this});" name="contentMediaAttachFileUpload" value="' + param.fileName + '" id="contentMediaAttachFileUpload" maxlength="500" class="form-control" required="required">';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        return _stringHtml;

    }

    function _setVideoValue(param) {
        var _this = $(param.elem);
        $('input[name="contentMediaAttachFile"]').val(_this.val());
        $('input[name="contentMediaMimeType"]').val('');
        $('input[name="contentMediaFileSize"]').val('');
        $('input[name="contentMediaOldAttachFile"]').val('');
        $('input[name="contentMediaOldMimeType"]').val('');
        $('input[name="contentMediaOldFileSize"]').val('');

    }
</script>