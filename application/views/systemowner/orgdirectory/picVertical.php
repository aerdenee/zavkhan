<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-pic-vertical', 'enctype' => 'multipart/form-data'));

echo form_hidden('id', $row['id']);
echo form_hidden('verticalOldPic', $row['pic_vertical']);
echo form_hidden('verticalPic');
echo form_hidden('verticalCropX');
echo form_hidden('verticalCropY');
echo form_hidden('verticalCropWidth');
echo form_hidden('verticalCropHeight');
?>
<div class="form-group" id="picVerticalField">
    <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-lg-5">
        <div class="media no-margin-top">
            <div class="media-left">
                <?php
                $this->picSmall = UPLOADS_CONTENT_PATH . CROP_SMALL . $row['pic_vertical'];
                $this->picBig = UPLOADS_CONTENT_PATH . CROP_MEDIUM . $row['pic_vertical'];
                if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picSmall)) {
                    echo '<div style="position: relative; display: inline-block;">';
                    echo '<a href="' . $this->picBig . '" class="fancybox-vertical-image" data-fancybox-group="gallery">';
                    echo '<img src="' . $this->picSmall . '" style="width: 58px; height: 58px;" class="img-rounded">';
                    echo '</a>';
                    echo '<span class="badge bg-danger" style="position: absolute; bottom: -8px; right: -8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_removeVerticalImage({image:\'' . $row['pic_vertical'] . '\'});"><i class="fa fa-close"></i></span>';
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
                    'onchange' => '_uploadVerticalImage();',
                ));
                echo '<span class="filename" style="user-select: none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
                echo '</div>';
                ?>
                <span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?></span>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    _uploadVerticalImageOldData = $('#picVerticalField').html();
    function _tempVerticalImageControl(param) {
        console.log(param);
        var _html = '';
        _html += '<label for="Зураг" required="required" class="control-label col-lg-2 text-right" defined="1">Зураг: </label>';
        _html += '<div class="col-lg-5">';
        _html += '<div class="media no-margin-top">';
        _html += '<div class="media-left">';
        if (param.image != '') {
            var _picVerticalBig = '<?php echo UPLOADS_CONTENT_PATH . CROP_MEDIUM; ?>' + param.image;
            var _picVerticalSmall = '<?php echo UPLOADS_CONTENT_PATH . CROP_SMALL; ?>' + param.image;
            _html += '<div style="position: relative; display: inline-block;">';
            _html += '<a href="' + _picVerticalBig + '" class="fancybox-vertical-image" data-fancybox-group="gallery">';
            _html += '<img src="' + _picVerticalSmall + '" style="width: 58px; height: 58px;" class="img-rounded">';
            _html += '</a>';
            _html += '<span class="badge bg-danger" style="position: absolute; bottom: -8px; right: -8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_removeVerticalImage({image:\'' + param.image + '\'});"><i class="fa fa-close"></i></span>';
            _html += '</div>';
        } else {
            _html += '<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">';
        }
        _html += '</div>';
        _html += '<div class="media-body">';
        _html += '<div class="uploader">';
        _html += '<input type="file" name="picUpload" id="picUpload" class="pull-left file-styled" onchange="_uploadVerticalImage();">';
        _html += '<span class="filename" style="user-select: none;">Файл сонгох</span>';
        _html += '<span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
        _html += '</div>';
        _html += '<span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?></span>';
        _html += '</div>';
        _html += '</div>';
        _html += '</div>';
        return _html;
    }
</script>
