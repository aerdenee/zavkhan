<?php
echo form_open('', array('class' => 'form-vertical', 'id' => 'form-student', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row['id']);
echo form_hidden('modId', $row['mod_id']);

echo form_hidden('oldPic', $row['pic']);
echo form_hidden('pic');
echo form_hidden('crop_x');
echo form_hidden('crop_y');
echo form_hidden('crop_width');
echo form_hidden('crop_height');
echo form_hidden('orderNum', $row['order_num']);

if (!$this->input->is_ajax_request()) {
    echo '<div class="panel panel-flat">';
    echo '<div class="panel-heading">';
    echo '<h5 class="panel-title">' . $module->title . '</h5>';
    echo '<div class="heading-elements">';
    echo '<ul class="icons-list">';
    echo '<li><a href="' . Sstudent::$path . 'index/' . $modId . '"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>';
    echo '</ul>';
    echo '</div>';
    echo '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
    echo '</div>';

    echo '<div class="panel-body">';
}
?>
<style type="text/css">
    .nav-tabs {
        margin-bottom: 0;
    }
    .uploader input[type=file] {
        width: 30px;
        height: 36px;
        bottom: 8px;
        right: 30px;
    }
    .btn-rounded {
        border-radius: 100px !important;
    }
    .btn-xs, .btn-group-xs > .btn {
        padding: 3px 8px !important;
        font-size: 11px !important;
        line-height: 13px !important;
    }
</style>
<div class="col-md-2">

    <div class="form-group text-right" id="picField">
        <div class="media">
            <div style="position: relative; display: inline-block;">
                <?php
                if ($mode == 'insert') {
                    echo '<a href="javascript:;">';
                    echo '<img src="' . UPLOADS_USER_PATH . 'student.jpg" style="width: 100%; height: 140px; " class="img-rounded">';
                    echo '</a>';
                    echo '<div class="uploader">';
                    echo form_upload(array(
                        'name' => 'picUpload',
                        'id' => 'picUpload',
                        'class' => 'pull-left file-styled',
                        'onchange' => '_uploadImageStudent();',
                    ));
                    echo '<span class="image-btn bg-blue" style="position: absolute; bottom: 8px; right: 8px; border: 1px solid rgba(255,255,255,0.6); border-radius: 3px; padding-left: 3px; padding-right: 3px; cursor:pointer; user-select: none;"><i class="fa fa-upload"></i></span>';
                    echo '</div>';
                } else {
                    if (is_file($_SERVER['DOCUMENT_ROOT'] . UPLOADS_USER_PATH . CROP_LARGE . $row['pic'])) {
                        echo '<a href="' . UPLOADS_USER_PATH . CROP_LARGE . $row['pic'] . '" class="fancybox" data-fancybox-group="gallery">';
                        echo '<img src="' . UPLOADS_USER_PATH . CROP_SMALL . $row['pic'] . '" style="width: 100%; height: 140px; " class="img-rounded">';
                        echo '</a>';
                    } else {
                        echo '<a href="javascript:;">';
                        echo '<img src="' . UPLOADS_USER_PATH . 'student.jpg" style="width: 100%; height: 140px; " class="img-rounded">';
                        echo '</a>';
                    }

                    echo '<div class="uploader">';
                    echo form_upload(array(
                        'name' => 'picUpload',
                        'id' => 'picUpload',
                        'class' => 'pull-left file-styled',
                        'onchange' => '_uploadImageStudent();',
                    ));
                    echo '<span class="image-btn bg-blue" style="position: absolute; bottom: 8px; right: 30px; border: 1px solid rgba(255,255,255,0.6); border-radius: 3px; padding-left: 3px; padding-right: 3px; cursor:pointer; user-select: none;"><i class="fa fa-upload"></i></span>';
                    echo '</div>';
                    echo '<span class="image-btn bg-danger" style="position: absolute; bottom: 8px; right: 8px; border: 1px solid rgba(255,255,255,0.6); border-radius: 3px; padding-left: 3px; padding-right: 3px; cursor:pointer;" onclick="_removeImageStudent({pic:' . $row['pic'] . '});"><i class="fa fa-close"></i></span>';
                }
                ?>
            </div>

            <?php
//                    $this->picSmall = UPLOADS_USER_PATH . CROP_SMALL . $row['pic'];
//                    $this->picBig = UPLOADS_USER_PATH . CROP_MEDIUM . $row['pic'];
//                    if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picSmall)) {
//                        echo '<div style="position: relative; display: inline-block;">';
//                        echo '<a href="' . $this->picBig . '" class="fancybox" data-fancybox-group="gallery">';
//                        echo '<img src="' . $this->picSmall . '" style="width: 58px; height: 58px;" class="img-rounded">';
//                        echo '</a>';
//                        echo '<span class="badge bg-danger" style="position: absolute; bottom: -8px; right: -8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_removeImage(\'' . $row['pic'] . '\');"><i class="fa fa-close"></i></span>';
//                        echo '</div>';
//                    } else {
//                        echo '<img src="/assets/images/placeholder.jpg" style="width: 120px; height: 160px;" class="img-rounded">';
//                    }
            ?>



        </div>
    </div>
</div>
<div class="col-md-10">
    <div class="tabbable">
        <ul class="nav nav-tabs nav-tabs-bottom">
            <li class="active"><a href="#student-main" data-toggle="tab">Үндсэн мэдээлэл</a></li>
            <li><a href="#student-class" data-toggle="tab">Сургалт</a></li>
            <li><a href="#student-finance" data-toggle="tab">Төлбөр</a></li>
            <li><a href="#student-other" data-toggle="tab">Бусад</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="student-main">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo form_label('Оюутны код', 'Оюутны код', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE)); ?>
                            <?php
                            echo form_input(array(
                                'name' => 'code',
                                'id' => 'code',
                                'value' => $row['code'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                            echo form_input(array(
                                'name' => 'lname',
                                'id' => 'lname',
                                'value' => $row['lname'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE)); ?>
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'sex', 'name' => 'sex', 'class' => 'radio'), '1', ($row['sex'] == 1 ? TRUE : '')); ?>
                                    Эрэгтэй </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'sex', 'name' => 'sex', 'class' => 'radio'), '0', ($row['sex'] == 0 ? TRUE : '')); ?>
                                    Эмэгтэй </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php
                            echo form_label('Гар утас', 'Гар утас', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                            echo form_input(array(
                                'name' => 'mobile',
                                'id' => 'mobile',
                                'value' => $row['mobile'],
                                'maxlength' => '500',
                                'class' => 'form-control'
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                            echo form_input(array(
                                'name' => 'phone',
                                'id' => 'phone',
                                'value' => $row['phone'],
                                'maxlength' => '500',
                                'class' => 'form-control'
                            ));
                            ?>
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php
                            echo form_label('Төрсөн он, сар, өдөр', 'Төрсөн он, сар, өдөр', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                            echo form_input(array(
                                'name' => 'birthday',
                                'id' => 'birthday',
                                'value' => $row['birthday'],
                                'maxlength' => '10',
                                'class' => 'form-control init-date',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                            echo form_input(array(
                                'name' => 'fname',
                                'id' => 'fname',
                                'value' => $row['fname'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo form_label('Ам бүл', 'Ам бүл', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                            echo form_input(array(
                                'name' => 'familyMemberCount',
                                'id' => 'familyMemberCount',
                                'value' => $row['family_member_count'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                            echo form_input(array(
                                'name' => 'email',
                                'id' => 'email',
                                'value' => $row['email'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'email' => TRUE
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo form_label('Facebook хаяг', 'Facebook хаяг', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                            echo form_input(array(
                                'name' => 'facebook',
                                'id' => 'facebook',
                                'value' => $row['facebook'],
                                'maxlength' => '500',
                                'class' => 'form-control'
                            ));
                            ?>
                        </div>
                        
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <h3 style="margin-bottom: 5px;">Одоо амьдарч буй хаяг</h3>
                        <hr style="margin-top: 0;">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php // echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE)); ?>
                            <div style="padding-bottom: 5px;" id="address-city-html"><?php echo $controlCityDropdown; ?></div>
                            <div style="padding-bottom: 5px;" id="address-soum-html"><?php echo $controlSoumDropdown; ?></div>
                            <div style="padding-bottom: 5px;" id="address-street-html"><?php echo $controlStreetDropdown; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group address-detial-html">
                            <?php
                            //echo form_label('Дэлгэрэнгүй хаяг', 'Дэлгэрэнгүй хаяг', array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE));
                            echo form_textarea(array(
                                'name' => 'address',
                                'id' => 'address',
                                'value' => $row['address'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'style' => 'height: 100px;'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h3 style="margin-bottom: 5px;">Төлөв</h3>
                        <hr style="margin-top: 0;">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '1', ($row['is_active'] == 1 ? TRUE : '')); ?>
                                    Идэвхтэй </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '0', ($row['is_active'] == 0 ? TRUE : '')); ?>
                                    Идэвхгүй </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="student-class" style="min-height: 160px;"></div>
            <div class="tab-pane" id="student-finance" style="min-height: 160px;"></div>
            <div class="tab-pane" id="student-other">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php
                        echo form_label('Яаралтай үед холбогдох утас', 'Яаралтай үед холбогдох утас', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
                        echo form_input(array(
                            'name' => 'phone2',
                            'id' => 'phone2',
                            'value' => $row['phone_2'],
                            'maxlength' => '500',
                            'class' => 'form-control'
                        ));
                        ?>
                    </div>
                    <div class="form-group address-detial-html">
                        <?php
                        echo form_label('Дэлгэрэнгүй хаяг', 'Дэлгэрэнгүй хаяг', array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE));

                        echo form_textarea(array(
                            'name' => 'introText',
                            'id' => 'introText',
                            'value' => $row['intro_text'],
                            'maxlength' => '500',
                            'class' => 'form-control',
                            'style' => 'height: 80px;'
                        ));
                        ?>
                        <span class="help-block">Хаягийн талаар дэлгэрэнгүй мэдээлэл</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>













<?php
if (!$this->input->is_ajax_request()) {
    echo '<div class="form-group">';
    echo form_label(' ', ' ', array('class' => 'control-label col-lg-2 text-right', 'defined' => FALSE));
    echo '<div class="col-lg-10 text-left">';
    echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({elem:this, mode: \'' . $mode . '\', modId: ' . $row['mod_id'] . '});"', 'button');
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>


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
    $(function () {
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.select2').select2();
        $('.integer').formatter({pattern: '{{999}}'});
        $('.fancybox').fancybox({
            helpers: {
                title: null,
                overlay: {
                    speedOut: 0
                }
            }
        });

        $('#cityId').on('change', function () {
            _selectSoum({elem: this});
        });
        $('#soumId').on('change', function () {
            _selectStreet({elem: this});
        });

        $('#departmentCatId').on('change', function () {
            _selectDepartment({elem: this});
        });

    });
</script>