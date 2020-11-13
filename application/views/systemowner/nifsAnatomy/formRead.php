<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-nifs-anatomy', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('catId', 0);
?>
<fieldset>

    <div class="col-md-6">

        <div class="form-group">
            <?php echo form_label('Актын дугаар', 'Актын дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'createNumber',
                    'id' => 'createNumber',
                    'value' => $row->create_number,
                    'class' => 'form-control control-journal-number',
                    'readonly' => true
                ));
                ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Ирсэн', 'Ирсэн', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-5">
                        <?php
                        echo form_input(array(
                            'name' => 'inDate',
                            'id' => 'inDate',
                            'value' => date('Y-m-d', strtotime($row->in_date)),
                            'maxlength' => '10',
                            'class' => 'form-control read-date',
                            'required' => 'required',
                            'readonly' => true
                        ));
                        ?>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <div class="row">
                                <?php echo form_label('Дуусах', 'Дуусах', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
                                <div class="col-md-8">
                                    <?php
                                    echo form_input(array(
                                        'name' => 'outDate',
                                        'id' => 'outDate',
                                        'value' => date('Y-m-d', strtotime($row->out_date)),
                                        'maxlength' => '10',
                                        'class' => 'form-control read-date',
                                        'required' => 'required',
                                        'readonly' => true
                                    ));
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <span class="help-block"><i class="icon-help"></i> Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ. <span id="in-out-date-diff"></span></span>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Овог', 'Овог', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'lname',
                    'id' => 'lname',
                    'value' => $row->lname,
                    'maxlength' => '250',
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Нэр', 'Нэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'fname',
                    'id' => 'fname',
                    'value' => $row->fname,
                    'maxlength' => '250',
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Регистр №', 'Регистр №', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'register',
                    'id' => 'register',
                    'value' => $row->register,
                    'maxlength' => 10,
                    'class' => 'form-control pull-left',
                    'readonly' => true
                ));
                ?>
                <label style="margin-top: 8px; margin-left: 10px;" class="pull-left help-block">
                    <i class="icon-help"></i> Зөвхөн крилл үсгээр бичнэ үү</label>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Нас', 'Нас', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                if ($row->is_age_infinitive == '1') {
                    echo form_input(array(
                        'name' => 'age',
                        'id' => 'age',
                        'value' => '',
                        'maxlength' => 3,
                        'class' => 'form-control control-number text-right',
                        'readonly' => true,
                        'style' => 'width: 50px; display:inline; margin-right:10px;',
                        'readonly' => true
                    ));

                    echo '<label style="margin-top: 2px;">' . form_checkbox(array('name' => 'isAgeInfinitive', 'class' => 'radio', 'disabled' => true), 1, TRUE) . 'Нас тодорхойгүй </label>';
                } else {
                    echo form_input(array(
                        'name' => 'age',
                        'id' => 'age',
                        'value' => $row->age,
                        'maxlength' => 3,
                        'class' => 'form-control control-number text-right',
                        'style' => 'width: 50px; display:inline; margin-right:10px;',
                        'readonly' => true
                    ));
                    echo '<label style="margin-top: 2px;">' . form_checkbox(array('name' => 'isAgeInfinitive', 'class' => 'radio', 'disabled' => true), 1, FALSE) . 'Нас тодорхойгүй </label>';
                }
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div class="radio-list">
                    <label class="radio-inline">
                        <?php echo form_radio(array('name' => 'sex', 'class' => 'radio', 'disabled' => true), '1', (intval($row->sex) == 1 ? TRUE : '')); ?>
                        Эрэгтэй </label>
                    <label class="radio-inline">
                        <?php echo form_radio(array('name' => 'sex', 'class' => 'radio', 'disabled' => true), '0', (intval($row->sex) == 0 ? TRUE : '')); ?>
                        Эмэгтэй </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_textarea(array(
                    'name' => 'address',
                    'id' => 'address',
                    'value' => $row->address,
                    'rows' => 2,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Ажил', 'Ажил', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlWorkDropdown; ?>
            </div>
        </div>

    </div><!-- end col -->

    <div class="col-lg-6">

        <div class="form-group">
            <?php echo form_label('Хэгийн төрөл', 'Хэгийн төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsCrimeTypeDropdown; ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Ирүүлсэн байгууллага', 'Ирүүлсэн байгууллага', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlPartnerDropdown; ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Албан хаагч', 'Албан хаагч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'expertName',
                    'id' => 'expertName',
                    'value' => $row->expert_name,
                    'maxlength' => '250',
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Болсон хэргийн товч', 'Болсон хэргийн товч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_textarea(array(
                    'name' => 'shortValue',
                    'id' => 'shortValue',
                    'value' => $row->short_value,
                    'rows' => 2,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Шинжээч эмч', 'Шинжээч эмч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlExpertDropdown; ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Хаана', 'Хаана', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsWhereDropdown; ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_textarea(array(
                    'name' => 'description',
                    'id' => 'description',
                    'value' => $row->description,
                    'rows' => 2,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Төлбөр', 'Төлбөр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div class="radio-list">
                    <label class="radio-inline">
                        <?php echo form_radio(array('name' => 'payment', 'class' => 'radio', 'disabled' => true), 1, ($row->payment == 1 ? TRUE : '')); ?>
                        Төлсөн </label>
                    <label class="radio-inline">
                        <?php echo form_radio(array('name' => 'payment', 'class' => 'radio', 'disabled' => true), 0, ($row->payment == 0 ? TRUE : '')); ?>
                        Төлөөгүй </label>
                </div>
            </div>
        </div>

    </div><!-- end col -->
    <div class="clearfix"></div>
</fieldset>
<?php echo form_close(); ?>