<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-doctor-view', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('catId', $row->cat_id);
?>
<div class="row">

    <div class="col-6">

        <div class="form-group row">
            <?php echo form_label('Актын дугаар', 'Актын дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'createNumber',
                    'id' => 'createNumber',
                    'value' => $row->create_number,
                    'class' => 'form-control _control-create-number',
                    'required' => 'required',
                    'tabindex' => 1
                ));
                ?>
                <span id="checkNumberCreateNumber" class="checkNumberCreateNumber"></span>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Үндэслэл', 'Үндэслэл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsMotiveDropdown; ?>
            </div>
        </div>


        <div class="form-group row">
            <?php echo form_label('Ирсэн', 'Ирсэн', array('required' => 'required', 'class' => 'col-md-4 col-form-label text-md-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div style="width: 120px; float: left;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'inDate',
                            'id' => 'inDate',
                            'value' => date('Y-m-d', strtotime($row->in_date)),
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true,
                            'tabindex' => 3
                        ));
                        ?>
                    </div>
                </div>
                <div style="width: 120px; float:left; margin-left: 20px;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'inTime',
                            'id' => 'inTime',
                            'value' => date('H:i', strtotime($row->in_date)),
                            'maxlength' => '10',
                            'class' => 'form-control init-pickatime',
                            'required' => 'required',
                            'tabindex' => 4,
                            'maxlength' => 5
                        ));
                        ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Дууссан', 'Дууссан', array('required' => 'required', 'class' => 'col-md-4 col-form-label text-md-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div style="width: 120px; float: left;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'outDate',
                            'id' => 'outDate',
                            'value' => date('Y-m-d', strtotime($row->out_date)),
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true,
                            'tabindex' => 5
                        ));
                        ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Эцэг/эх/-ийн нэр', 'Эцэг/эх/-ийн нэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'lname',
                    'id' => 'lname',
                    'value' => $row->lname,
                    'maxlength' => '250',
                    'class' => 'form-control',
                    'tabindex' => 6
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'fname',
                    'id' => 'fname',
                    'value' => $row->fname,
                    'maxlength' => '250',
                    'class' => 'form-control',
                    'tabindex' => 7
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Регистр №', 'Регистр №', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'register',
                    'id' => 'register',
                    'value' => $row->register,
                    'maxlength' => 10,
                    'class' => 'form-control pull-left',
                    'tabindex' => 8
                ));
                ?>
                <label style="margin-top: 8px; margin-left: 10px;" class="pull-left help-block">
                    <i class="icon-help"></i> Зөвхөн крилл үсгээр бичнэ үү</label>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Нас', 'Нас', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                $dataAge = array(
                    'name' => 'age',
                    'id' => 'age',
                    'value' => $row->age,
                    'maxlength' => 5,
                    'class' => 'form-control control-number text-right init-control-age',
                    'style' => 'width: 50px; display:inline; margin-right:10px;',
                    'tabindex' => 9
                );
                if (intval($row->is_age_infinitive) == 1) {
                    $dataAge['readonly'] = true;
                }

                echo form_input($dataAge);
                ?>
                <label style="margin-top: 2px;">
                    <?php echo form_checkbox(array('name' => 'isAgeInfinitive', 'class' => 'radio', 'onclick' => '_checkDoctorViewAge({elem: this});'), 1, (intval($row->is_age_infinitive) == 1 ? TRUE : '')); ?>
                    Нас тодорхойгүй </label>
                <span class="help-block"><i class="icon-help"></i> 5 сартай гэж бичихийн тулд нас талбарт 0.5 гэж бичнэ <span id="in-out-date-diff"></span></span>
            </div>

        </div>

        <div class="form-group row">
            <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'control-label col-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('name' => 'sex', 'class' => 'radio', 'tabindex' => 10), 1, ($row->sex == 1 ? TRUE : FALSE)); ?>
                        Эрэгтэй </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('name' => 'sex', 'class' => 'radio', 'tabindex' => 10), 0, ($row->sex == 0 ? TRUE : FALSE)); ?>
                        Эмэгтэй </label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'phone',
                    'id' => 'phone',
                    'value' => $row->phone,
                    'maxlength' => '250',
                    'class' => 'form-control',
                    'tabindex' => 11
                ));
                ?>
            </div>
        </div>



    </div><!-- end col -->

    <div class="col-6">
        <div class="form-group row">
            <?php echo form_label('Ажил', 'Ажил', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlWorkDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Ирүүлсэн байгууллага', 'Ирүүлсэн байгууллага', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlPartnerDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Албан хаагч', 'Албан хаагч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'expertName',
                    'id' => 'expertName',
                    'value' => $row->expert_name,
                    'maxlength' => '250',
                    'class' => 'form-control',
                    'tabindex' => 13
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Хэрэг болсон огноо', 'Хэрэг болсон огноо', array('required' => 'required', 'class' => 'col-md-4 col-form-label text-md-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div style="width: 120px; float: left;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'crimeDate',
                            'id' => 'crimeDate',
                            'value' => $row->crime_date,
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'readonly' => true,
                            'placeholder' => '____-__-__',
                            'tabindex' => 14
                        ));
                        ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Болсон хэргийн товч', 'Болсон хэргийн товч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlCrimeShortValueDropdown; ?>
            </div>
        </div>
        <span id="initDoctorViewControlShortValueHtml">
            <?php
            if ($row->short_value_id == 7) {
                echo '<div class="form-group row"><label class="col-md-4 control-label text-right"></label><div class="col-md-8"><textarea name="shortValue" row="2" class="form-control">' . $row->short_value . '</textarea></div></div>';
            } else {
                echo form_hidden('shortValue', '');
            }
            ?>
        </span>
        <span id="initDoctorViewControlIsCrimeShipHtml">
            <?php
            if ($row->short_value_id == 9 or $row->short_value_id == 3) {
                echo '<div class="form-group row">';
                echo form_label('Оролцогч', 'Оролцогч', array('required' => 'required', 'class' => 'control-label col-4 text-right', 'defined' => TRUE));
                echo '<div class="col-8">';
                echo '<div class="form-check form-check-inline">';
                echo '<label class="form-check-label">';
                echo form_radio(array('name' => 'isCrimeShip', 'class' => 'radio'), 1, ($row->is_crime_ship == 1 ? TRUE : FALSE));
                echo ' Хохирогч ';
                echo '</label>';
                echo '</div>';
                echo '<div class="form-check form-check-inline">';
                echo '<label class="form-check-label">';
                echo form_radio(array('name' => 'isCrimeShip', 'class' => 'radio'), 0, ($row->is_crime_ship == 0 ? TRUE : FALSE));
                echo ' Холбогдогч ';
                echo '</label>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            } else {
                echo form_hidden('isCrimeShip', 0);
            }
            ?>
        </span>

        <div class="form-group row">
            <?php echo form_label('Шинжээч эмч', 'Шинжээч эмч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlExpertDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Хаана', 'Хаана', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsWhereDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Хэргийн төрөл', 'Хэргийн төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlCategoryListDropdown; ?>
            </div>
        </div>

        <div class="form-group row">

            <?php echo form_label('Төлбөр', 'Төлбөр', array('required' => 'required', 'class' => 'control-label col-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php echo form_hidden('payment', $row->payment); ?>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('name' => 'isPayment', 'class' => 'radio', 'tabindex' => 20, 'onclick' => '_isPaymentDoctorView({elem: this});'), 1, ($row->payment == 1 ? TRUE : FALSE)); ?>
                        Төлсөн </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('name' => 'isPayment', 'class' => 'radio', 'tabindex' => 20, 'onclick' => '_isPaymentDoctorView({elem: this});'), 0, ($row->payment == 0 ? TRUE : FALSE)); ?>
                        Төлөөгүй </label>
                </div>

                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('name' => 'isPayment', 'class' => 'radio', 'tabindex' => 20, 'onclick' => '_isPaymentDoctorView({elem: this});'), 2, ($row->payment == 2 ? TRUE : FALSE)); ?>
                        Чөлөөлсөн </label>
                </div>
            </div>
        </div>

        <div class="<?php echo ($row->payment == 2 ? 'show' : 'hide'); ?>" id="paymentDescriptionHtml">
            <div class="form-group row">
                <?php echo form_label('Чөлөөлсөн үндэслэл', 'Чөлөөлсөн үндэслэл', array('required' => 'required', 'class' => 'control-label col-4 text-right', 'defined' => TRUE)); ?>
                <div class="col-8">
                    <?php
                    echo form_textarea(array(
                        'name' => 'paymentDescription',
                        'id' => 'paymentDescription',
                        'value' => $row->payment_description,
                        'rows' => 3,
                        'class' => 'form-control'
                    ));
                    ?>

                </div>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_textarea(array(
                    'name' => 'description',
                    'id' => 'description',
                    'value' => $row->description,
                    'rows' => 3,
                    'class' => 'form-control',
                    'tabindex' => 21
                ));
                ?>
            </div>
        </div>
    </div><!-- end col -->

</div>
<hr>
<div class="row">
    <div class="col-4">
        <div class="form-group row mb-0">
            <?php echo form_label('Хэргийн дугаар', 'Хэргийн дугаар', array('required' => 'required', 'class' => 'col-6 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-6">
                <?php
                echo form_input(array(
                    'name' => 'protocolNumber',
                    'id' => 'protocolNumber',
                    'value' => $row->protocol_number,
                    'class' => 'form-control control-number text-right',
                    'maxlength' => 20,
                    'tabindex' => 21
                ));
                ?>
                <span class="help-block"><i class="icon-help"></i> Захирамж, хэргийн №</span>

            </div>
            <div class="clearfix"></div>

        </div>

    </div>

    <div class="col-7">
        <div class="form-group row mb-0">
            <?php echo form_label('Тогтоолын огноо', 'Тогтоолын огноо', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <div class="row">
                    <div class="col-5">
                        <?php
                        echo form_input(array(
                            'name' => 'protocolInDate',
                            'id' => 'protocolInDate',
                            'placeholder' => '____-__-__',
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'readonly' => true,
                            'value' => $row->protocol_in_date,
                            'tabindex' => 22
                        ));
                        ?>
                    </div>
                    <div class="col-7">
                        <div class="form-group row" style="margin-bottom: 0;">
                            <?php echo form_label('Дуусах', 'Дуусах', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
                            <div class="col-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'protocolOutDate',
                                    'id' => 'protocolOutDate',
                                    'class' => 'form-control init-date',
                                    'maxlength' => '10',
                                    'placeholder' => '____-__-__',
                                    'readonly' => true,
                                    'value' => $row->protocol_out_date,
                                    'tabindex' => 22
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <span class="help-block">
                    <i class="icon-help"></i> <span id="nifs-anatomy-protocol-in-out-date-diff-work-day">Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ.</span>
                </span>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
