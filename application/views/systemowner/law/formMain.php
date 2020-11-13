<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-main', 'enctype' => 'multipart/form-data')); ?>
<?php
echo form_hidden('id', $row['id']);
echo form_hidden('modId', $modId);
echo form_hidden('orderNum', $row['order_num']);
//echo form_hidden('imageCropTypeId', $row['image_crop_type']);

echo form_hidden('url', $row['url']);

?>
<div class="clearfix margin-top-20"></div>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabContentMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabContentEnglish" data-toggle="tab">English</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabContentMongolia">
            
            <div class="form-group">
                <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlCategoryListDropdown; ?>
                </div>
            </div>
            <?php if ($this->session->userdata['adminAccessTypeId'] == 1) { ?>
                <div class="form-group">
                    <?php echo form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-5">
                        <?php
                        echo $controlPartnerDropdown;
                        ?>
                    </div>
                </div>
            <?php }?>
            <div class="form-group">
                <?php echo form_label('Автор (Мэдээллийг нэмсэн)', 'Автор (Мэдээллийг нэмсэн)', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlAuthorDropdown; ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Дугаар', 'Дугаар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'lawNumber',
                        'id' => 'lawNumber',
                        'value' => $row['law_number'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Байгууллага', 'Байгууллага', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'lawOrganizationMn',
                        'id' => 'lawOrganizationMn',
                        'value' => $row['law_organization_mn'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Гарчиг /тэргүү/', 'Гарчиг /тэргүү/', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
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
                <?php echo form_label('Газар', 'Газар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'lawPlaceMn',
                        'id' => 'lawPlaceMn',
                        'value' => $row['law_place_mn'],
                        'maxlength' => '500',
                        'class' => 'form-control',
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
                            <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), '1', ($row['is_active_mn'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), '0', ($row['is_active_mn'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Огноо', 'Огноо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10 col-md-10">
                    <?php $isActiveDate = explode(' ', $row['is_active_date']); ?>
                    
                        <?php
                        echo form_input(array(
                            'name' => 'isActiveDate',
                            'id' => 'isActiveDate',
                            'value' => $isActiveDate['0'],
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true,
                            'style' => 'float:left; margin-right:10px;'
                        ));
                        ?>
                </div>
            </div>
            <div class="form-group hide">
                <?php echo form_label('Агуулгыг харуулах загвар', 'Агуулгыг харуулах загвар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10">
                    <?php echo $controlThemeLayoutRadio; ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Тайлбар', 'Тайлбар', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
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
            <div class="form-group">
                <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'fullTextMn',
                        'id' => 'fullTextMn',
                        'value' => $row['full_text_mn'],
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <?php echo form_label('Хуудасны гарчиг', 'Хуудасны гарчиг', array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'pageTitleMn',
                        'id' => 'pageTitleMn',
                        'value' => $row['page_title_mn'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Web browser-н title bar дээр харагдах үг</span>
                </div>
                <div class="col-md-6">
                    <?php echo form_label('Хуудасны гол агуулга', 'Хуудасны гол агуулга', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'h1TextMn',
                        'id' => 'h1TextMn',
                        'value' => $row['h1_text_mn'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">h1 text</span>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'metaKeyMn',
                        'id' => 'metaKeyMn',
                        'value' => $row['meta_key_mn'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Хайлтын системд бүртгүүлэх түлхүүр үгийг таслалаар тусгаарлан бичнэ.</span>

                </div>
                <div class="col-md-6">
                    <?php echo form_label('Хуудасны тайлбар', 'Хуудасны тайлбар', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'metaDescMn',
                        'id' => 'metaDescMn',
                        'value' => $row['meta_desc_mn'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Энэ хуудасны тухай товчхон 1 өгүүлбэр</span>
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
        <div class="tab-pane" id="tabContentEnglish">
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
                <?php echo form_label('Байгууллага', 'Байгууллага', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'lawOrganizationEn',
                        'id' => 'lawOrganizationEn',
                        'value' => $row['law_organization_en'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Гарчиг /тэргүү/', 'Гарчиг /тэргүү/', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'titleEn',
                        'id' => 'titleEn',
                        'value' => $row['title_en'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Газар', 'Газар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'lawPlaceEn',
                        'id' => 'lawPlaceEn',
                        'value' => $row['law_place_en'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Тайлбар', 'Тайлбар', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
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
            <div class="form-group">
                <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'fullTextEn',
                        'id' => 'fullTextEn',
                        'value' => $row['full_text_en'],
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <?php echo form_label('Хуудасны гарчиг', 'Хуудасны гарчиг', array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'pageTitleEn',
                        'id' => 'pageTitleEn',
                        'value' => $row['page_title_en'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Web browser-н title bar дээр харагдах үг</span>
                </div>
                <div class="col-md-6">
                    <?php echo form_label('Хуудасны гол агуулга', 'Хуудасны гол агуулга', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'h1TextEn',
                        'id' => 'h1TextEn',
                        'value' => $row['h1_text_en'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">h1 text</span>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'metaKeyEn',
                        'id' => 'metaKeyEn',
                        'value' => $row['meta_key_en'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Хайлтын системд бүртгүүлэх түлхүүр үгийг таслалаар тусгаарлан бичнэ.</span>

                </div>
                <div class="col-md-6">
                    <?php echo form_label('Хуудасны тайлбар', 'Хуудасны тайлбар', array('required' => 'required', 'class' => 'control-label text-right', 'defined' => FALSE)); ?>
                    <?php
                    echo form_textarea(array(
                        'name' => 'metaDescEn',
                        'id' => 'metaDescEn',
                        'value' => $row['meta_desc_en'],
                        'maxlength' => '500',
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                    <span class="help-block">Энэ хуудасны тухай товчхон 1 өгүүлбэр</span>
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
<?php echo form_close(); ?>

<script type="text/javascript">

    _uploadImageOldData = '<label for="Зураг" required="required" class="control-label col-lg-2 text-right" defined="1">Зураг: </label>';
    _uploadImageOldData += '<div class="col-lg-5">';
    _uploadImageOldData += '<div class="media no-margin-top">';
    _uploadImageOldData += '<div class="media-left">';
    _uploadImageOldData += '<img src="/assets/images/placeholder.jpg" style="width: 58px; height: 58px;" class="img-rounded">';
    _uploadImageOldData += '</div>';
    _uploadImageOldData += '<div class="media-body">';
    _uploadImageOldData += '<div class="uploader">';
    _uploadImageOldData += '<input type="file" name="picUpload" id="picUpload" class="pull-left file-styled" onchange="_uploadImage();">';
    _uploadImageOldData += '<span class="filename" style="user-select: none;">Файл сонгох</span>';
    _uploadImageOldData += '<span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
    _uploadImageOldData += '</div>';
    _uploadImageOldData += '<span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?></span>';
    _uploadImageOldData += '</div>';
    _uploadImageOldData += '</div>';
    _uploadImageOldData += '</div>';

</script>
