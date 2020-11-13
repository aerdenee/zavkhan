<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-ourteam', 'enctype' => 'multipart/form-data')); ?>
<?php
echo form_hidden('contId', $contId);
echo form_hidden('modId', $modId);
echo form_hidden('id', $row['id']);
echo form_hidden('type', $row['type']);
echo form_hidden('oldAttachFileMn', $row['attach_file_mn']);
?>
<div style="padding: 10px; display: block;"></div>
<div class="form-group media-form-photo" id="picMediaField">
    <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <div class="media no-margin-top">
            <div class="media-left">
                <?php
                $this->picSmall = UPLOADS_CONTENT_PATH . $row['attach_file_mn'];
                $this->picBig = UPLOADS_CONTENT_PATH . $row['attach_file_mn'];
                if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picSmall)) {
                    echo '<a href="' . $this->picBig . '" class="media-fancybox" data-fancybox-group="gallery">';
                    echo '<img src="' . $this->picSmall . '" style="width: 58px; height: 58px;" class="img-rounded">';
                    echo '</a>';
                } else {
                    echo '<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">';
                }
                ?>
            </div>

            <div class="media-body">
                <?php
                //echo '<div class="uploader">';
                echo form_upload(array(
                    'name' => 'attachFileMn',
                    'id' => 'attachFileMn',
                    'class' => 'pull-left file-styled'
                ));
                //echo '<span class="filename" style="user-select: none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
                //echo '</div>';
                ?>

                <br>
                <div class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?></div>
            </div>
        </div>
    </div>
</div>
<?php if ($row['id'] != ''): ?>
    <div class="clearfix"></div>
    <div class="form-group">
        <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'orderNum',
                'id' => 'orderNum',
                'value' => $row['order_num'],
                'maxlength' => '4',
                'class' => 'form-control integer order-num',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
<?php endif; ?>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabContentMediaMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabContentMediaEnglish" data-toggle="tab">English</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabContentMediaMongolia">
            
            <div class="form-group">
                <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-9">
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
            <div class="clearfix"></div>
            <div class="form-group">
                <?php echo form_label('Товч мэдээлэл', 'Товч мэдээлэл', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        'name' => 'titleMn',
                        'id' => 'titleMn',
                        'value' => $row['title_mn'],
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php
                $this->paramMnArray = json_decode($row['param_mn']);

                foreach ($this->paramMnArray as $paramKey => $paramRow) {

                    echo '<div class="form-group">';
                    echo form_label($paramRow->label, $paramRow->label, array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE));
                    echo '<div class="col-md-6">';
                    echo form_hidden('paramMnLabel[' . $paramKey . ']', $paramRow->label);
                    echo form_input(array(
                        'name' => 'paramMnValue[' . $paramKey . ']',
                        'id' => 'paramMnValue[' . $paramKey . ']',
                        'value' => $paramRow->value,
                        'maxlength' => '500',
                        'class' => 'form-control'
                    ));
                    echo '</div>';
                    echo '<div class="col-md-4">';
                    echo '<div class="radio-list">';
                    echo '<label class="radio-inline">';
                    echo form_radio(array('name' => 'paramMnShow[' . $paramKey . ']', 'class' => 'radio'), '1', ($paramRow->show == 1 ? TRUE : ''));
                    echo 'Нээх </label>';
                    echo '<label class="radio-inline">';
                    echo form_radio(array('name' => 'paramMnShow[' . $paramKey . ']', 'class' => 'radio'), '0', ($paramRow->show == 0 ? TRUE : ''));
                    echo 'Хаах </label>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
            <div class="clearfix"></div>
            <?php
                $this->socialArray = json_decode($row['social']);

                foreach ($this->socialArray as $paramKey => $paramRow) {

                    echo '<div class="form-group">';
                    echo form_label($paramRow->label, $paramRow->label, array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE));
                    echo '<div class="col-md-6">';
                    echo form_hidden('socialLabel[' . $paramKey . ']', $paramRow->label);
                    echo form_hidden('socialClass[' . $paramKey . ']', $paramRow->class);
                    echo form_hidden('socialShow[' . $paramKey . ']', $paramRow->show);
                    echo form_input(array(
                        'name' => 'social[' . $paramKey . ']',
                        'id' => 'social[' . $paramKey . ']',
                        'value' => $paramRow->address,
                        'maxlength' => '500',
                        'class' => 'form-control'
                    ));
                    echo '</div>';
                    echo '<div class="col-md-4">';
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
            <div class="clearfix"></div>
            <div class="form-group">
                <?php echo form_label('Тайлбар /Монгол/', 'Тайлбар /Монгол/', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-10">
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
        </div>
        <div class="tab-pane" id="tabContentMediaEnglish">
            <div class="form-group">
                <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-9">
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
            <div class="clearfix"></div>
            <div class="form-group">
                <?php echo form_label('Товч мэдээлэл', 'Товч мэдээлэл', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        'name' => 'titleEn',
                        'id' => 'titleEn',
                        'value' => $row['title_en'],
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php
                $this->paramEnArray = json_decode($row['param_en']);

                foreach ($this->paramEnArray as $paramKey => $paramRow) {

                    echo '<div class="form-group">';
                    echo form_label($paramRow->label, $paramRow->label, array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE));
                    echo '<div class="col-md-6">';
                    echo form_hidden('paramEnLabel[' . $paramKey . ']', $paramRow->label);
                    echo form_input(array(
                        'name' => 'paramEnValue[' . $paramKey . ']',
                        'id' => 'paramEnValue[' . $paramKey . ']',
                        'value' => $paramRow->value,
                        'maxlength' => '500',
                        'class' => 'form-control'
                    ));
                    echo '</div>';
                    echo '<div class="col-md-4">';
                    echo '<div class="radio-list">';
                    echo '<label class="radio-inline">';
                    echo form_radio(array('name' => 'paramEnShow[' . $paramKey . ']', 'class' => 'radio'), '1', ($paramRow->show == 1 ? TRUE : ''));
                    echo 'Нээх </label>';
                    echo '<label class="radio-inline">';
                    echo form_radio(array('name' => 'paramEnShow[' . $paramKey . ']', 'class' => 'radio'), '0', ($paramRow->show == 0 ? TRUE : ''));
                    echo 'Хаах </label>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
            
            <div class="clearfix"></div>
            <div class="form-group">
                <?php echo form_label('Тайлбар /English/', 'Тайлбар /English/', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-10">
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
        </div>
    </div>
</div>
<?php echo form_close(); ?>