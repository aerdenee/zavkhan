<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabContentMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabContentEnglish" data-toggle="tab">English</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabContentMongolia">
            <div class="form-group" id="picField">
                <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="media no-margin-top">
                        <div class="media-left">
                            <?php
                            $this->picSmall = UPLOADS_USER_PATH . CROP_SMALL . $row['pic'];
                            $this->picBig = UPLOADS_USER_PATH . CROP_MEDIUM . $row['pic'];
                            if (is_file($_SERVER['DOCUMENT_ROOT'] . $this->picSmall)) {
                                echo '<div style="position: relative; display: inline-block;">';
                                echo '<a href="' . $this->picBig . '" class="fancybox" data-fancybox-group="gallery">';
                                echo '<img src="' . $this->picSmall . '" style="width: 58px; height: 58px;" class="img-rounded">';
                                echo '</a>';
                                //echo '<span class="badge bg-danger" style="position: absolute; bottom: -8px; right: -8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_removeImage(\'' . $row['pic'] . '\');"><i class="fa fa-close"></i></span>';
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
                                'onchange' => '_uploadImage();',
                                'disabled' => true
                            ));
                            echo '<span class="filename" style="user-select: none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
                            echo '</div>';
                            ?>
                            <span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'lnameMn',
                        'id' => 'lnameMn',
                        'value' => $row['lname_mn'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required',
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'fnameMn',
                        'id' => 'fnameMn',
                        'value' => $row['fname_mn'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required',
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'sex', 'name' => 'sex', 'class' => 'radio', 'disabled' => true), '1', ($row['sex'] == 1 ? TRUE : '')); ?>
                            Эрэгтэй </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'sex', 'name' => 'sex', 'class' => 'radio', 'disabled' => true), '0', ($row['sex'] == 0 ? TRUE : '')); ?>
                            Эмэгтэй </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Төрсөн он, сар, өдөр', 'Төрсөн он, сар, өдөр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-2 col-md-2">
                    <div class="input-group date date-time" id="event_start_date">
                        <?php
                        echo form_input(array(
                            'name' => 'birthday',
                            'id' => 'birthday',
                            'value' => $row['birthday'],
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'disabled' => true
                        ));
                        ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'phone',
                        'id' => 'phone',
                        'value' => $row['phone'],
                        'maxlength' => '500',
                        'class' => 'form-control', 
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'email',
                        'id' => 'email',
                        'value' => $row['email'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'email' => TRUE, 
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="pull-left" style="padding-right: 20px; min-width: 200px;" id="address-city-html">
                        <?php echo $controlCityDropdown; ?>
                        <span class="help-block">Нийслэл, аймаг</span>
                    </div>
                    <div class="pull-left" style="padding-right: 20px; min-width: 200px;" id="address-soum-html">
                        <?php echo $controlSoumDropdown; ?>
                        <span class="help-block">Дүүрэг, сум</span>
                    </div>
                    <div class="pull-left" style="padding-right: 20px; min-width: 200px;" id="address-street-html">
                        <?php echo $controlStreetDropdown; ?>
                        <span class="help-block">Хороо, баг</span>
                    </div>
                </div>
            </div>
            <div class="form-group <?php echo ($row['address_mn'] != '' ? '' : 'hide'); ?> address-detial-html">
                <?php echo form_label(' ', ' ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_textarea(array(
                        'name' => 'addressMn',
                        'id' => 'addressMn',
                        'value' => $row['address_mn'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'row' => 3,
                        'style' => 'max-height: 150px;', 
                        'disabled' => true
                    ));
                    ?>
                    <span class="help-block">Хаягийн талаар дэлгэрэнгүй мэдээлэл</span>
                </div>
            </div>
            <?php
            $this->socialArray = json_decode($row['social']);

            foreach ($this->socialArray as $paramKey => $paramRow) {
                echo '<div class="form-group">';
                echo form_label($paramRow->label, $paramRow->label, array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE));
                echo '<div class="col-lg-5">';

                echo form_hidden('socialLabel[' . $paramKey . ']', $paramRow->label);
                echo form_input(array(
                    'name' => 'social[' . $paramKey . ']',
                    'id' => 'social[' . $paramKey . ']',
                    'value' => $paramRow->address,
                    'maxlength' => '500',
                    'class' => 'form-control', 
                        'disabled' => true
                ));
                echo '</div>';
                echo '</div>';
            }
            ?>
            <div class="form-group">
                <?php echo form_label('Боловсрол', 'Боловсрол', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlEducationDropDown; ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label('Зэрэг дэв', 'Зэрэг дэв', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlDegreeDropDown; ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label('Албан тушаал', 'Албан тушаал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlCareerDropDown; ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label('Салбар хэлтэс', 'Салбар хэлтэс', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="pull-left" style="padding-right: 20px; width: 50%;">
                        <?php echo $controlDepartmentCategoryDropdown; ?>
                        <span class="help-block">Салбар, хэлтэс ангилал</span>
                    </div>
                    <div class="pull-left" style="width: 50%;" id="department-html">
                        <?php echo $controlDepartmentDropDown; ?>
                        <span class="help-block">Салбар, хэлтэс</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-2">
                    <?php
                    echo form_input(array(
                        'name' => 'orderNum',
                        'id' => 'orderNum',
                        'value' => $row['order_num'],
                        'maxlength' => '10',
                        'class' => 'form-control integer',
                        'required' => 'required', 
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label('Үзлэг, шинжилгээ хийх эрх', 'Үзлэг, шинжилгээ хийх эрх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list is-krim">
                        <?php echo form_hidden('extraIsCrimeScene', $row['extra_is_crime_scene']); ?>
                        <label class="radio-inline" style="padding-left: 0;">
                            <?php
                            $this->isCrime = FALSE;
                            $this->isResearch = FALSE;
                            if ($row['extra_is_crime_scene'] == 1) {
                                $this->isCrime = TRUE;
                            } else if ($row['extra_is_crime_scene'] == 2) {
                                $this->isCrime = TRUE;
                                $this->isResearch = TRUE;
                            }
                            ?>
                            <?php echo form_checkbox(array('name' => 'isCrime', 'class' => 'checkbox', 'disabled' => true, 'onclick' => '_setKrimValue(this)'), 1, $this->isCrime); ?>
                            Хэргийн газрын үзлэг </label>
                        <label class="radio-inline">
                            <?php echo form_checkbox(array('name' => 'isResearch', 'class' => 'checkbox', 'disabled' => true, 'onclick' => '_setKrimValue(this)'), 2, $this->isResearch); ?>
                            Шинжилгээ хийх </label>
                    </div>
                    <script type="text/javascript">
                        function _setKrimValue(elem) {
                            var _this = $(elem);
                            var _isCrime = $('input[name="isCrime"]');
                            var _isResearch = $('input[name="isResearch"]');

                            if (_this.prop('checked')) {
                                if ($(elem).val() == '2') {
                                    _isCrime.attr('checked', true);
                                    _isCrime.parents('span').addClass('checked');

                                    _isResearch.attr('checked', true);
                                    _isResearch.parents('span').addClass('checked');
                                }
                                $('input[name="extraIsCrimeScene"]').val($(elem).val());
                            } else {

                                if ($(elem).val() == '1') {
                                    _isCrime.attr('checked', false);
                                    _isCrime.parents('span').removeClass('checked');

                                    _isResearch.attr('checked', false);
                                    _isResearch.parents('span').removeClass('checked');
                                    $('input[name="extraIsCrimeScene"]').val(0);
                                } else {
                                    $('input[name="extraIsCrimeScene"]').val(1);
                                }
                            }


                        }
                    </script>
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
                        'class' => 'ckeditor', 
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="tabContentEnglish">
            <div class="form-group">
                <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'lnameEn',
                        'id' => 'lnameEn',
                        'value' => $row['lname_en'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required', 
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'fnameEn',
                        'id' => 'fnameEn',
                        'value' => $row['fname_en'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required', 
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group <?php echo ($row['address_en'] != '' ? '' : 'hide'); ?> address-detial-html">
                <?php echo form_label(' ', ' ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_textarea(array(
                        'name' => 'addressEn',
                        'id' => 'addressEn',
                        'value' => $row['address_en'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'row' => 3,
                        'style' => 'max-height: 150px;', 
                        'disabled' => true
                    ));
                    ?>
                    <span class="help-block">Хаягийн талаар дэлгэрэнгүй мэдээлэл</span>
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
                        'class' => 'ckeditor', 
                        'disabled' => true
                    ));
                    ?>
                </div>
            </div>
            
        </div>
    </div>
</div>
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
        CKEDITOR.replace('introTextMn', {height: '400px'});
        CKEDITOR.replace('introTextEn', {height: '400px'});

        $('#cityId').on('change', function () {
            _selectSoum({elem:this});
        });
        $('#soumId').on('change', function () {
            _selectStreet({elem:this});
        });

        $('#departmentCatId').on('change', function () {
            _selectDepartment({elem:this});
        });
        $('.init-date').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd',
            selectYears: 80,
            max: true,
            today: '',
            close: '',
            clear: ''
        });

    });
</script>