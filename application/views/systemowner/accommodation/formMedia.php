<div class="col-md-12">
    <?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-media-video-photo', 'enctype' => 'multipart/form-data')); ?>
    <?php
    echo form_hidden('contId', $contId);
    echo form_hidden('modId', $modId);
    echo form_hidden('id', $row['id']);
    echo form_hidden('type', $row['type']);
    echo form_hidden('oldAttachFileMn', $row['attach_file_mn']);
    ?>
    <div class="form-group">
        <?php echo form_label('Файлын төрөл', 'Файлын төрөл', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined'=>TRUE)); ?>
        <div class="col-md-8">
            <div class="radio-list">
                <label class="radio-inline">
                    <?php echo form_radio(array('name' => 'raiodType'), '1', ($row['type'] === '1' ? TRUE : '')); ?>
                    Зураг </label>
                <label class="radio-inline">
                    <?php echo form_radio(array('name' => 'raiodType'), '3', ($row['type'] === '3' ? TRUE : '')); ?>
                    Видео </label>
            </div>
        </div>
    </div>
    <div class="form-group media-form-video" <?php echo ($row['type'] === '3' ? 'style="display: block;"' : 'style="display: none;"'); ?>>
        <?php echo form_label('Видео хаяг', 'Видео хаяг', array('class' => 'control-label col-md-3 text-right', 'defined'=>TRUE)); ?>
        <div class="col-md-8">
            <?php
            echo form_input(array(
                'name' => 'videoId',
                'id' => 'videoId',
                'value' => $row['attach_file_' . $this->session->adminLangCode],
                'class' => 'form-control'
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group media-form-photo" <?php echo ($row['type'] === '1' ? 'style="display: block;"' : 'style="display: none;"'); ?>>
        <?php echo form_label('', '', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined'=>FALSE)); ?>
        <div class="col-md-8">
            <?php 
            $path = UPLOADS_CONTENT_PATH . CROP_SMALL; 
            $pic = $path . $row['attach_file_mn'];
            ?>
            <img src="<?php echo (is_file($_SERVER['DOCUMENT_ROOT'] . $pic) ? $pic : 'assets/images/icon-pic.png'); ?>">
        </div>
    </div>
    <div class="form-group media-form-photo"  <?php echo ($row['type'] === '1' ? 'style="display: block;"' : 'style="display: none;"'); ?>>
        <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined'=>TRUE)); ?>
        <div class="col-md-8">
            <?php
            echo form_upload(array(
                'name' => 'attachFileMn[]',
                'id' => 'attachFileMn',
                'class' => 'pull-left',
                'multiple' => 'multiple'
            ));
            ?>
            <div class="clearfix"></div>
            <div class="help-block">Зөвшөөрөгдсөн зураг: <?php echo UPLOAD_IMAGE_TYPE;?></div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined'=>TRUE)); ?>
        <div class="col-md-8">
            <div class="radio-list">
                <label class="radio-inline">
                    <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive'), '1', ($row['is_active'] === '1' ? TRUE : '')); ?>
                    Нээх </label>
                <label class="radio-inline">
                    <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive'), '0', ($row['is_active'] === '0' ? TRUE : '')); ?>
                    Хаах </label>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <?php echo form_label('Тайлбар /Монгол/', 'Тайлбар /Монгол/', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined'=>TRUE)); ?>
        <div class="col-md-8">
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
        <?php echo form_label('Тайлбар /English/', 'Тайлбар /English/', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined'=>TRUE)); ?>
        <div class="col-md-8">
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
    <?php if($row['id'] != ''): ?>
    <div class="form-group">
        <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined'=>TRUE)); ?>
        <div class="col-md-8">
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
    <?php endif;?>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    var parentMediaFormId = '#form-media-video-photo';
    $(function () {
        $('input[name="raiodType"]', parentMediaFormId).on('click', function (event) {
            if ($(this).val() === '3') {
                $("#videoId", parentMediaFormId).val('');
                $(".media-form-video", parentMediaFormId).show();
                $(".media-form-photo", parentMediaFormId).hide();
                $("input[name='type']", parentMediaFormId).val('3');
            } else {
                $(".media-form-video", parentMediaFormId).hide();
                $(".media-form-photo", parentMediaFormId).show();
                $("input[name='type']", parentMediaFormId).val('1');
            }
        });

    });
</script>