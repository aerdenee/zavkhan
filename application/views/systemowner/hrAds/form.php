<ul class="nav nav-tabs nav-tabs-bottom mb-0">
    <li class="nav-item"><a href="#bottom-tab1" class="nav-link active" data-toggle="tab">Үндсэн</a></li>
    <li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Медиа</a></li>
    <li class="nav-item"><a href="#bottom-tab3" class="nav-link" data-toggle="tab">Сэтгэгдэл</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="bottom-tab1">
        <?php
        echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-hr-ads', 'enctype' => 'multipart/form-data'));
        echo form_hidden('id', $row->id);
        echo form_hidden('parentId', $row->parent_id);
        echo form_hidden('modId', $row->mod_id);
        echo form_hidden('hrAdsOldPic', '');
        echo form_hidden('hrAdsPic');
        ?>

        <div class="form-group row">
            <?php echo form_label('Фото зураг', 'Фото зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-7">
                <?php
                echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
                echo '<a href="javascript:;">';
                echo '<img src="' . $row->pic . '" class="_hr-ads-image">';
                echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'hr_ads\', formId: _hrAdsFormMainId, appendHtmlClass: \'._hr-ads-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, prefix: \'hrAds\'});">';
                echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
                echo '</span>';
                echo '<span class="_user-image-upload-button">';
                echo '<div class="uploader">';

                echo form_upload(array(
                    'name' => 'hrAdsPicUpload',
                    'id' => 'hrAdsPicUpload',
                    'class' => 'pull-left file-styled',
                    'onchange' => '_imageProfileUpload({
                                        elem: this, 
                                        uploadPath: UPLOADS_CONTENT_PATH, 
                                        formId: _hrAdsFormMainId, 
                                        appendHtmlClass: \'._hr-ads-image\', 
                                        prefix: \'hrAds\'});',
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

        <div class="form-group row">
            <?php echo form_label('Салбар, хэлтэс', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-7">
                <?php
                echo $controlHrPeopleDepartmentDropdown;
                ?>
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
                    'maxlength' => '500',
                    'class' => 'form-control',
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
                        <?php echo form_radio(array('class' => 'radio', 'name' => 'isActive'), 1, ($row->is_active == 1 ? TRUE : '')); ?>
                        Нээх
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('class' => 'radio', 'name' => 'isActive'), 0, ($row->is_active == 0 ? TRUE : '')); ?>
                        Нээх
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Дотор зураг', 'Дотор зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-7">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPicInside'), 1, ($row->show_pic_inside == 1 ? TRUE : '')); ?>
                        Нээх
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPicInside'), 0, ($row->show_pic_inside == 0 ? TRUE : '')); ?>
                        Нээх
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">

            <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-7">
                <div class="input-group group-indicator">
                    <span class="input-group-append">
                        <span class="input-group-text">Бодит хандалт: <?php echo $row->click_real; ?>, Зохимол хандалт: </span>
                    </span>
                    <?php
                    echo form_input(array(
                        'name' => 'click',
                        'id' => 'click',
                        'value' => $row->click,
                        'maxlength' => '10',
                        'class' => 'form-control',
                        'required' => 'required',
                        'style' => 'max-width: 100px;'
                    ));
                    ?>
                </div>
            </div>

        </div>
        <div class="form-group row">
            <?php echo form_label('Тайлбар', 'Тайлбар', array('class' => 'control-label col-md-12', 'defined' => TRUE)); ?>
            <div class="col-md-12">
                <?php
                echo form_textarea(array(
                    'name' => 'introText',
                    'id' => 'introText',
                    'value' => $row->intro_text,
                    'rows' => 4,
                    'class' => 'form-control ckeditor'
                ));
                ?>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>

    <div class="tab-pane fade" id="bottom-tab2">
        Засварын горимд ашиглах боломжтой.
    </div>

    <div class="tab-pane fade" id="bottom-tab3">
        Засварын горимд ашиглах боломжтой.
    </div>

</div>

<?php
if (isset($contentMediaJsFile)) {
    foreach ($contentMediaJsFile as $contentMedia) {
        echo '<script src="' . $contentMedia . '" type="text/javascript" async defer></script>' . "\n";
    }
}
?>