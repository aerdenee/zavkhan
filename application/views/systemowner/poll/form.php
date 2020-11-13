<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-poll', 'enctype' => 'multipart/form-data')); ?>
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo $module->title; ?></h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="<?php echo Spoll::$path . 'index/' . $modId; ?>"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>
            </ul>
        </div>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

    <div class="panel-body">

        <?php
        echo form_hidden('id', $row->id);
        echo form_hidden('modId', $modId);
        echo form_hidden('oldPicEn', $row->pic_en);
        echo form_hidden('picEn');
        echo form_hidden('oldPicMn', $row->pic_mn);
        echo form_hidden('picMn');
        ?>

        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="active"><a href="#tabFeedbackMongolia" data-toggle="tab">Монгол</a></li>
                <li><a href="#tabFeedbackEnglish" data-toggle="tab">English</a></li>
                <li><a href="#tabpollDetail" data-toggle="tab">Хариулт</a></li>
                <li><a href="#tabpollConfig" data-toggle="tab">Тохиргоо</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tabFeedbackMongolia">
                    <div class="form-group" id="pic-field-mn">
                        <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="media no-margin-top">
                                <div class="media-left">
                                    <?php
                                    $this->picMn = UPLOADS_CONTENT_PATH . $row->pic_mn;
                                    if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picMn)) {
                                        echo '<div style="position: relative; display: inline-block;">';
                                        echo '<a href="' . $this->picMn . '" class="fancybox" data-fancybox-group="gallery">';
                                        echo '<img src="' . $this->picMn . '" style="width: 58px; height: 58px;" class="img-rounded">';
                                        echo '</a>';
                                        echo '<span class="badge bg-danger" style="position: absolute; bottom: -8px; right: -8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_removeImage({lang:\'mn\', elem: this, description: \'Хуулах боломжтой зураг: ' . formatInFileExtension(UPLOAD_IMAGE_TYPE) . '  Хуулах файлын хэмжээ: ' . formatInBytes(UPLOAD_FILE_MAX_SIZE) . '\', pic: \'' . $row->pic_mn . '\'});"><i class="fa fa-close"></i></span>';
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
                                        'name' => 'picUploadMn',
                                        'id' => 'picUploadMn',
                                        'class' => 'pull-left file-styled',
                                        'onchange' => '_uploadImage({lang:\'mn\', elem: this, description: \'Хуулах боломжтой зураг: ' . formatInFileExtension(UPLOAD_IMAGE_TYPE) . '  Хуулах файлын хэмжээ: ' . formatInBytes(UPLOAD_FILE_MAX_SIZE) . '\'});',
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
                        <?php echo form_label('Гадна зураг', 'Гадна зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showPicOutsideMn', 'name' => 'showPicOutsideMn', 'class' => 'radio'), 1, ($row->show_pic_outside_mn == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showPicOutsideMn', 'name' => 'showPicOutsideMn', 'class' => 'radio'), 0, ($row->show_pic_outside_mn == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Дотор зураг', 'Дотор зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showPicInsideMn', 'name' => 'showPicInsideMn', 'class' => 'radio'), 1, ($row->show_pic_inside_mn == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showPicInsideMn', 'name' => 'showPicInsideMn', 'class' => 'radio'), 0, ($row->show_pic_inside_mn == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), 1, ($row->is_active_mn == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), 0, ($row->is_active_mn == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-6">
                            <?php echo $controlCategoryListDropdown; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-10">
                            <?php
                            echo form_textarea(array(
                                'name' => 'titleMn',
                                'id' => 'titleMn',
                                'value' => $row->title_mn,
                                'rows' => 4,
                                'class' => 'form-control'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>    
                    <div class="col-md-6 text-left">
                    </div>
                    <div class="col-md-6 text-right">
                        <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({modId: ' . $modId . ', mode: \'' . $mode . '\'});"', 'button'); ?>
                    </div>
                    <div class="clearfix"></div>

                </div>
                <div class="tab-pane" id="tabFeedbackEnglish">

                    <div class="form-group" id="pic-field-en">
                        <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="media no-margin-top">
                                <div class="media-left">
                                    <?php
                                    $this->picEn = UPLOADS_CONTENT_PATH . $row->pic_en;
                                    if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picEn)) {
                                        echo '<div style="position: relative; display: inline-block;">';
                                        echo '<a href="' . $this->picEn . '" class="fancybox" data-fancybox-group="gallery">';
                                        echo '<img src="' . $this->picEn . '" style="width: 58px; height: 58px;" class="img-rounded">';
                                        echo '</a>';
                                        echo '<span class="badge bg-danger" style="position: absolute; bottom: -8px; right: -8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_removeImage({lang:\'en\', elem: this, description: \'Хуулах боломжтой зураг: ' . formatInFileExtension(UPLOAD_IMAGE_TYPE) . '  Хуулах файлын хэмжээ: ' . formatInBytes(UPLOAD_FILE_MAX_SIZE) . '\', pic: \'' . $row->pic_en . '\'});"><i class="fa fa-close"></i></span>';
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
                                        'name' => 'picUploadEn',
                                        'id' => 'picUploadEn',
                                        'class' => 'pull-left file-styled',
                                        'onchange' => '_uploadImage({lang:\'en\', elem: this, description: \'Хуулах боломжтой зураг: ' . formatInFileExtension(UPLOAD_IMAGE_TYPE) . '  Хуулах файлын хэмжээ: ' . formatInBytes(UPLOAD_FILE_MAX_SIZE) . '\'});',
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
                        <?php echo form_label('Гадна зураг', 'Гадна зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showPicOutsideEn', 'name' => 'showPicOutsideEn', 'class' => 'radio'), 1, ($row->show_pic_inside_en == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showPicOutsideEn', 'name' => 'showPicOutsideEn', 'class' => 'radio'), 0, ($row->show_pic_inside_en == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Дотор зураг', 'Дотор зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showPicInsideEn', 'name' => 'showPicInsideEn', 'class' => 'radio'), 1, ($row->show_pic_inside_en == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showPicInsideEn', 'name' => 'showPicInsideEn', 'class' => 'radio'), 0, ($row->show_pic_inside_en == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Нийтлэх', 'Нийтлэх', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('class' => 'radio', 'name' => 'isActiveEn'), 1, ($row->is_active_en == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('class' => 'radio', 'name' => 'isActiveEn'), 0, ($row->is_active_en == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span>Бодит хандалт: <?php echo $row->click_real_en; ?>, Зохимол хандалт: </span>
                                </span>
                                <?php
                                echo form_input(array(
                                    'name' => 'clickEn',
                                    'id' => 'clickEn',
                                    'value' => $row->click_en,
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
                        <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-10">
                            <?php
                            echo form_textarea(array(
                                'name' => 'titleEn',
                                'id' => 'titleEn',
                                'value' => $row->title_en,
                                'rows' => 4,
                                'class' => 'form-control'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>    
                    <div class="col-md-6 text-left">
                    </div>
                    <div class="col-md-6 text-right">
                        <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({modId: ' . $modId . ', mode: \'' . $mode . '\'});"', 'button'); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="tab-pane" id="tabpollDetail">
                    <?php
                    if ($mode == 'update') {
                        $this->load->view(MY_ADMIN . '/poll/listDetail', $param);
                    } else {
                        echo $row->emptyTabContent;
                    }
                    ?>
                </div>
                <div class="tab-pane" id="tabpollConfig">
                    <?php if ($this->session->userdata['adminAccessTypeId'] == 1) { ?>
                        <div class="form-group">
                            <?php echo form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                            <div class="col-lg-5">
                                <?php
                                echo $controlPartnerDropdown;
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'orderNum',
                                'id' => 'orderNum',
                                'value' => $row->order_num,
                                'maxlength' => '255',
                                'class' => 'form-control integer',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Нийтлэх огноо', 'Нийтлэх огноо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php $date = explode(' ', $row->is_active_date); ?>
                            <?php
                            echo form_input(array(
                                'name' => 'isActiveDate',
                                'id' => 'isActiveDate',
                                'value' => $date['0'],
                                'maxlength' => '10',
                                'class' => 'form-control init-date',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Огноо', 'Огноо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showDate', 'name' => 'showDate', 'class' => 'radio'), 1, ($row->show_date == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showDate', 'name' => 'showDate', 'class' => 'radio'), 0, ($row->show_date == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showClick', 'name' => 'showClick', 'class' => 'radio'), 1, ($row->show_click == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'showClick', 'name' => 'showClick', 'class' => 'radio'), 0, ($row->show_click == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span>Бодит хандалт: <?php echo $row->click_real_mn; ?>, Зохимол хандалт: </span>
                                </span>
                                <?php
                                echo form_input(array(
                                    'name' => 'clickMn',
                                    'id' => 'clickMn',
                                    'value' => $row->click_mn,
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
                        <?php echo form_label('Автор (Мэдээллийг нэмсэн)', 'Автор (Мэдээллийг нэмсэн)', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php echo $controlAuthorDropdown; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Агуулгыг харуулах загвар', 'Агуулгыг харуулах загвар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-10">
                            <?php echo $controlThemeLayoutRadio; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Вэб хаяг /url/', 'Вэб хаяг /url/', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
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

    </div>
</div>
<?php echo form_close(); ?>