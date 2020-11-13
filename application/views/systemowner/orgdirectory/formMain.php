<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-main', 'enctype' => 'multipart/form-data')); ?>
<?php
echo form_hidden('id', $row['id']);
echo form_hidden('modId', $modId);
echo form_hidden('urlId', $row['url_id']);
//echo form_hidden('imageCropTypeId', $row['image_crop_type']);

echo form_hidden('oldPic', $row['pic']);
echo form_hidden('pic');
echo form_hidden('crop_x');
echo form_hidden('crop_y');
echo form_hidden('crop_width');
echo form_hidden('crop_height');
?>
<div class="clearfix margin-top-20"></div>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabContentMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabContentEnglish" data-toggle="tab">English</a></li>
        <li><a href="#tabContentConfig" data-toggle="tab">Тохиргоо</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabContentMongolia">
            
            <div class="form-group" id="picField">
                <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="media no-margin-top">
                        <div class="media-left">
                            <?php
                            $this->picSmall = UPLOADS_CONTENT_PATH . CROP_SMALL . $row['pic'];
                            $this->picBig = UPLOADS_CONTENT_PATH . CROP_MEDIUM . $row['pic'];
                            if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picSmall)) {
                                echo '<div style="position: relative; display: inline-block;">';
                                echo '<a href="' . $this->picBig . '" class="fancybox" data-fancybox-group="gallery">';
                                echo '<img src="' . $this->picSmall . '" style="width: 58px; height: 58px;" class="img-rounded">';
                                echo '</a>';
                                echo '<span class="badge bg-danger" style="position: absolute; bottom: -8px; right: -8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_removeImage(\'' . $row['pic'] . '\');"><i class="fa fa-close"></i></span>';
                                echo '</div>';
                            } else {
                                echo '<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">';
                            }
                            ?>
                        </div>

                        <div class="media-body">
                            <?php
                            echo '<div class="uploader">';
                            echo form_upload(array(
                                'name' => 'picUpload',
                                'id' => 'picUpload',
                                'class' => 'pull-left file-styled',
                                'onchange' => '_uploadImage();',
                            ));
                            echo '<span class="filename" style="user-select: none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
                            echo '</div>';
                            ?>
                            <span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlCategoryListDropdown; ?>
                </div>
            </div>
            <?php if ($this->session->userdata['adminAccessTypeId'] == 1) { ?>
                <div class="form-group">
                    <?php echo form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-5">
                        <?php
                        echo $controlPartnerDropdown;
                        ?>
                    </div>
                </div>
            <?php } else {
                echo form_hidden('partnerId', $this->session->adminPartnerId);
            }?>
            <div class="form-group">
                <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'titleMn',
                        'id' => 'titleMn',
                        'value' => $row['title_mn'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Гарчиг тайлбар', 'Гарчиг тайлбар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'linkTitleMn',
                        'id' => 'linkTitleMn',
                        'value' => $row['link_title_mn'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Вэб сайт', 'Вэб сайт', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'directoryWeb',
                        'id' => 'directoryWeb',
                        'value' => $row['directory_web'],
                        'maxlength' => '100',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'directoryPhone',
                        'id' => 'directoryPhone',
                        'value' => $row['directory_phone'],
                        'maxlength' => '100',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Факс', 'Факс', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'directoryFax',
                        'id' => 'directoryFax',
                        'value' => $row['directory_fax'],
                        'maxlength' => '100',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Мэйл', 'Мэйл', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'directoryEmail',
                        'id' => 'directoryEmail',
                        'value' => $row['directory_email'],
                        'maxlength' => '100',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'directoryAddress',
                        'id' => 'directoryAddress',
                        'value' => $row['directory_address'],
                        'maxlength' => '100',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <?php
                    
                    $this->socialArray = json_decode($row['directory_social']);

                    foreach ($this->socialArray as $paramKey => $paramRow) {

                        echo '<div class="form-group">';
                        echo form_label($paramRow->label, $paramRow->label, array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE));
                        echo '<div class="col-lg-5">';
                        echo form_hidden('socialLabel[' . $paramKey . ']', $paramRow->label);
                        echo form_hidden('socialShow[' . $paramKey . ']', $paramRow->show);
                        echo form_input(array(
                            'name' => 'social[' . $paramKey . ']',
                            'id' => 'social[' . $paramKey . ']',
                            'value' => $paramRow->address,
                            'maxlength' => '500',
                            'class' => 'form-control'
                        ));
                        echo '</div>';
                        echo '<div class="col-md-5">';
                        echo '<div class="radio-list">';
                        echo '<label class="radio-inline">';
                        echo form_radio(array('name' => 'showSocial[' . $paramKey . ']', 'class' => 'radio'), '1', ($paramRow->show == 1 ? TRUE : ''));
                        echo 'Нээх </label>';
                        echo '<label class="radio-inline">';
                        echo form_radio(array('name' => 'showSocial[' . $paramKey . ']', 'class' => 'radio'), '0', ($paramRow->show == 0 ? TRUE : ''));
                        echo 'Хаах </label>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>

            
            <div class="form-group">
                <?php echo form_label('Тайлбар', 'Тайлбар', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'introTextMn',
                        'id' => 'introTextMn',
                        'value' => $row['intro_text_mn'],
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'fullTextMn',
                        'id' => 'fullTextMn',
                        'value' => $row['full_text_mn'],
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <?php echo form_label('Хуудасны гарчиг', 'Хуудасны гарчиг', array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'pageTitleMn',
                        'id' => 'pageTitleMn',
                        'value' => $row['page_title_mn'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Web browser-н title bar дээр харагдах үг</span>
                </div>
                <div class="col-md-6">
                    <?php echo form_label('Хуудасны гол агуулга', 'Хуудасны гол агуулга', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'h1TextMn',
                        'id' => 'h1TextMn',
                        'value' => $row['h1_text_mn'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">h1 text</span>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'metaKeyMn',
                        'id' => 'metaKeyMn',
                        'value' => $row['meta_key_mn'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Хайлтын системд бүртгүүлэх түлхүүр үгийг таслалаар тусгаарлан бичнэ.</span>

                </div>
                <div class="col-md-6">
                    <?php echo form_label('Хуудасны тайлбар', 'Хуудасны тайлбар', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'metaDescMn',
                        'id' => 'metaDescMn',
                        'value' => $row['meta_desc_mn'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Энэ хуудасны тухай товчхон 1 өгүүлбэр</span>
                </div>
                <div class="clearfix"></div>    
                <div class="col-md-6 text-left">
                </div>
                <div class="col-md-6 text-right">
                    <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({modId: ' . $modId . ', mode: \'' . $mode . '\'});"', 'button'); ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="tab-pane" id="tabContentEnglish">
            <div class="form-group">
                <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), 1, ($row['is_active_en'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), 0, ($row['is_active_en'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                    <div id="form_2_membership_error">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span>Бодит хандалт: <?php echo $row['click_real_en']; ?>, Зохимол хандалт: </span>
                        </span>
                        <?php
                        echo form_input(array(
                            'name' => 'clickEn',
                            'id' => 'clickEn',
                            'value' => $row['click_en'],
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
                <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'titleEn',
                        'id' => 'titleEn',
                        'value' => $row['title_en'],
                        'maxlength' => '500',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Гарчиг тайлбар', 'Гарчиг тайлбар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'linkTitleEn',
                        'id' => 'linkTitleEn',
                        'value' => $row['link_title_en'],
                        'maxlength' => '500',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Тайлбар', 'Тайлбар', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'introTextEn',
                        'id' => 'introTextEn',
                        'value' => $row['intro_text_en'],
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'fullTextEn',
                        'id' => 'fullTextEn',
                        'value' => $row['full_text_en'],
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <?php echo form_label('Хуудасны гарчиг', 'Хуудасны гарчиг', array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'pageTitleEn',
                        'id' => 'pageTitleEn',
                        'value' => $row['page_title_en'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Web browser-н title bar дээр харагдах үг</span>
                </div>
                <div class="col-md-6">
                    <?php echo form_label('Хуудасны гол агуулга', 'Хуудасны гол агуулга', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'h1TextEn',
                        'id' => 'h1TextEn',
                        'value' => $row['h1_text_en'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">h1 text</span>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'metaKeyEn',
                        'id' => 'metaKeyEn',
                        'value' => $row['meta_key_en'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Хайлтын системд бүртгүүлэх түлхүүр үгийг таслалаар тусгаарлан бичнэ.</span>

                </div>
                <div class="col-md-6">
                    <?php echo form_label('Хуудасны тайлбар', 'Хуудасны тайлбар', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'metaDescEn',
                        'id' => 'metaDescEn',
                        'value' => $row['meta_desc_en'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Энэ хуудасны тухай товчхон 1 өгүүлбэр</span>
                </div>
                <div class="clearfix"></div>    
                <div class="col-md-6 text-left">
                </div>
                <div class="col-md-6 text-right">
                    <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({modId: ' . $modId . ', mode: \'' . $mode . '\'});"', 'button'); ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="tab-pane" id="tabContentConfig">
            <div class="form-group">
                <?php echo form_label('Вэб хаяг /url/', 'Вэб хаяг /url/', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'url',
                        'id' => 'url',
                        'value' => $row['url'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Автор (Мэдээллийг нэмсэн)', 'Автор (Мэдээллийг нэмсэн)', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlAuthorDropdown; ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Автор', 'Автор', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showAuthor', 'name' => 'showAuthor', 'class' => 'radio'), 1, ($row['show_author'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showAuthor', 'name' => 'showAuthor', 'class' => 'radio'), 0, ($row['show_author'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'orderNum',
                        'id' => 'orderNum',
                        'value' => $row['order_num'],
                        'maxlength' => '10',
                        'class' => 'form-control integer',
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
                            <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), '1', ($row['is_active_mn'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), '0', ($row['is_active_mn'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Сэтгэгдэл', 'Сэтгэгдэл', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showComment', 'name' => 'showComment', 'class' => 'radio'), 1, ($row['show_comment'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showComment', 'name' => 'showComment', 'class' => 'radio'), 0, ($row['show_comment'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Гадна зураг', 'Гадна зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showPicOutside', 'name' => 'showPicOutside', 'class' => 'radio'), 1, ($row['show_pic_outside'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showPicOutside', 'name' => 'showPicOutside', 'class' => 'radio'), 0, ($row['show_pic_outside'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Дотор зураг', 'Дотор зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showPicInside', 'name' => 'showPicInside', 'class' => 'radio'), 1, ($row['show_pic_inside'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showPicInside', 'name' => 'showPicInside', 'class' => 'radio'), 0, ($row['show_pic_inside'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Сошиал', 'Сошиал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showSocial', 'name' => 'showSocial', 'class' => 'radio'), 1, ($row['show_social'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showSocial', 'name' => 'showSocial', 'class' => 'radio'), 0, ($row['show_social'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label(' ', ' ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span>Бодит хандалт: <?php echo $row['click_real_mn']; ?>, Зохимол хандалт: </span>
                        </span>
                        <?php
                        echo form_input(array(
                            'name' => 'clickMn',
                            'id' => 'clickMn',
                            'value' => $row['click_mn'],
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
                <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showClick', 'name' => 'showClick', 'class' => 'radio'), 1, ($row['show_click'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showClick', 'name' => 'showClick', 'class' => 'radio'), 0, ($row['show_click'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Нийтлэх огноо', 'Нийтлэх огноо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10 col-md-10">
                    <?php $isActiveDate = explode(' ', $row['is_active_date']); ?>
                    
                        <?php
                        echo form_input(array(
                            'name' => 'isActiveDate',
                            'id' => 'isActiveDate',
                            'value' => $isActiveDate['0'],
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true,
                            'style' => 'float:left; margin-right:10px;'
                        ));
                        ?>
                
                    <div class="input-group date date-time" id="event_start_date" style="width:120px; float:left;">
                        <?php
                        $time = explode(':', $isActiveDate['1']);
                        echo form_input(array(
                            'name' => 'isActiveTime',
                            'id' => 'isActiveTime',
                            'value' => $time['0'] . ':' . $time['1'],
                            'maxlength' => '8',
                            'class' => 'form-control init-time',
                            'required' => 'required',
                            'readonly' => true
                        ));
                        ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Огноо', 'Огноо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showDate', 'name' => 'showDate', 'class' => 'radio'), 1, ($row['show_date'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'showDate', 'name' => 'showDate', 'class' => 'radio'), 0, ($row['show_date'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Агуулгыг харуулах загвар', 'Агуулгыг харуулах загвар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10">
                    <?php echo $controlThemeLayoutRadio; ?>
                </div>
            </div>
            <hr>
            <div class="clearfix"></div>    
                <div class="col-md-6 text-left">
                </div>
                <div class="col-md-6 text-right">
                    <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({modId: ' . $modId . ', mode: \'' . $mode . '\'});"', 'button'); ?>
                </div>
                <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">

    _uploadImageOldData = '<label for="Зураг" required="required" class="control-label col-lg-2 text-right" defined="1">Зураг: </label>';
    _uploadImageOldData += '<div class="col-lg-5">';
    _uploadImageOldData += '<div class="media no-margin-top">';
    _uploadImageOldData += '<div class="media-left">';
    _uploadImageOldData += '<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">';
    _uploadImageOldData += '</div>';
    _uploadImageOldData += '<div class="media-body">';
    _uploadImageOldData += '<div class="uploader">';
    _uploadImageOldData += '<input type="file" name="picUpload" id="picUpload" class="pull-left file-styled" onchange="_uploadImage();">';
    _uploadImageOldData += '<span class="filename" style="user-select: none;">Файл сонгох</span>';
    _uploadImageOldData += '<span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
    _uploadImageOldData += '</div>';
    _uploadImageOldData += '<span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?></span>';
    _uploadImageOldData += '</div>';
    _uploadImageOldData += '</div>';
    _uploadImageOldData += '</div>';

</script>
