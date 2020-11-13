<ul class="nav nav-tabs nav-tabs-bottom mb-0">
    <li class="nav-item"><a href="#bottom-tab1" class="nav-link active" data-toggle="tab">Үндсэн</a></li>
    <li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Медиа</a></li>
    <li class="nav-item"><a href="#bottom-tab3" class="nav-link" data-toggle="tab">Сэтгэгдэл</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="bottom-tab1">

        <?php
        echo form_open('', array('class' => 'form-horizontal p-0', 'id' => 'form-contact', 'enctype' => 'multipart/form-data'));
        echo form_hidden('id', $row->id);
        echo form_hidden('parentId', $row->parent_id);
        echo form_hidden('modId', $row->mod_id);
        echo form_hidden('contactPic', '');
        if ($row->id != 0) {
            echo form_hidden('contactOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH . CROP_SMALL));
        } else {
            echo form_hidden('contactOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH));
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
                            <div class="col-md-10">
                                <?php
                                echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
                                echo '<a href="javascript:;">';
                                echo '<img src="' . $row->pic . '" class="_contact-image">';
                                echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'contact\', formId: _contactFormMainId, appendHtmlClass: \'._contact-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_MEDIA_PATH, prefix: \'contact\'});">';
                                echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
                                echo '</span>';
                                echo '<span class="_user-image-upload-button">';
                                echo '<div class="uploader">';
                                echo form_upload(array(
                                    'name' => 'contactPicUpload',
                                    'id' => 'contactPicUpload',
                                    'class' => 'pull-left file-styled',
                                    'onchange' => '_imageUpload({
                                    elem: this, 
                                    uploadPath: UPLOADS_CONTENT_PATH, 
                                    formId: _contactFormMainId, 
                                    appendHtmlClass: \'._contact-image\', 
                                    prefix: \'contact\'});',
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
                            <?php echo form_label('Факс', 'Факс', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-5">
                                <?php
                                echo form_input(array(
                                    'name' => 'fax',
                                    'id' => 'fax',
                                    'value' => $row->fax,
                                    'maxlength' => '500',
                                    'class' => 'form-control'
                                ));
                                ?>
                            </div>
                            <div class="col-md-5">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showFax'), 1, ($row->show_fax == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showFax'), 0, ($row->show_fax == 0 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Албаны утас', 'Албаны утас', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-5">
                                <?php
                                echo form_input(array(
                                    'name' => 'phone',
                                    'id' => 'phone',
                                    'value' => $row->phone,
                                    'maxlength' => '500',
                                    'class' => 'form-control'
                                ));
                                ?>
                            </div>
                            <div class="col-md-5">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPhone'), 1, ($row->show_phone == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPhone'), 0, ($row->show_phone == 0 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-5">
                                <?php
                                echo form_input(array(
                                    'name' => 'mobile',
                                    'id' => 'mobile',
                                    'value' => $row->mobile,
                                    'maxlength' => '500',
                                    'class' => 'form-control'
                                ));
                                ?>
                            </div>
                            <div class="col-md-5">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showMobile'), 1, ($row->show_mobile == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showMobile'), 0, ($row->show_mobile == 0 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Хаяг/Байршил', 'Хаяг/Байршил', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-5">
                                <?php
                                echo form_input(array(
                                    'name' => 'address',
                                    'id' => 'address',
                                    'value' => $row->address,
                                    'maxlength' => '500',
                                    'class' => 'form-control',
                                    'required' => 'required'
                                ));
                                ?>
                            </div>
                            <div class="col-md-5">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showAddress'), 1, ($row->show_address == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showAddress'), 0, ($row->show_address == 0 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Шуудангийн хаяг', 'Шуудангийн хаяг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-5">
                                <?php
                                echo form_input(array(
                                    'name' => 'postAddress',
                                    'id' => 'postAddress',
                                    'value' => $row->post_address,
                                    'maxlength' => '500',
                                    'class' => 'form-control'
                                ));
                                ?>
                            </div>
                            <div class="col-md-5">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPostAddress'), 1, ($row->show_post_address == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPostAddress'), 0, ($row->show_post_address == 0 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                            </div>
                        </div>

                        <?php
                        $this->socialArray = json_decode($row->social);

                        foreach ($this->socialArray as $paramKey => $paramRow) {

                            echo '<div class="form-group row">';
                            echo form_label($paramRow->label, $paramRow->label, array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE));
                            echo '<div class="col-md-5">';
                            echo form_hidden('socialLabel[' . $paramKey . ']', $paramRow->label);
                            echo form_hidden('socialClass[' . $paramKey . ']', $paramRow->class);
                            echo form_input(array(
                                'name' => 'socialUrl[' . $paramKey . ']',
                                'id' => 'socialUrl[' . $paramKey . ']',
                                'value' => $paramRow->url,
                                'maxlength' => '500',
                                'class' => 'form-control'
                            ));
                            echo '</div>';
                            echo '<div class="col-md-5">';
                            echo '<div class="form-check form-check-inline">';
                            echo '<label class="form-check-label">';
                            echo form_radio(array('name' => 'socialShow[' . $paramKey . ']', 'class' => 'radio'), 1, ($paramRow->show == 1 ? TRUE : ''));
                            echo 'Нээх </label>';
                            echo '</div>';
                            echo '<div class="form-check form-check-inline">';
                            echo '<label class="form-check-label">';
                            echo form_radio(array('name' => 'socialShow[' . $paramKey . ']', 'class' => 'radio'), 0, ($paramRow->show == 0 ? TRUE : ''));
                            echo 'Хаах </label>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                        <div class="form-group row">
                            <?php echo form_label('Ил харуулах мэйл', 'Ил харуулах мэйл', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-5">
                                <?php
                                echo form_input(array(
                                    'name' => 'email',
                                    'id' => 'email',
                                    'value' => $row->email,
                                    'maxlength' => '500',
                                    'class' => 'form-control',
                                    'required' => 'required'
                                ));
                                ?>
                            </div>
                            <div class="col-md-5">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showEmail'), 1, ($row->show_email == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showEmail'), 0, ($row->show_email == 0 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Формоор дамжуулан захиа авах', 'Формоор дамжуулан захиа авах', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-5">
                                <?php
                                echo form_input(array(
                                    'name' => 'emailTo',
                                    'id' => 'emailTo',
                                    'value' => $row->email_to,
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
                            <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showIntroText'), 1, ($row->show_intro_text == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showIntroText'), 0, ($row->show_intro_text == 0 ? TRUE : '')); ?>
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