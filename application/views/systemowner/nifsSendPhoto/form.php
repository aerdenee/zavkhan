<?php echo form_open('javascript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-send-photo', 'enctype' => 'multipart/form-data')); ?>

<?php
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('sendPhotoOldPic', $row->pic);
echo form_hidden('sendPhotoPic');
echo form_hidden('catId', $row->cat_id);
echo form_hidden('orderNum', $row->order_num);
?>
<div class="form-group row">
    <?php echo form_label('Фото зураг', 'Фото зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">

        <?php
        echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
        echo '<a href="javascript:;">';
        echo '<img src="' . $row->pic . '" class="_send-photo-image">';
        echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'nifs_send_photo\', formId: _nifsSendPhotoFormMainId, appendHtmlClass: \'._send-photo-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_NIFS_SEND_PHOTO_PATH, prefix: \'sendPhoto\'});">';
        echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
        echo '</span>';
        echo '<span class="_user-image-upload-button">';
        echo '<div class="uploader">';
        echo form_upload(array(
            'name' => 'sendPhotoPicUpload',
            'id' => 'sendPhotoPicUpload',
            'class' => 'pull-left file-styled',
            'onchange' => '_imageBigUpload({
                                        elem: this, 
                                        uploadPath: UPLOADS_NIFS_SEND_PHOTO_PATH, 
                                        formId: _nifsSendPhotoFormMainId, 
                                        appendHtmlClass: \'._send-photo-image\', 
                                        prefix: \'sendPhoto\'});',
        ));
        echo '<i class="icon-camera" style="user-select: none;"> <span class="_icon-text">зураг хуулах</span></i>';
        echo '</div>';
        echo '</span>';
        echo '</a>';
        echo '</div>';
        ?>
        <div class="clearfix"></div>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
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
</div>
<div class="form-group row">
    <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_textarea(array(
            'name' => 'description',
            'id' => 'description',
            'value' => $row->description,
            'rows' => 4,
            'class' => 'form-control'
        ));
        ?>
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
<?php echo form_close(); ?>