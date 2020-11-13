<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-content-media', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('contId', $row->cont_id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('pic', '');
echo form_hidden('oldPic', ltrim($row->pic, CROP_SMALL));
echo form_hidden('attachFile', '');
echo form_hidden('oldAttachFile', $row->attach_file);
echo form_hidden('mimeType', '');
echo form_hidden('oldMimeType', $row->mime_type);
echo form_hidden('fileSize', '');
echo form_hidden('oldFileSize', $row->file_size);
?>
<div class="clearfix margin-top-20"></div>
<div class="form-group row">
    <?php echo form_label('Медиа төрөл', 'Медиа төрөл', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php echo $controlMediaTypeRadioButton; ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('name' => 'isActive', 'class' => 'radio'), 1, ($row->is_active == 1 ? TRUE : FALSE)); ?>
                Нээх </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('name' => 'isActive', 'class' => 'radio'), 0, ($row->is_active == 0 ? TRUE : FALSE)); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Фото зураг', 'Фото зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
        echo '<a href="javascript:;">';
        echo '<img src="' . UPLOADS_CONTENT_PATH . $row->pic . '" class="_profile-image">';
        echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'content_media\', formId: _contentMediaFormMainId, appendHtmlClass: \'._profile-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, fileName: ($(\'input[name=pic]\').val() != \'\' ? $(\'input[name=pic]\').val() : $(\'input[name=oldPic]\').val())});">';
        echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
        echo '</span>';
        echo '<span class="_user-image-upload-button">';
        echo '<div class="uploader">';
        echo form_upload(array(
            'name' => 'picUpload',
            'id' => 'picUpload',
            'class' => 'pull-left file-styled',
            'onchange' => '_imageUpload({elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _contentMediaFormMainId, appendHtmlClass: \'._profile-image\', table: \'content_media\', selectedId: ' . $row->id . ', oldPic: ($(\'input[name=pic]\').val() != \'\' ? $(\'input[name=pic]\').val() : $(\'input[name=oldPic]\').val())});',
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

<span id="html-attach-file"></span>

<div class="form-group row">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $row->title,
            'maxlength' => '500',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Өдөр', 'өдөр', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-3">
        <?php
        echo form_input(array(
            'name' => 'orderNum',
            'id' => 'orderNum',
            'value' => $row->order_num,
            'maxlength' => '500',
            'class' => 'form-control',
            'style' => 'display:inline-block; width:40px; margin-right:10px;'
        ));
        ?>
        дэхь өдөр
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Хоол', 'Хоол', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'food',
            'id' => 'food',
            'value' => $row->food,
            'maxlength' => '500',
            'class' => 'form-control',
            'style' => 'display:inline-block; width:200px; margin-right:10px;'
        ));
        ?>
        B - Breakfast, L - Lunch, D - Dinner
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Тээвэр', 'Тээвэр', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        $transportationDefault = json_decode(json_encode(array('car', 'bus', 'bike', 'flight', 'train', 'horse')));

        function trourItineraryAccessories($param = array()) {
            
            if ($param['data'] != 'null') {
                $data = json_decode($param['data']);
                foreach ($data as $row) {
                    if ($row == $param['value']) {
                        return true;
                    }
                }
            }

            return false;
        }

        foreach ($transportationDefault as $rowTransportation) {
            echo '<div class="form-check form-check-inline">';
            echo '<label class="form-check-label">';
            echo form_checkbox(array('name' => 'transportation[]', 'class' => 'radio'), $rowTransportation, (trourItineraryAccessories(array('value' => $rowTransportation, 'data' => $row->transportation)) ? TRUE : FALSE));
            echo '<img src="assets/system/icons/svg/' . $rowTransportation . '.svg" style="width:24px; display:inline-block; paddin-top:0.00002rem;">';
            echo '</label>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Амрах газар', 'Амрах газар', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        $accommodationDefault = json_decode(json_encode(array('home', 'hotel', 'tent', 'train')));
        foreach ($accommodationDefault as $rowAccommodation) {
            echo '<div class="form-check form-check-inline">';
            echo '<label class="form-check-label">';
            echo form_checkbox(array('name' => 'accommodation[]', 'class' => 'radio'), $rowAccommodation, (trourItineraryAccessories(array('value' => $rowAccommodation, 'data' => $row->accommodation)) ? TRUE : FALSE));
            echo '<img src="assets/system/icons/svg/' . $rowAccommodation . '.svg" style="width:24px; display:inline-block; paddin-top:0.00002rem;">';
            echo '</label>';
            echo '</div>';
        }
        ?>
    </div>
</div>


<div class="form-group row">
    <?php echo form_label('Бусад үзвэр', 'Бусад үзвэр', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        $otherDefault = json_decode(json_encode(array('horse')));
        foreach ($otherDefault as $rowOther) {
            echo '<div class="form-check form-check-inline">';
            echo '<label class="form-check-label">';
            echo form_checkbox(array('name' => 'other[]', 'class' => 'radio'), $rowOther, (trourItineraryAccessories(array('value' => $rowOther, 'data' => $row->other)) ? TRUE : FALSE));
            echo '<img src="assets/system/icons/svg/' . $rowOther . '.svg" style="width:24px; display:inline-block; paddin-top:0.00002rem;">';
            echo '</label>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12">
        <?php
        echo form_textarea(array(
            'name' => 'mediaIntroText',
            'id' => 'mediaIntroText',
            'value' => $row->intro_text,
            'rows' => 4,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">

    $(function () {
        $('input[name="mediaType"]').on('click', function () {

            var _this = $(this);

            if (_this.val() == 2) {

                $('#html-attach-file').html(_controlFileUpload({labelText: 'MP4 видео', fileName: '<?php echo $row->attach_file; ?>'}));

            } else if (_this.val() == 3) {

                $('#html-attach-file').html(_controlInputText({labelText: 'Youtube video', fileName: '<?php echo $row->attach_file; ?>'}));

            } else if (_this.val() == 4) {

                $('#html-attach-file').html(_controlFileUpload({labelText: 'Document файл', fileName: '<?php echo $row->attach_file; ?>'}));

            } else {

                $('#html-attach-file').empty();

            }

        });

        $('input[name="mediaType"][value="<?php echo $row->media_type_id; ?>"]').prop('checked', true).trigger('click');

    });

    function _controlFileUpload(param) {

        var _stringHtml = '<div class="form-group row">';
        _stringHtml += '<label for="' + param.labelText + '" required="required" class="control-label col-md-2 text-right" defined="1">' + param.labelText + ': </label>';
        _stringHtml += '<div class="col-md-7">';
        _stringHtml += '<div class="media no-margin-top">';
        _stringHtml += '<div class="media-body">';
        _stringHtml += '<div class="uploader">';
        _stringHtml += '<input type="file" name="attachFileUpload" id="attachFileUpload" class="pull-left file-styled" onchange="_fileUpload({table:\'content_media\', selectedId:<?php echo $row->id; ?>, elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _contentMediaFormMainId, appendHtmlClass: \'attach-file-html\'});">';
        _stringHtml += '<span class="filename" style="user-select: none; display:none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
        _stringHtml += '</div>';
        _stringHtml += '<span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_ALL_FILE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?> <span class="attach-file-html">' + (param.fileName != '' ? param.fileName + ' <span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'content_media\', formId: _contentMediaFormMainId, selectedId:<?php echo $row->id; ?>, uploadPath: UPLOADS_CONTENT_PATH, appendHtmlClass: \'attach-file-html\', fileName: ($(\'input[name=attachFile]\').val() != \'\' ? $(\'input[name=attachFile]\').val() : $(\'input[name=oldAttachFile]\').val())});"><i class="fa fa-close"></i></span>' : '') + '</span></span>';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        return _stringHtml;

    }
    function _controlInputText(param) {

        var _stringHtml = '<div class="form-group row">';
        _stringHtml += '<label for="' + param.labelText + '" required="required" class="control-label col-md-2 text-right" defined="1">' + param.labelText + ': </label>';
        _stringHtml += '<div class="col-md-7">';
        _stringHtml += '<input type="text" onchange="_setVideoValue({elem:this});" name="attachFileUpload" value="' + param.fileName + '" id="attachFileUpload" maxlength="500" class="form-control" required="required">';
        _stringHtml += '</div>';
        _stringHtml += '</div>';
        return _stringHtml;

    }

    function _setVideoValue(param) {
        var _this = $(param.elem);
        $('input[name="attachFile"]').val(_this.val());
        $('input[name="oldAttachFile"]').val('');
        $('input[name="mimeType"]').val('');
        $('input[name="oldMimeType"]').val('');
        $('input[name="fileSize"]').val('');
        $('input[name="oldFileSize"]').val('');

    }
</script>