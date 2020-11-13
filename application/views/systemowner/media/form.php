<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-media', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('mediaPic', '');
if ($row->id != 0) {
    echo form_hidden('mediaOldPic', ltrim($row->pic, UPLOADS_MEDIA_PATH . CROP_SMALL));
} else {
    echo form_hidden('mediaOldPic', ltrim($row->pic, UPLOADS_MEDIA_PATH));
}
echo form_hidden('mediaAttachFile', '');
echo form_hidden('mediaOldAttachFile', $row->attach_file);

echo form_hidden('orderNum', $row->order_num);
?>
<div class="form-group row">
    <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php echo $controlCategoryListDropdown; ?>
    </div>
</div>
<?php if (IS_MULTIPLE_PARTNER) { ?>
    <div class="form-group row">
        <?php echo form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
        <div class="col-md-7">
            <?php
            echo $controlPartnerDropdown;
            ?>
        </div>
    </div>
    <?php
} else {
    echo form_hidden('partnerId', $row->partner_id);
}
?>

<div class="form-group row">
    <?php echo form_label('Медиа төрөл', 'Медиа төрөл', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php echo $controlMasterMediaTypeRadioButton; ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('name' => 'isActive', 'class' => 'radio'), 1, ($row->is_active == 1 ? TRUE : FALSE)); ?>
                Нээх </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('name' => 'isActive', 'class' => 'radio'), 0, ($row->is_active == 0 ? TRUE : FALSE)); ?>
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
        echo '<img src="' . $row->pic . '" class="_media-image">';
        echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'media\', formId: _mediaFormMainId, appendHtmlClass: \'._media-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_MEDIA_PATH, prefix: \'media\'});">';
        echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
        echo '</span>';
        echo '<span class="_user-image-upload-button">';
        echo '<div class="uploader">';
        echo form_upload(array(
            'name' => 'mediaPicUpload',
            'id' => 'mediaPicUpload',
            'class' => 'pull-left file-styled',
            'onchange' => '_imageUpload({
                elem: this, 
                uploadPath: UPLOADS_MEDIA_PATH, 
                formId: _mediaFormMainId, 
                appendHtmlClass: \'._media-image\', 
                prefix: \'media\'});',
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
        echo '<input type="file" name="mediaAttachFileUpload" id="mediaAttachFileUpload" class="pull-left file-styled" onchange="_fileUpload({elem: this, uploadPath: UPLOADS_MEDIA_PATH, formId: _mediaFormMainId, appendHtmlClass: \'.attach-file-html\', prefix: \'media\'});">';
        echo '<span class="filename" style="user-select: none; display:none;">Файл сонгох</span>';
        echo '<span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
        echo '</div>';
        echo '<span class="help-block">';
        echo 'Хуулах боломжтой зураг: pdf, docx, doc, ppt, pptx, xls, xlsx, gif, jpg, png, jpeg, swf, mp4,  Хуулах файлын хэмжээ: 286.10 MB ';
        echo '<span class="attach-file-html">';
        echo $row->attach_file;
        echo ' <span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'media\', formId: _mediaFormMainId, selectedId:' . $row->id . ', uploadPath: UPLOADS_MEDIA_PATH, appendHtmlClass: \'.attach-file-html\', prefix: \'media\'});"><i class="fa fa-close"></i></span>';
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
            echo '<input type="text" onchange="_setVideoValue({elem:this});" name="mediaAttachFileUpload" value="' . $row->attach_file . '" id="mediaAttachFileUpload" maxlength="500" class="form-control" required="required">';
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
        echo '<input type="file" name="mediaAttachFileUpload" id="mediaAttachFileUpload" class="pull-left file-styled" onchange="_fileUpload({elem: this, uploadPath: UPLOADS_MEDIA_PATH, formId: _mediaFormMainId, appendHtmlClass: \'.attach-file-html\', prefix: \'media\'});">';
        echo '<span class="filename" style="user-select: none; display:none;">Файл сонгох</span>';
        echo '<span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
        echo '</div>';
        echo '<span class="help-block">';
        echo 'Хуулах боломжтой зураг: pdf, docx, doc, ppt, pptx, xls, xlsx, gif, jpg, png, jpeg, swf, mp4,  Хуулах файлын хэмжээ: 286.10 MB ';
        echo '<span class="attach-file-html">';
        echo $row->attach_file;
        echo ' <span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'media\', formId: _mediaFormMainId, selectedId:' . $row->id . ', uploadPath: UPLOADS_MEDIA_PATH, appendHtmlClass: \'.attach-file-html\', prefix: \'media\')});"><i class="fa fa-close"></i></span>';
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
            echo '<input type="text" onchange="_setVideoValue({elem:this});" name="mediaAttachFileUpload" value="' . $row->attach_file . '" id="mediaAttachFileUpload" maxlength="500" class="form-control" required="required">';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</span>

<div class="form-group row">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $row->title,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Гарчиг тайлбар', 'Гарчиг тайлбар', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php
        echo form_input(array(
            'name' => 'linkTitle',
            'id' => 'linkTitle',
            'value' => $row->link_title,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Вэб хаяг (url)', 'Вэб хаяг (url)', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php
        echo form_input(array(
            'name' => 'url',
            'id' => 'url',
            'value' => $row->url,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Солигдох хугацаа', 'Солигдох хугацаа', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php
        echo form_input(array(
            'name' => 'duration',
            'id' => 'duration',
            'value' => $row->duration,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Үнэ', 'Үнэ', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php
        echo form_input(array(
            'name' => 'price',
            'id' => 'price',
            'value' => $row->price,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Нийтлэх огноо', 'Нийтлэх огноо', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php $isActiveDate = explode(' ', $row->is_active_date); ?>

        <div style="width: 120px; float: left;">
            <div class="input-group">
                <?php
                echo form_input(array(
                    'name' => 'isActiveDate',
                    'id' => 'isActiveDate',
                    'value' => date('Y-m-d', strtotime($isActiveDate['0'])),
                    'maxlength' => '10',
                    'class' => 'form-control init-date',
                    'required' => 'required',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>
        <div style="width: 120px; float:left; margin-left: 20px;">
            <div class="input-group">
                <input type="text" class="form-control pickatime-limits" placeholder="Try me&hellip;">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-alarm"></i></span>
                </span>
            </div>
        </div>

    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Хаяг дуудах төлөв', 'Хаяг дуудах төлөв', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'target'), '_parent', ($row->target == '_parent' ? TRUE : '')); ?>
                Энэ цонхонд
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'target'), '_blank', ($row->target == '_blank' ? TRUE : '')); ?>
                Шинэ цонхонд
            </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_textarea(array(
            'name' => 'description',
            'id' => 'description',
            'value' => $row->description,
            'rows' => 4,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Бусад', 'Бусад', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_textarea(array(
            'name' => 'custom',
            'id' => 'custom',
            'value' => $row->custom,
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
        _stringHtml += '<input type="file" name="mediaAttachFileUpload" id="mediaAttachFileUpload" class="pull-left file-styled" onchange="_fileUpload({elem: this, uploadPath: UPLOADS_MEDIA_PATH, formId: _mediaFormMainId, appendHtmlClass: \'.attach-file-html\', prefix: \'media\'});">';
        _stringHtml += '<span class="filename" style="user-select: none; display:none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
        _stringHtml += '</div>';
        _stringHtml += '<span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_ALL_FILE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?> <span class="attach-file-html">' + (param.fileName != '' ? param.fileName + ' <span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'media\', formId: _mediaFormMainId, selectedId:<?php echo $row->id;?>, uploadPath: UPLOADS_MEDIA_PATH, appendHtmlClass: \'attach-file-html\', prefix: \'media\')});"><i class="fa fa-close"></i></span>' : '') + '</span></span>';
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
        _stringHtml += '<input type="text" onchange="_setVideoValue({elem:this});" name="mediaAttachFileUpload" value="' + param.fileName + '" id="mediaAttachFileUpload" maxlength="500" class="form-control" required="required">';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        return _stringHtml;

    }

    function _setVideoValue(param) {
        var _this = $(param.elem);
        $('input[name="mediaAttachFile"]').val(_this.val());
        $('input[name="mediaMimeType"]').val('');
        $('input[name="mediaFileSize"]').val('');
        $('input[name="mediaOldAttachFile"]').val('');
        $('input[name="mediaOldMimeType"]').val('');
        $('input[name="mediaOldFileSize"]').val('');

    }
</script>