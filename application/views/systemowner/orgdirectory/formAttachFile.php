<?php 
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-attach-file', 'enctype' => 'multipart/form-data'));

echo form_hidden('contId', $contId);
echo form_hidden('modId', $modId);
echo form_hidden('id', $row['id']);
echo form_hidden('type', $row['type']);
echo form_hidden('oldAttachFileMn', $row['attach_file_mn']);
echo form_hidden('oldAttachFileEn', $row['attach_file_en']);

?>
<div style="padding: 10px; display: block;"></div>
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
            <div class="form-group media-form-photo">
                <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-9">
                    <div class="media no-margin-top">
                        <div class="media-left">
                            <?php echo '<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">';?>
                        </div>

                        <div class="media-body">
                            <?php
                            echo form_upload(array(
                                'name' => 'attachFileMn',
                                'id' => 'attachFileMn',
                                'class' => 'pull-left file-styled'
                            ));
                            ?>

                            <br>
                            <div class="help-block">
                                Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_OFFICE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?><br>
                                <?php echo $row['attach_file_mn'];?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <?php echo form_label('Гарчиг /Монгол/', 'Гарчиг /Монгол/', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
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
            <div class="form-group media-form-photo">
                <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-9">
                    <div class="media no-margin-top">
                        <div class="media-left">
                            <?php echo '<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">';?>
                        </div>

                        <div class="media-body">
                            <?php
                            echo form_upload(array(
                                'name' => 'attachFileEn',
                                'id' => 'attachFileEn',
                                'class' => 'pull-left file-styled'
                            ));
                            ?>

                            <br>
                            <div class="help-block">
                                Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_OFFICE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?><br>
                                <?php echo $row['attach_file_en'];?>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
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
                <?php echo form_label('Гарчиг /Монгол/', 'Гарчиг /Монгол/', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
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