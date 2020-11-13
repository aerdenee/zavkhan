<?php
echo form_open('', array('class' => 'form-horizontal col-12', 'id' => 'form-hr-people-report', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('attachFile', '');
echo form_hidden('oldAttachFile', $row->attach_file);
echo form_hidden('orderNum', $row->order_num);
echo form_hidden('peopleId', $row->people_id);

?>
<div class="form-group row">
    <?php echo form_label('Хавсралт файл', 'Хавсралт файл', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-7">
        <div class="media no-margin-top">
            <div class="media-body">
                <div class="uploader">
                    <input type="file" name="attachFileUpload" id="attachFileUpload" class="pull-left file-styled" onchange="_fileUpload({table: 'content_media', selectedId:<?php echo $row->id; ?>, elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _contentMediaFormMainId, appendHtmlClass: 'attach-file-html'});">
                    <span class="filename" style="user-select: none; display:none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>
                </div>
                <span class="help-block">
                    Хуулах боломжтой файл: <?php echo formatInFileExtension(UPLOAD_PDF_TYPE); ?>  
                    Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?> 
                    <span class="attach-file-html"></span>
                    <span id="html-attach-file"></span>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Хамрах хугацаа', 'Хамрах хугацаа', array('required' => 'required', 'class' => 'col-2 col-form-label text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
        <?php
        echo form_input(array(
            'name' => 'reportInDate',
            'id' => 'reportInDate',
            'value' => $row->in_date,
            'maxlength' => '10',
            'class' => 'form-control init-date full-left mr-2',
            'required' => 'required',
            'readonly' => true
        ));

        echo form_input(array(
            'name' => 'reportOutDate',
            'id' => 'reportOutDate',
            'value' => $row->out_date,
            'maxlength' => '10',
            'class' => 'form-control init-date full-left',
            'required' => 'required',
            'readonly' => true
        ));
        ?>
        <div class="clearfix"></div>
        <span class="help-block"><i class="icon-help"></i> <span>Тайланд хамаарах хугацаа.</span></span>
    </div>
</div>


<div class="form-group row">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
        <?php
        echo form_input(array(
            'name' => 'reportTitle',
            'id' => 'reportTitle',
            'value' => $row->title,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
        <?php
        echo form_textarea(array(
            'name' => 'reportDescription',
            'id' => 'reportDescription',
            'value' => $row->description,
            'rows' => 4,
            'class' => 'form-control ckeditor'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>