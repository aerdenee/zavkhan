<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-category', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('parentId', $row->parent_id);
echo form_hidden('modId', $row->mod_id);

echo form_hidden('categoryPic', '');
if ($row->id != 0) {
    echo form_hidden('categoryOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH . CROP_SMALL));
} else {
    echo form_hidden('categoryOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH));
}

?>
<div class="form-group row">
    <?php echo form_label('Фото зураг', 'Фото зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php
        echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
        echo '<a href="javascript:;">';
        echo '<img src="' . $row->pic . '" class="_category-image">';
        echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'category\', formId: _categoryFormMainId, appendHtmlClass: \'._category-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, prefix: \'category\'});">';
        echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
        echo '</span>';
        echo '<span class="_user-image-upload-button">';
        echo '<div class="uploader">';
        echo form_upload(array(
            'name' => 'categoryPicUpload',
            'id' => 'categoryPicUpload',
            'class' => 'pull-left file-styled',
            'onchange' => '_imageUpload({
                                        elem: this, 
                                        uploadPath: UPLOADS_CONTENT_PATH, 
                                        formId: _categoryFormMainId, 
                                        appendHtmlClass: \'._category-image\', 
                                        prefix: \'category\'});',
        ));
        echo '<i class="icon-camera" style="user-select: none;"> <span class="_icon-text">Зураг хуулах</span></i>';
        echo '</div>';
        echo '</span>';
        echo '</a>';
        echo '</div>';
        ?>
        <span class="help-block">Хуулах зургийн хэмжээ: <?php echo formatInBytes(UPLOAD_PROFILE_PHOTO_MAX_SIZE); ?></span>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Зураг харуулах', 'Зураг харуулах', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('id' => 'showPic', 'name' => 'showPic', 'class' => 'radio'), '1', (intval($row->show_pic) == 1 ? TRUE : '')); ?>
                Нээх </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('id' => 'showPic', 'name' => 'showPic', 'class' => 'radio'), '0', (intval($row->show_pic) == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php echo $controlCategoryParentMultiRowDropdown; ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
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
<div class="form-group row">
    <?php echo form_label('Class name', 'Class name', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">        
        <?php
        echo form_input(array(
            'name' => 'class',
            'id' => 'class',
            'value' => $row->class,
            'maxlength' => '250',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <?php
        echo form_input(array(
            'name' => 'orderNum',
            'id' => 'orderNum',
            'value' => $row->order_num,
            'maxlength' => '10',
            'class' => 'form-control order-num',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '1', (intval($row->is_active) == 1 ? TRUE : '')); ?>
                Нээх </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '0', (intval($row->is_active) == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Theme layout', 'Theme layout', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php echo $controlThemeLayoutRadio; ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-md-12', 'defined' => FALSE)); ?>
    <div class="col-md-12">
        <?php
        echo form_textarea(array(
            'placeholder' => 'Тайлбар бичих',
            'name' => 'introText',
            'id' => 'introText',
            'value' => $row->intro_text,
            'size' => '50',
            'rows' => 5,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>

<?php echo form_close(); ?>