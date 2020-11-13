<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-nifs-file-folder', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>

<fieldset>
    <div class="col-md-3">
        <div class="form-group" style="margin-bottom: 0;">
            <?php echo form_label('Дугаар', 'Дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'createNumber',
                    'id' => 'createNumber',
                    'value' => $row->create_number,
                    'class' => 'form-control control-number text-right',
                    'readonly' => true,
                    'tabindex' => 1
                ));
                ?>

            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group" style="margin-bottom: 0;">
            <?php echo form_label('Шинжилгээ', 'Шинжилгээ', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $controlNifsResearchTypeDropdown; ?>
                    </div>
                    <div class="col-md-6"><?php echo $controlNifsIsMixxCheckBox;?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group" style="margin-bottom: 0;">
            <?php echo form_label('Үндэслэл', 'Үндэслэл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8"><?php echo $controlNifsMotiveDropdown; ?></div>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend> </legend>
    <div class="col-lg-6">
        
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
                            'class' => 'form-control init-date',
                            'readonly' => true
                        ));
                        ?>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <?php echo form_label('Дуусах', 'Дуусах', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
                            <div class="col-md-8">
                                <?php
                                echo form_input(array(
                                    'name' => 'outDate',
                                    'id' => 'outDate',
                                    'value' => date('Y-m-d', strtotime($row->out_date)),
                                    'maxlength' => '10',
                                    'class' => 'form-control init-date',
                                    'readonly' => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <span class="help-block"><i class="icon-help"></i> Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ. <span id="in-out-date-diff"></span></span>
            </div>


        </div>

        <div class="form-group">
            <?php echo form_label('Эцэг/эх/-ийн нэр', 'Эцэг/эх/-ийн нэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'lname',
                    'id' => 'lname',
                    'value' => $row->lname,
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'fname',
                    'id' => 'fname',
                    'value' => $row->fname,
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Томилсон байгууллага', 'Томилсон байгууллага', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlPartnerDropdown; ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Албан тушаалтны нэр', 'Албан тушаалтны нэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'agentName',
                    'id' => 'agentName',
                    'value' => $row->agent_name,
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Шийдвэрлэх асуудал', 'Шийдвэрлэх асуудал', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsQuestionDropDown; ?>
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
                    'rows' => 3,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-lg-6">
        <div class="form-group">
            <?php echo form_label('Өмнөх дугаар', 'Өмнөх дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'preCreateNumber',
                    'id' => 'preCreateNumber',
                    'value' => $row->pre_create_number,
                    'class' => 'form-control control-number text-right',
                    'readonly' => true,
                    'maxlength' => 30
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Өмнөх шинжээч', 'Өмнөх шинжээч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlpreExpertDropDown; ?>
            </div>
        </div>

        <div class="form-group">
            <div class="form-group">
                <?php echo form_label('Өмнөх хэргийн утга', 'Өмнөх хэргийн утга', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
                <div class="col-md-8">
                    <?php echo form_textarea(array('name' => 'preValue', 'value' => $row->pre_value, 'rows' => 3, 'class' => 'form-control')); ?>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <?php echo form_label('Ирүүлсэн обьект' . ($row->object_count > 0 ? ' (' . $row->object_count . ')' : ''), 'Ирүүлсэн обьект' . ($row->object_count > 0 ? ' ' . $row->object_count . '' : ''), array('required' => 'required', 'class' => 'col-md-4 control-label text-right object-count', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_hidden('objectCount', $row->object_count);
                echo form_textarea(array(
                    'name' => 'object',
                    'id' => 'object',
                    'value' => $row->object,
                    'rows' => 2,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Хэргийн төрөл', 'Хэргийн төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlCategoryListDropdown; ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Ахалсан шинжээч', 'Ахалсан шинжээч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlSeniorExpertDropDown; ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Бичсэн шинжээч', 'Бичсэн шинжээч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlCreateExpertDropDown; ?>
            </div>
        </div>
        <?php echo $controlHrPeopleMultiListDropdown; ?>
    </div><!-- end col -->
    <div class="clearfix"></div>
</fieldset>

<fieldset>
    <legend> </legend>
    <div class="col-md-4">
        <div class="form-group">
            <?php echo form_label('Хэргийн дугаар', 'Хэргийн дугаар', array('required' => 'required', 'class' => 'col-md-6 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-6">
                <?php
                echo form_input(array(
                    'name' => 'protocolNumber',
                    'id' => 'protocolNumber',
                    'value' => $row->protocol_number,
                    'class' => 'form-control control-number text-right',
                    'required' => 'required',
                    'maxlength' => 14,
                    'readonly' => true
                ));
                ?>
                <span class="help-block"><i class="icon-help"></i> Захирамж, хэргийн №</span>
            </div>
            <div class="clearfix"></div>

        </div>

    </div>

    <div class="col-md-7">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo form_label('Тогтоолын огноо', 'Тогтоолын огноо', array('required' => 'required', 'class' => 'col-md-7 control-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-md-5">
                        <?php
                        echo form_input(array(
                            'name' => 'protocolInDate',
                            'id' => 'protocolInDate',
                            'value' => ($row->protocol_out_date != '0000-00-00 00:00:00' ? ($row->protocol_in_date != '1970-01-01 00:00:00' ? date('Y-m-d', strtotime($row->protocol_in_date)) : '') : ''),
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'readonly' => true,
                            'placeholder' => '____-__-__'
                        ));
                        ?>
                        <span class="help-block"><i class="icon-help"></i> Ирсэн огноо</span>
                    </div>    
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo form_label('Тогтоолын огноо', 'Тогтоолын огноо', array('required' => 'required', 'class' => 'col-md-7 control-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-md-5">
                        <?php
                        echo form_input(array(
                            'name' => 'protocolOutDate',
                            'id' => 'protocolOutDate',
                            'value' => ($row->protocol_out_date != '0000-00-00 00:00:00' ? ($row->protocol_out_date != '1970-01-01 00:00:00' ? date('Y-m-d', strtotime($row->protocol_out_date)) : '') : ''),
                            'maxlength' => '10',
                            'class' => 'form-control read-date',
                            'readonly' => true,
                            'placeholder' => '____-__-__'
                        ));
                        ?>
                        <span class="help-block"><i class="icon-help"></i> Дуусах огноо</span>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php echo form_close(); ?>