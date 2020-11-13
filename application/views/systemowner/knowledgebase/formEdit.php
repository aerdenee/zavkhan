<ul class="nav nav-tabs nav-tabs-bottom mb-0">
    <li class="nav-item"><a href="#tab-content" class="nav-link active" data-toggle="tab">Үндсэн</a></li>
    <li class="nav-item"><a href="#window-content-media" class="nav-link" data-toggle="tab">Медиа</a></li>
    <li class="nav-item"><a href="#window-content-comment" class="nav-link" data-toggle="tab">Сэтгэгдэл</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tab-content">
        <?php
        echo form_open('', array('class' => 'form-horizontal p-0', 'id' => 'form-knowledgebase', 'enctype' => 'multipart/form-data'));
        echo form_hidden('id', $row->id);
        echo form_hidden('parentId', $row->parent_id);
        echo form_hidden('modId', $row->mod_id);
        echo form_hidden('knowledgebasePic', '');
        if ($row->id != 0) {
            echo form_hidden('knowledgebaseOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH . CROP_SMALL));
        } else {
            echo form_hidden('knowledgebase', ltrim($row->pic, UPLOADS_CONTENT_PATH));
        }
        ?>
        <div id="accordion-group">
            <div class="card mb-0 card rounded-top-0 rounded-bottom-0">
                <div class="card-header">
                    <h6 class="card-title">
                        <a data-toggle="collapse" class="text-default" href="#accordion-item-group1">Агуулга</a>
                    </h6>
                </div>

                <div id="accordion-item-group1" class="collapse show" data-parent="#accordion-group">
                    <div class="card-body col-md-12">

                        <div class="form-group row">
                            <?php echo form_label('Фото зураг', 'Фото зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-7">
                                <?php
                                echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
                                echo '<a href="javascript:;">';
                                echo '<img src="' . $row->pic . '" class="_knowledgebase-image">';
                                echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'content\', formId: _knowledgebaseFormMainId, appendHtmlClass: \'._knowledgebase-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, prefix: \'knowledgebase\'});">';
                                echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
                                echo '</span>';
                                echo '<span class="_user-image-upload-button">';
                                echo '<div class="uploader">';
                                echo form_upload(array(
                                    'name' => 'knowledgebasePicUpload',
                                    'id' => 'knowledgebasePicUpload',
                                    'class' => 'pull-left file-styled',
                                    'onchange' => '_imageUpload({
                                        elem: this, 
                                        uploadPath: UPLOADS_CONTENT_PATH, 
                                        formId: _knowledgebaseFormMainId, 
                                        appendHtmlClass: \'._knowledgebase-image\', 
                                        prefix: \'knowledgebase\'});',
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
                            <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
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
                            <?php echo form_label('Товч тайлбар', 'Товч тайлбар', array('class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-10">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'introText',
                                    'id' => 'introText',
                                    'value' => $row->intro_text,
                                    'rows' => 4,
                                    'class' => 'form-control'
                                ));
                                ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <?php echo form_label('Бүрэн агуулга', 'Бүрэн агуулга', array('class' => 'control-label col-md-12', 'defined' => TRUE)); ?>
                                <?php
                                echo form_textarea(array(
                                    'name' => 'fullText',
                                    'id' => 'fullText',
                                    'value' => $row->full_text,
                                    'rows' => 4,
                                    'class' => 'form-control ckeditor'
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-0 rounded-0 border-y-0">
                <div class="card-header">
                    <h6 class="card-title">
                        <a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-group2">Хайлтын систем</a>
                    </h6>
                </div>

                <div id="accordion-item-group2" class="collapse" data-parent="#accordion-group">
                    <div class="card-body">
                        <div class="form-group row">
                            <?php echo form_label('Гарчиг тайлбар', 'Гарчиг тайлбар', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-7">
                                <?php
                                echo form_input(array(
                                    'name' => 'linkTitle',
                                    'id' => 'linkTitle',
                                    'value' => $row->link_title,
                                    'maxlength' => '500',
                                    'class' => 'form-control'
                                ));
                                ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Хуудасны гарчиг', 'Хуудасны гарчиг', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-10">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'pageTitle',
                                    'id' => 'pageTitle',
                                    'value' => $row->page_title,
                                    'maxlength' => '500',
                                    'rows' => 3,
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">Web browser-н title bar дээр харагдах үг</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Хуудасны гол агуулга', 'Хуудасны гол агуулга', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-10">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'h1Text',
                                    'id' => 'h1Text',
                                    'value' => $row->h1_text,
                                    'maxlength' => '500',
                                    'rows' => 3,
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">h1 text</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => FALSE)); ?>
                            <div class="col-md-10">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'metaKey',
                                    'id' => 'metaKey',
                                    'value' => $row->meta_key,
                                    'maxlength' => '500',
                                    'rows' => 3,
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">Хайлтын системд бүртгүүлэх түлхүүр үгийг таслалаар тусгаарлан бичнэ.</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Хуудасны тайлбар', 'Хуудасны тайлбар', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => FALSE)); ?>
                            <div class="col-md-10">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'metaDesc',
                                    'id' => 'metaDesc',
                                    'value' => $row->meta_desc,
                                    'maxlength' => '500',
                                    'rows' => 3,
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">Энэ хуудасны тухай товчхон 1 өгүүлбэр</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Вэб хаяг /url/', 'Вэб хаяг /url/', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-10">
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
                    </div>
                </div>
            </div>

            <div class="card rounded-top-0 rounded-bottom-0 mb-0">
                <div class="card-header">
                    <h6 class="card-title">
                        <a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-group3">Тохиргоо</a>
                    </h6>
                </div>

                <div id="accordion-item-group3" class="collapse" data-parent="#accordion-group">
                    <div class="card-body">
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
                            <?php echo form_label('Автор (Мэдээллийг нэмсэн)', 'Автор (Мэдээллийг нэмсэн)', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-7">
                                <?php echo $controlHrPeopleListDropdown; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Автор', 'Автор', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPeople'), 1, ($row->show_people == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPeople'), 0, ($row->show_people == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-7">
                                <?php
                                echo form_input(array(
                                    'name' => 'orderNum',
                                    'id' => 'orderNum',
                                    'value' => $row->order_num,
                                    'maxlength' => '10',
                                    'class' => 'form-control integer',
                                    'required' => 'required'
                                ));
                                ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Сэтгэгдэл', 'Сэтгэгдэл', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showComment'), 1, ($row->show_comment == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showComment'), 0, ($row->show_comment == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Гадна зураг', 'Гадна зураг', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPicOutside'), 1, ($row->show_pic_outside == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPicOutside'), 0, ($row->show_pic_outside == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Дотор зураг', 'Дотор зураг', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPicInside'), 1, ($row->show_pic_inside == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPicInside'), 0, ($row->show_pic_inside == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Сошиал', 'Сошиал', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showSocial'), 1, ($row->show_social == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showSocial'), 0, ($row->show_social == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showClick'), 1, ($row->show_click == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showClick'), 0, ($row->show_click == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Нийтлэх огноо', 'Нийтлэх огноо', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
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
                                        <span class="input-group-append">
                                            <span class="input-group-text"><i class="icon-calendar"></i></span>
                                        </span>
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
                            <?php echo form_label('Огноо', 'Огноо', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showDate'), 1, ($row->show_date == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showDate'), 0, ($row->show_date == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Агуулгын гарчиг', 'Агуулгын гарчиг', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showTitle'), 1, ($row->show_title == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showTitle'), 0, ($row->show_title == 0 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Агуулгыг харуулах загвар', 'Агуулгыг харуулах загвар', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php echo $controlThemeLayoutRadio; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>

    <div class="tab-pane fade" id="window-content-media" data-cont-id="<?php echo $row->id;?>" data-mod-id="<?php echo $row->mod_id;?>">
        <?php $this->load->view(MY_ADMIN . '/contentMedia/index', array('content' => $row)); ?>
    </div>

    <div class="tab-pane fade" id="window-content-comment" data-cont-id="<?php echo $row->id;?>" data-mod-id="<?php echo $row->mod_id;?>" data-sort-type="DESC" style="width: 100%;">
        <?php $this->load->view(MY_ADMIN . '/contentComment/lists', array('content' => $row)); ?>
    </div>

</div>

<?php
if (isset($contentMediaJsFile)) {
    foreach ($contentMediaJsFile as $contentMedia) {
        echo '<script src="' . $contentMedia . '" type="text/javascript" async defer></script>' . "\n";
    }
}
?>

