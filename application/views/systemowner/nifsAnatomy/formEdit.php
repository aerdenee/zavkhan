<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-anatomy', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('catId', 0);
?>
<div class="row">
    <div class="col-7">
        <div class="row">
            <div class="col-7">
                <div class="form-group row mb-1">
                    <?php echo form_label('Дугаар', 'Дугаар', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-8">
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

                    </div>
                </div>
            </div>

            <div class="col-5">
                <div class="form-group row mb-1">
                    <?php echo form_label('Бүрэлдэхүүн', 'Бүрэлдэхүүн', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-8">
                        <?php echo $controlNifsIsMixxCheckBox; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-5">
        <div class="form-group row mb-1">
            <?php echo form_label('Үндэслэл', 'Үндэслэл', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8"><?php echo $controlNifsMotiveDropdown; ?></div>
        </div>
    </div>
</div>
<hr>

<div class="row">

    <div class="col-6">
        <div class="form-group row">
            <?php echo form_label('Бүртгэсэн, дуусах огноо', 'Бүртгэсэн, дуусах огноо', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <div class="row">
                    <div class="col-6">

                        <div class="input-group">
                            <?php
                            echo form_input(array(
                                'name' => 'inDate',
                                'id' => 'inDate',
                                'value' => date('Y-m-d', strtotime($row->in_date)),
                                'maxlength' => '10',
                                'class' => 'form-control init-date',
                                'required' => 'required',
                                'readonly' => true
                            ));
                            ?>
                        </div>

                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <?php
                            echo form_input(array(
                                'name' => 'outDate',
                                'id' => 'outDate',
                                'value' => date('Y-m-d', strtotime($row->out_date)),
                                'maxlength' => '10',
                                'class' => 'form-control init-date',
                                'required' => 'required',
                                'readonly' => true
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <span class="help-block"><i class="icon-help"></i> <span id="nifs-anatomy-in-out-date-diff-work-day">Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ.</span></span>
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
                    'class' => 'form-control'
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
                    'class' => 'form-control'
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
                    'class' => 'form-control pull-left'
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
                    'style' => 'width: 50px; display:inline; margin-right:10px;'
                );
                if (intval($row->is_age_infinitive) == 1) {
                    $dataAge['readonly'] = true;
                }

                echo form_input($dataAge);
                ?>
                <label style="margin-top: 2px;">
                    <?php echo form_checkbox(array('name' => 'isAgeInfinitive', 'class' => 'radio', 'onclick' => '_checkAnatomyAge({elem: this});'), 1, (intval($row->is_age_infinitive) == 1 ? TRUE : '')); ?>
                    Нас тодорхойгүй </label>
                <span class="help-block"><i class="icon-help"></i> 5 сартай гэж бичихийн тулд нас талбарт 0.5 гэж бичнэ <span id="in-out-date-diff"></span></span>
            </div>

        </div>

        <div class="form-group row">
            <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('class' => 'radio', 'name' => 'sex'), 1, ($row->sex == 1 ? true : false)); ?>
                        Эр
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('class' => 'radio', 'name' => 'sex'), 0, ($row->sex == 0 ? true : false)); ?>
                        Эм
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'address',
                    'id' => 'address',
                    'value' => $row->address,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Ажил', 'Ажил', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlWorkDropdown; ?>
            </div>
        </div>

    </div>
    <div class="col-6">
        <div class="form-group row">
            <?php echo form_label('Хэргийн төрөл', 'Хэргийн төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsCrimeTypeDropdown; ?>
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
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Болсон хэргийн товч', 'Болсон хэргийн товч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'shortValue',
                    'id' => 'shortValue',
                    'value' => $row->short_value,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
        <?php echo $controlHrPeopleExpertMultiListDropdown; ?>
        <div id="initAnatomyControlExpertHtmlExtra" class="<?php echo ($row->extra_expert_value != '' ? 'show' : 'hide'); ?>">
            <div class="form-group row">
                <?php echo form_label('', '', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => FALSE)); ?>
                <div class="col-md-8">
                    <?php echo form_textarea(array('name' => 'extraExpertValue', 'value' => $row->extra_expert_value, 'rows' => 3, 'class' => 'form-control')); ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Хаана', 'Хаана', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsWhereDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'description',
                    'id' => 'description',
                    'value' => $row->description,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">

            <?php echo form_label('Төлбөр', 'Төлбөр', array('required' => 'required', 'class' => 'control-label col-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php echo form_hidden('payment', $row->payment); ?>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('name' => 'isPayment', 'class' => 'radio', 'tabindex' => 20, 'onclick' => '_isPaymentAnatomy({elem: this});'), 1, ($row->payment == 1 ? TRUE : FALSE)); ?>
                        Төлсөн </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('name' => 'isPayment', 'class' => 'radio', 'tabindex' => 20, 'onclick' => '_isPaymentAnatomy({elem: this});'), 0, ($row->payment == 0 ? TRUE : FALSE)); ?>
                        Төлөөгүй </label>
                </div>

                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('name' => 'isPayment', 'class' => 'radio', 'tabindex' => 20, 'onclick' => '_isPaymentAnatomy({elem: this});'), 2, ($row->payment == 2 ? TRUE : FALSE)); ?>
                        Чөлөөлье </label>
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

    </div>

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
                    'maxlength' => 20
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
                            'value' => $row->protocol_in_date
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
                                    'value' => $row->protocol_out_date
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