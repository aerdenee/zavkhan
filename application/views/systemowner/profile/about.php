<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">

                    <div class="col-md-12">
                        <fieldset class="stepy-step">
                            <legend><span class="text-semibold">Хувийн мэдээлэл</span> <span class="_description"></span><div class="clearfix"></div></legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'lname',
                                                'id' => 'lname',
                                                'value' => $row->lname,
                                                'maxlength' => '100',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>    
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'fname',
                                                'id' => 'fname',
                                                'value' => $row->fname,
                                                'maxlength' => '100',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Хүйс', 'Хүйс', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <?php echo form_radio(array('class' => 'radio', 'name' => 'sex', 'disabled' => TRUE), 1, ($row->sex == 1 ? TRUE : '')); ?>
                                                    Эрэгтэй
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <?php echo form_radio(array('class' => 'radio', 'name' => 'sex', 'disabled' => TRUE), 0, ($row->sex == 0 ? TRUE : '')); ?>
                                                    Эмэгтэй
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Төрсөн он, сар, өдөр', 'Төрсөн он, сар, өдөр', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-7">

                                            <?php
                                            echo form_input(array(
                                                'name' => 'birthday',
                                                'id' => 'birthday',
                                                'value' => date('Y-m-d', strtotime($row->birthday)),
                                                'maxlength' => '10',
                                                'class' => 'form-control init-date',
                                                'readonly' => true
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Төрсөн хот, аймаг', 'Төрсөн хот, аймаг', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo $controlBirthCityDropDown;
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Төрсөн сум, дүүрэг', 'Төрсөн сум, дүүрэг', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8 _init-birth-soum-html">
                                            <?php
                                            echo $controlBirthSoumDropDown;
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Төрсөн баг, хороо', 'Төрсөн баг, хороо', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8 _init-birth-street-html">
                                            <?php
                                            echo $controlBirthStreetDropDown;
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Төрсөн газрын хаяг', 'Төрсөн газрын хаяг', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'birthAddress',
                                                'id' => 'birthAddress',
                                                'value' => $row->birth_address,
                                                'maxlength' => '255',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Удмын овог', 'Удмын овог', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'familyName',
                                                'id' => 'familyName',
                                                'value' => $row->family_name,
                                                'maxlength' => '50',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Үндэс угсаа', 'Үндэс угсаа', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'nationality',
                                                'id' => 'nationality',
                                                'value' => $row->nationality,
                                                'maxlength' => '50',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Нийгмийн гарал', 'Нийгмийн гарал', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'socialOrigin',
                                                'id' => 'socialOrigin',
                                                'value' => $row->social_origin,
                                                'maxlength' => '50',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Регистрийн дугаар', 'Регистрийн дугаар', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'register',
                                                'id' => 'register',
                                                'value' => $row->register,
                                                'maxlength' => '12',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Нийгмийн даатгалын дэвтэр', 'Нийгмийн даатгалын дэвтэр', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'socialInsurance',
                                                'id' => 'socialInsurance',
                                                'value' => $row->social_insurance,
                                                'maxlength' => '12',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Эрүүл мэндийн дэвтэр', 'Эрүүл мэндийн дэвтэр', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo form_input(array(
                                                'name' => 'healthInsurance',
                                                'id' => 'healthInsurance',
                                                'value' => $row->health_insurance,
                                                'maxlength' => '12',
                                                'class' => 'form-control',
                                                'readonly' => true
                                            ));
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo form_label('Ажилтны төлөв', 'Ажилтны төлөв', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo $controlStatusListDropdown;
                                            ?>

                                        </div>
                                    </div>
                                </div>


                            </div>
                        </fieldset>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-12"><?php echo $listsHrPeopleFamilyMember; ?></div>
                    <div class="col-md-12"><?php echo $listsHrPeopleRelationMember; ?></div>
                    <div class="clearfix"></div>

                    <div class="col-md-12">
                        <fieldset class="stepy-step">
                            <legend>Одоо оршин суугаа хаяг</legend>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Хот, аймаг', 'Хот, аймаг', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo $controlLiveCityDropDown;
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Сум, дүүрэг', 'Сум, дүүрэг', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8 _init-live-soum-html">
                                <?php
                                echo $controlLiveSoumDropDown;
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Баг, хороо', 'Баг, хороо', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8 _init-live-street-html">
                                <?php
                                echo $controlLiveStreetDropDown;
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Хаяг', 'Хаяг', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'liveAddress',
                                    'id' => 'liveAddress',
                                    'value' => $row->live_address,
                                    'maxlength' => '255',
                                    'class' => 'form-control',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Утас', 'Утас', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'phone',
                                    'id' => 'phone',
                                    'value' => $row->phone,
                                    'maxlength' => '255',
                                    'class' => 'form-control',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Факс', 'Факс', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'fax',
                                    'id' => 'fax',
                                    'value' => $row->fax,
                                    'maxlength' => '255',
                                    'class' => 'form-control',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'email',
                                    'id' => 'email',
                                    'value' => $row->email,
                                    'maxlength' => '255',
                                    'class' => 'form-control',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Шуудангийн хаяг', 'Шуудангийн хаяг', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'postAddress',
                                    'id' => 'postAddress',
                                    'value' => $row->post_address,
                                    'maxlength' => '255',
                                    'class' => 'form-control',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Онцгой шаардлага гарвал харилцах хүний нэр', 'Онцгой шаардлага гарвал харилцах хүний нэр', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'specialPeople',
                                    'id' => 'specialPeople',
                                    'value' => $row->special_people,
                                    'maxlength' => '255',
                                    'class' => 'form-control',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Онцгой шаардлага гарвал харилцах хүний нэр', 'Онцгой шаардлага гарвал харилцах хүний нэр', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'specialPeople',
                                    'id' => 'specialPeople',
                                    'value' => $row->special_people,
                                    'maxlength' => '255',
                                    'class' => 'form-control',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo form_label('Утас', 'Утас', array('readonly' => true, 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'specialPhone',
                                    'id' => 'specialPhone',
                                    'value' => $row->special_phone,
                                    'maxlength' => '255',
                                    'class' => 'form-control',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>          



                    <div class="clearfix"></div>

                    <div class="col-md-12"><?php echo $listsHrPeopleWorkHistory; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>