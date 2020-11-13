
<div class="card container">
    <div class="card-body">
        <?php
        echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-hr-people', 'enctype' => 'multipart/form-data'));
        echo form_hidden('id', $row->id);
        echo form_hidden('modId', $row->mod_id);
        echo form_hidden('catId', $row->cat_id);
        echo form_hidden('isActive', $row->is_active);
        echo form_hidden('hrPeoplePic', '');
        echo form_hidden('hrPeopleOldPic', ltrim($row->pic, UPLOADS_USER_PATH . CROP_SMALL));

        echo form_hidden('orderNum', $row->order_num);
        ?>
        <ul class="nav nav-tabs nav-tabs-highlight justify-content-center">
            <li class="nav-item"><a href="#centered-tab1" class="nav-link active show" data-toggle="tab">Хувийн мэдээлэл</a></li>
            <li class="nav-item"><a href="#centered-tab2" class="nav-link" data-toggle="tab">Боловсрол</a></li>
            <li class="nav-item"><a href="#centered-tab3" class="nav-link" data-toggle="tab">Мэргэшил</a></li>
            <li class="nav-item"><a href="#centered-tab4" class="nav-link" data-toggle="tab">Шагнал</a></li>
            <li class="nav-item"><a href="#centered-tab5" class="nav-link" data-toggle="tab">Тайлан</a></li>
            <li class="nav-item"><a href="#centered-tab6" class="nav-link" data-toggle="tab">Шийтгэл</a></li>
        </ul>

        <div class="tab-content pt-2">
            <div class="tab-pane fade active show" id="centered-tab1">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <?php
                                    echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
                                    echo '<a href="javascript:;">';
                                    echo '<img src="' . $row->pic . '" class="_hr-people-image">';
                                    echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'hr_people\', formId: _hrPeopleFormMainId, appendHtmlClass: \'._hr-people-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_USER_PATH, prefix: \'hrPeople\'});">';
                                    echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
                                    echo '</span>';
                                    echo '<span class="_user-image-upload-button">';
                                    echo '<div class="uploader">';

                                    echo form_upload(array(
                                        'name' => 'hrPeoplePicUpload',
                                        'id' => 'hrPeoplePicUpload',
                                        'class' => 'pull-left file-styled',
                                        'onchange' => '_imageProfileUpload({
                                        elem: this, 
                                        uploadPath: UPLOADS_USER_PATH, 
                                        formId: _hrPeopleFormMainId, 
                                        appendHtmlClass: \'._hr-people-image\', 
                                        prefix: \'hrPeople\'});',
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
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'lname',
                                                    'id' => 'lname',
                                                    'value' => $row->lname,
                                                    'maxlength' => '100',
                                                    'class' => 'form-control',
                                                    'required' => 'required'
                                                ));
                                                ?>    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'fname',
                                                    'id' => 'fname',
                                                    'value' => $row->fname,
                                                    'maxlength' => '100',
                                                    'class' => 'form-control',
                                                    'required' => 'required'
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'sex'), 1, ($row->sex == 1 ? TRUE : '')); ?>
                                                        Эрэгтэй
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'sex'), 0, ($row->sex == 0 ? TRUE : '')); ?>
                                                        Эмэгтэй
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Төрсөн он, сар, өдөр', 'Төрсөн он, сар, өдөр', array('required' => 'required', 'class' => 'control-label col-md-5 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-7">

                                                <?php
                                                echo form_input(array(
                                                    'name' => 'birthday',
                                                    'id' => 'birthday',
                                                    'value' => date('Y-m-d', strtotime($row->birthday)),
                                                    'maxlength' => '10',
                                                    'class' => 'form-control init-date',
                                                    'required' => 'required',
                                                    'readonly' => true
                                                ));
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Төрсөн хот, аймаг', 'Төрсөн хот, аймаг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo $controlBirthCityDropDown;
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Төрсөн сум, дүүрэг', 'Төрсөн сум, дүүрэг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8 _init-birth-soum-html">
                                                <?php
                                                echo $controlBirthSoumDropDown;
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Төрсөн баг, хороо', 'Төрсөн баг, хороо', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8 _init-birth-street-html">
                                                <?php
                                                echo $controlBirthStreetDropDown;
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Төрсөн газрын хаяг', 'Төрсөн газрын хаяг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'birthAddress',
                                                    'id' => 'birthAddress',
                                                    'value' => $row->birth_address,
                                                    'maxlength' => '255',
                                                    'class' => 'form-control'
                                                ));
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Ургийн овог', 'Ургийн овог', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'familyName',
                                                    'id' => 'familyName',
                                                    'value' => $row->family_name,
                                                    'maxlength' => '50',
                                                    'class' => 'form-control'
                                                ));
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Үндэс угсаа', 'Үндэс угсаа', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'nationality',
                                                    'id' => 'nationality',
                                                    'value' => $row->nationality,
                                                    'maxlength' => '50',
                                                    'class' => 'form-control'
                                                ));
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Нийгмийн гарал', 'Нийгмийн гарал', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'socialOrigin',
                                                    'id' => 'socialOrigin',
                                                    'value' => $row->social_origin,
                                                    'maxlength' => '50',
                                                    'class' => 'form-control'
                                                ));
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Регистрийн дугаар', 'Регистрийн дугаар', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'register',
                                                    'id' => 'register',
                                                    'value' => $row->register,
                                                    'maxlength' => '12',
                                                    'class' => 'form-control'
                                                ));
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Нийгмийн даатгалын дэвтэр', 'Нийгмийн даатгалын дэвтэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'socialInsurance',
                                                    'id' => 'socialInsurance',
                                                    'value' => $row->social_insurance,
                                                    'maxlength' => '12',
                                                    'class' => 'form-control'
                                                ));
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Эрүүл мэндийн дэвтэр', 'Эрүүл мэндийн дэвтэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo form_input(array(
                                                    'name' => 'healthInsurance',
                                                    'id' => 'healthInsurance',
                                                    'value' => $row->health_insurance,
                                                    'maxlength' => '12',
                                                    'class' => 'form-control'
                                                ));
                                                ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <?php echo form_label('Ажилтны төлөв', 'Ажилтны төлөв', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo $controlStatusListDropdown;
                                                ?>

                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-12" id="init-hr-family-member"></div>
                            <div class="col-md-12" id="init-hr-relation-member"></div>
                            <div class="clearfix"></div>

                            <div class="col-md-12">
                                <fieldset class="stepy-step">
                                    <legend>Одоо оршин суугаа хаяг</legend>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Хот, аймаг', 'Хот, аймаг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo $controlLiveCityDropDown;
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Сум, дүүрэг', 'Сум, дүүрэг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8 _init-live-soum-html">
                                        <?php
                                        echo $controlLiveSoumDropDown;
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Баг, хороо', 'Баг, хороо', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8 _init-live-street-html">
                                        <?php
                                        echo $controlLiveStreetDropDown;
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'liveAddress',
                                            'id' => 'liveAddress',
                                            'value' => $row->live_address,
                                            'maxlength' => '255',
                                            'class' => 'form-control'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'phone',
                                            'id' => 'phone',
                                            'value' => $row->phone,
                                            'maxlength' => '255',
                                            'class' => 'form-control'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Факс', 'Факс', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'fax',
                                            'id' => 'fax',
                                            'value' => $row->fax,
                                            'maxlength' => '255',
                                            'class' => 'form-control'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'email',
                                            'id' => 'email',
                                            'value' => $row->email,
                                            'maxlength' => '255',
                                            'class' => 'form-control'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Шуудангийн хаяг', 'Шуудангийн хаяг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'postAddress',
                                            'id' => 'postAddress',
                                            'value' => $row->post_address,
                                            'maxlength' => '255',
                                            'class' => 'form-control'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Онцгой шаардлага гарвал харилцах хүний нэр', 'Онцгой шаардлага гарвал харилцах хүний нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'specialPeople',
                                            'id' => 'specialPeople',
                                            'value' => $row->special_people,
                                            'maxlength' => '255',
                                            'class' => 'form-control'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Онцгой шаардлага гарвал харилцах хүний нэр', 'Онцгой шаардлага гарвал харилцах хүний нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'specialPeople',
                                            'id' => 'specialPeople',
                                            'value' => $row->special_people,
                                            'maxlength' => '255',
                                            'class' => 'form-control'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                    <div class="col-md-8">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'specialPhone',
                                            'id' => 'specialPhone',
                                            'value' => $row->special_phone,
                                            'maxlength' => '255',
                                            'class' => 'form-control'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>          



                            <div class="clearfix"></div>

                            <div class="col-md-12" id="init-hr-people-work"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="centered-tab2">
                <div id="init-hr-people-education"></div>
                <div id="init-hr-people-education-doctor"></div>
            </div>

            <div class="tab-pane fade" id="centered-tab3">
                <div id="init-hr-people-course"></div>
                <div id="init-hr-people-position-rank"></div>
                <div id="init-hr-people-education-rank"></div>
                <div id="init-hr-people-language"></div>
            </div>

            <div class="tab-pane fade" id="centered-tab4">
                <div id="init-hr-people-award"></div>
            </div>

            <div class="tab-pane fade" id="centered-tab5">
                <div id="init-hr-people-report"></div>

            </div>
            <div class="tab-pane fade" id="centered-tab6">
                <div id="init-hr-people-conviction"></div>
            </div>

        </div>
        <div class="container">
            <div class="row">
                <div class="text-right w-100">
                    <?php
                    echo form_button('initHrPeople', '<i class="icon-paperplane"></i> Хаах', 'class="btn btn-default" onclick="_initHrPeople({elem: this, searchQuery: {}});"', 'button');
                    echo form_button('saveHrPeople', '<i class="icon-paperplane"></i> Хадгалах', 'class="btn btn-primary" onclick="_updateHrPeople({elem: this});"', 'button');
                    ?>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
