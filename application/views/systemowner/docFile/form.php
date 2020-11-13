<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-doc-file', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('docId', $row->doc_id);
echo form_hidden('attachFile', '');
echo form_hidden('oldAttachFile', $row->attach_file);
echo form_hidden('mimeType', '');
echo form_hidden('oldMimeType', $row->mime_type);
echo form_hidden('fileSize', '');
echo form_hidden('oldFileSize', $row->file_size);
?>

<div class="form-group row">
    <?php echo form_label('Файл', 'Файл', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <div class="media no-margin-top">
            <div class="media-body">
                <div class="uploader">
                    <input type="file" name="attachFileUpload" id="attachFileUpload" class="pull-left file-styled" onchange="_fileUpload({table: 'doc_file', selectedId: 0, elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _docFileFormMainId, appendHtmlClass: 'attach-file-name'});">
                    <span class="filename" style="user-select: none; display:none;">Файл сонгох</span>
                    <span class="btn btn-primary btn-file" style="user-select: none; padding: auto;"><i class="icon-file-plus"></i> Файл хуулах</span>
                </div>
                <span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_ALL_FILE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?> <span class="attach-file-html"></span></span>
                <div class="attach-file-name">
                    <?php 
                    if ($row->attach_file != '') {
                        echo $row->attach_file . ' ' . '<span class="badge bg-danger" style="cursor:pointer;" onclick="_fileDelete({table: \'doc_file\', formId: \'#form-doc-file\', selectedId:' . $row->id . ', uploadPath: \'/upload/image/\', appendHtmlClass: \'attach-file-name\', fileName: ($(\'input[name=attachFile]\').val() != \'\' ? $(\'input[name=attachFile]\').val() : $(\'input[name=oldAttachFile]\').val())});"><i class="fa fa-close"></i></span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
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

