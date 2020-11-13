
<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-media-video-photo', 'enctype' => 'multipart/form-data')); ?>
<?php

echo form_hidden('contId', $contId);
echo form_hidden('modId', $modId);
echo form_hidden('id', $row['id']);
echo form_hidden('type', $row['type']);
echo form_hidden('oldAttachFileMn', $row['attach_file_mn']);
echo form_hidden('oldAttachFileEn', $row['attach_file_en']);
echo form_hidden('oldFileTypeMn', $row['file_type_mn']);
echo form_hidden('oldFileTypeEn', $row['file_type_en']);
$this->isFileBrowser = false;
if ($row['type'] == 1 or $row['type'] == 2) {
    $this->isFileBrowser = true;
}
?>
<div style="padding: 10px; display: block;"></div>
<div class="form-group">
    <?php echo form_label('Файлын төрөл', 'Файлын төрөл', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('name' => 'raiodType', 'class' => 'radio'), 1, ($row['type'] == 1 ? TRUE : '')); ?>
                Зураг </label>
            <label class="radio-inline">
                <?php echo form_radio(array('name' => 'raiodType', 'class' => 'radio'), 3, ($row['type'] == 3 ? TRUE : '')); ?>
                Видео </label>
            <label class="radio-inline">
                <?php echo form_radio(array('name' => 'raiodType', 'class' => 'radio'), 2, ($row['type'] == 2 ? TRUE : '')); ?>
                Файл </label>
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
            <div class="form-group media-form-video" <?php echo ($this->isFileBrowser == false ? 'style="display: block;"' : 'style="display: none;"'); ?>>
                <?php echo form_label('Видео хаяг', 'Видео хаяг', array('class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        'name' => 'videoIdMn',
                        'id' => 'videoIdMn',
                        'value' => $row['attach_file_mn'],
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group media-form-photo" id="picMediaField" <?php echo ($this->isFileBrowser == true ? 'style="display: block;"' : 'style="display: none;"'); ?>>
                <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-9">
                    <div class="media no-margin-top">
                        <div class="media-left">
                            <?php
                            $this->picSmallMn = UPLOADS_CONTENT_PATH . CROP_SMALL . $row['attach_file_mn'];
                            $this->picBigMn = UPLOADS_CONTENT_PATH . CROP_MEDIUM . $row['attach_file_mn'];
                            if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picSmallMn)) {
                                echo '<a href="' . $this->picBigMn . '" class="media-fancybox" data-fancybox-group="gallery">';
                                echo '<img src="' . $this->picSmallMn . '" style="width: 58px; height: 58px;" class="img-rounded">';
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
                            <div class="help-block">
                                Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?>
                                <br>
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
            <div class="form-group media-form-video" <?php echo ($row['type'] == 3 ? 'style="display: block;"' : 'style="display: none;"'); ?>>
                <?php echo form_label('Видео хаяг', 'Видео хаяг', array('class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        'name' => 'videoIdEn',
                        'id' => 'videoIdEn',
                        'value' => $row['attach_file_en'],
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group media-form-photo" id="picMediaField" <?php echo ($row['type'] == 1 ? 'style="display: block;"' : 'style="display: none;"'); ?>>
                <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-9">
                    <div class="media no-margin-top">
                        <div class="media-left">
                            <?php
                            $this->picSmallEn = UPLOADS_CONTENT_PATH . CROP_SMALL . $row['attach_file_en'];
                            $this->picBigEn = UPLOADS_CONTENT_PATH . CROP_MEDIUM . $row['attach_file_en'];
                            if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picSmallEn)) {
                                echo '<a href="' . $this->picBigEn . '" class="media-fancybox" data-fancybox-group="gallery">';
                                echo '<img src="' . $this->picSmallEn . '" style="width: 58px; height: 58px;" class="img-rounded">';
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
                                'name' => 'attachFileEn',
                                'id' => 'attachFileEn',
                                'class' => 'pull-left file-styled'
                            ));
                            //echo '<span class="filename" style="user-select: none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
                            //echo '</div>';
                            ?>

                            <br>
                            <div class="help-block">
                                Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?>
                                <br>
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

<script type="text/javascript">

    $(function () {
        $('input[name="raiodType"]', '#form-media-video-photo').on('click', function (event) {
            var _this = $(this);
            $("input[name='type']", '#form-media-video-photo').val(_this.val());
            if (_this.val() == 3) {
                $("#videoIdMn", '#form-media-video-photo').val('');
                $("#videoIdEn", '#form-media-video-photo').val('');
                $(".media-form-video", '#form-media-video-photo').show();
                $(".media-form-photo", '#form-media-video-photo').hide();
            } else {
                $(".media-form-video", '#form-media-video-photo').hide();
                $(".media-form-photo", '#form-media-video-photo').show();
            }
        });

        $('.media-fancybox').fancybox({
            helpers: {
                title: null,
                overlay: {
                    speedOut: 0
                }
            }
        });

    });
</script>