<style type="text/css">
    .nav-tabs-vertical > .nav-tabs {
        width: 100px;
    }
</style>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tab-content-language-<?php echo $this->session->adminDefaultLanguage->code; ?>" data-toggle="tab"><?php echo $this->session->adminDefaultLanguage->title; ?></a></li>
        <?php
        if ($this->session->adminTranslateLanguage) {
            foreach ($this->session->adminTranslateLanguage as $langKey => $langRow) {
                echo '<li><a href="#tab-content-language-' . $langRow->code . '" data-toggle="tab">' . $langRow->title . '</a></li>';
            }
        }
        ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab-content-language-<?php echo $this->session->adminDefaultLanguage->code; ?>">

            <div class="tabbable nav-tabs-vertical nav-tabs-right">
                <div class="tab-content">
                    <div class="tab-pane has-padding active" id="right-content-language-<?php echo $this->session->adminDefaultLanguage->code; ?>">

                        <?php
                        echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-hr-ads', 'enctype' => 'multipart/form-data'));

                        echo form_hidden('id', $row->id);
                        echo form_hidden('parentId', $row->parent_id);
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
                                echo '<img src="' . UPLOADS_CONTENT_PATH . $row->pic . '" class="_profile-image">';
                                echo '<span class="_user-image-delete-button" onclick="_imageDelete({modId: ' . $row->mod_id . ', selectedId: ' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, dbFieldName: \'pic\', fieldName: \'pic\', oldFieldName: \'oldPic\', fileName:  ($(\'input[name=pic]\').val() != \'\' ? $(\'input[name=pic]\').val() : $(\'input[name=oldPic]\').val()), isDefaultImage: $(\'input[name=isDefaultImage]\').val(), isMedia: 0, formId: _hrAdsFormMainId, appendHtmlClass: \'._profile-image\'});">';
                                echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
                                echo '</span>';
                                echo '<span class="_user-image-upload-button">';
                                echo '<div class="uploader">';
                                echo form_upload(array(
                                    'name' => 'picUpload',
                                    'id' => 'picUpload',
                                    'class' => 'pull-left file-styled',
                                    'onchange' => '_imageUpload({uploadFieldName: \'picUpload\', dbFieldName: \'pic\', fieldName: \'pic\', oldFieldName: \'oldPic\', elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _hrAdsFormMainId, appendHtmlClass: \'._profile-image\', modId: ' . $row->mod_id . ', selectedId: ' . $row->id . ', dbFieldName: \'pic\', isMedia: 0});',
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
                            <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-lg-5">
                                <?php echo $controlCategoryListDropdown; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-lg-5">
                                <?php
                                echo $controlHrPeopleDepartmentDropdown;
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-lg-5">
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
                        <div class="form-group">
                            <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-lg-5">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '1', ($row->is_active == 1 ? TRUE : '')); ?>
                                        Нээх </label>
                                    <label class="radio-inline">
                                        <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '0', ($row->is_active == 0 ? TRUE : '')); ?>
                                        Хаах </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <span>Бодит хандалт: <?php echo $row->click_real; ?>, Зохимол хандалт: </span>
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
                        <div class="form-group">
                            <?php echo form_label('Тайлбар', 'Тайлбар', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-lg-10">
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

                    <div class="tab-pane has-padding" id="right-content-media-language-<?php echo $this->session->adminDefaultLanguage->code; ?>">
                        <?php $this->load->view(MY_ADMIN . '/contentMedia/index', array('content' => $row, 'language' => $this->session->adminDefaultLanguage)); ?>
                    </div>

                    <div class="tab-pane has-padding" id="right-content-comment-language-<?php echo $this->session->adminDefaultLanguage->code; ?>">
                        <?php $this->load->view(MY_ADMIN . '/comment/lists', array('content' => $row, 'language' => $this->session->adminDefaultLanguage)); ?>
                    </div>
                </div>

                <ul class="nav nav-tabs nav-tabs-highlight">
                    <li class="active"><a href="#right-content-language-<?php echo $this->session->adminDefaultLanguage->code; ?>" data-toggle="tab" aria-expanded="true">Агуулга</a></li>
                    <li><a href="#right-content-media-language-<?php echo $this->session->adminDefaultLanguage->code; ?>" data-toggle="tab" aria-expanded="false">Медиа</a></li>
                    <li><a href="#right-content-comment-language-<?php echo $this->session->adminDefaultLanguage->code; ?>" data-toggle="tab" aria-expanded="false">Сэтгэгдэл</a></li>
                </ul>
            </div>

        </div>
        <?php
        if ($this->session->adminTranslateLanguage) {

            $langString = '';
            foreach ($this->session->adminTranslateLanguage as $langKey => $langRow) {
                $langString .= '<div class="tab-pane" id="tab-content-language-' . $langRow->code . '">';

                $langString .= '<div class="tabbable nav-tabs-vertical nav-tabs-right">';
                $langString .= '<div class="tab-content">';
                $langString .= '<div class="tab-pane has-padding active" id="right-content-language-' . $langRow->code . '">';
                $langString .= 'content';
                $langString .= '</div>';

                $langString .= '<div class="tab-pane has-padding" id="right-content-media-language-' . $langRow->code . '">';
                $langString .= 'media';
                $langString .= '</div>';
                $langString .= '<div class="tab-pane has-padding" id="right-content-comment-language-' . $langRow->code . '">';
                $langString .= 'comment';
                $langString .= '</div>';
                $langString .= '</div>';

                $langString .= '<ul class="nav nav-tabs nav-tabs-highlight">';
                $langString .= '<li class="active"><a href="#right-content-language-' . $langRow->code . '" data-toggle="tab" aria-expanded="true">Агуулга</a></li>';
                $langString .= '<li class=""><a href="#right-content-media-language-' . $langRow->code . '" data-toggle="tab" aria-expanded="false">Медиа</a></li>';
                $langString .= '<li class=""><a href="#right-content-comment-language-' . $langRow->code . '" data-toggle="tab" aria-expanded="false">Сэтгэгдэл</a></li>';
                $langString .= '</ul>';

                $langString .= '</div>';

                $langString .= '</div>';
            }
            echo $langString;
        }
        ?>
    </div>
</div>
<?php
if (isset($contentMediaJsFile)) {
    foreach ($contentMediaJsFile as $contentMedia) {
        echo '<script src="' . $contentMedia . '" type="text/javascript" async defer></script>' . "\n";
    }
}
?>