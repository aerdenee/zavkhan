<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-partial', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('oldPic', ltrim($row->pic, CROP_SMALL));
echo form_hidden('pic');
?>
<div class="form-group">
    <?php echo form_label('Фото зураг', 'Фото зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-5">
        <?php
        echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
        echo '<a href="javascript:;">';
        echo '<img src="' . UPLOADS_CONTENT_PATH . $row->pic . '" class="_partial-image">';
        echo '<span class="_user-image-delete-button" onclick="_imageDelete({modId: ' . $row->mod_id . ', selectedId: ' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, dbFieldName: \'pic\', fieldName: \'pic\', oldFieldName: \'oldPic\', fileName: ($(\'input[name=pic]\').val() != \'\' ? $(\'input[name=pic]\').val() : $(\'input[name=oldPic]\').val()), isMedia: 0, formId: _partialFormMainId, appendHtmlClass: \'._partial-image\'});">';
        echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
        echo '</span>';
        echo '<span class="_user-image-upload-button">';
        echo '<div class="uploader">';
        echo form_upload(array(
            'name' => 'picUpload',
            'id' => 'picUpload',
            'class' => 'pull-left file-styled',
            'onchange' => '_imageUpload({uploadFieldName: \'picUpload\', dbFieldName: \'pic\', fieldName: \'pic\', oldFieldName: \'oldPic\', elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _partialFormMainId, appendHtmlClass: \'._partial-image\', modId: ' . $row->mod_id . ', selectedId: ' . $row->id . ', dbFieldName: \'pic\', isMedia: 0});',
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

<div class="form-group">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $row->title,
            'maxlength' => 500,
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Мэдээний дугаар', 'Мэдээний дугаар', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'partial',
            'id' => 'partial',
            'value' => $row->partial,
            'maxlength' => 500,
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '1', (intval($row->is_active) == 1 ? TRUE : '')); ?>
                Нээх </label>
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '0', (intval($row->is_active) == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_textarea(array(
            'placeholder' => 'Тайлбар бичих',
            'name' => 'introText',
            'id' => 'introText',
            'value' => $row->intro_text,
            'size' => '50',
            'rows' => 5,
            'class' => 'form-control ckeditor'
        ));
        ?>
    </div>
</div>

<?php echo form_close(); ?>