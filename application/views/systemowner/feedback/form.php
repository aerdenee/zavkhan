<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-feedback', 'enctype' => 'multipart/form-data')); ?>
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo $module->title;?></h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="<?php echo Sfeedback::$path . 'index/' . $modId; ?>"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>
            </ul>
        </div>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

    <div class="panel-body">

        <?php
        echo form_hidden('id', $row['id']);
        echo form_hidden('modId', $modId);
        echo form_hidden('parentId', $row['parent_id']);
        echo form_hidden('return', $row['return']);
        echo form_hidden('oldPic', $row['pic']);
        echo form_hidden('pic');
        echo form_hidden('crop_x');
        echo form_hidden('crop_y');
        echo form_hidden('crop_width');
        echo form_hidden('crop_height');
        ?>

        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="active"><a href="#tabFeedbackMongolia" data-toggle="tab">Монгол</a></li>
                <li><a href="#tabFeedbackEnglish" data-toggle="tab">English</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tabFeedbackMongolia">
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
                        <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php echo $controlCategoryListDropdown; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'lnameMn',
                                'id' => 'lnameMn',
                                'value' => $row['lname_mn'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Нэр', 'Нэр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'fnameMn',
                                'id' => 'fnameMn',
                                'value' => $row['fname_mn'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'phone',
                                'id' => 'phone',
                                'value' => $row['phone'],
                                'maxlength' => '500',
                                'class' => 'form-control'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'email',
                                'id' => 'email',
                                'value' => $row['email'],
                                'maxlength' => '255',
                                'class' => 'form-control'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Компьютерын IP хаяг', 'Компьютерын IP хаяг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'ipAddress',
                                'id' => 'ipAddress',
                                'value' => $row['ip_address'],
                                'maxlength' => '255',
                                'class' => 'form-control control-ip-address'
                            ));
                            ?>
                        </div>
                    </div>

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
                        <?php echo form_label('Вэб хаяг /url/', 'Вэб хаяг /url/', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'url',
                                'id' => 'url',
                                'value' => $row['url'],
                                'maxlength' => '255',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
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
                                'maxlength' => '255',
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
                                    <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), 1, ($row['is_active_mn'] == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), 0, ($row['is_active_mn'] == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Нийтлэх огноо', 'Нийтлэх огноо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php $date = explode(' ', $row['is_active_date']); ?>
                            <div class="input-group date date-time control-date" id="is_active_date">
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
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
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
                        <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
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

                    <div class="clearfix"></div>    
                    <div class="col-md-6 text-left">
                    </div>
                    <div class="col-md-6 text-right">
                        <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({modId: ' . $modId . ', mode: \'' . $mode . '\'});"', 'button'); ?>
                    </div>
                    <div class="clearfix"></div>

                </div>
                <div class="tab-pane" id="tabFeedbackEnglish">

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
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'lnameEn',
                                'id' => 'lnameEn',
                                'value' => $row['lname_en'],
                                'maxlength' => '500',
                                'class' => 'form-control'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'fnameEn',
                                'id' => 'fnameEn',
                                'value' => $row['fname_en'],
                                'maxlength' => '500',
                                'class' => 'form-control'
                            ));
                            ?>
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
                        <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
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

    </div>
</div>
<?php echo form_close(); ?>