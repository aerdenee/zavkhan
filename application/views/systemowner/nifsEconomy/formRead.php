<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-nifs-economy', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('catId', 0);
?>
<fieldset>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label('Дугаар', 'Дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
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
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <?php echo form_label('Шинжилгээ', 'Шинжилгээ', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $controlNifsResearchTypeDropdown; ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $controlNifsIsMixxCheckBox; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <?php echo form_label('Үндэслэл', 'Үндэслэл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsMotiveDropdown; ?>
            </div>
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
                            'class' => 'form-control read-date',
                            'required' => 'required',
                            'readonly' => true,
                            'style' => 'width:105px;'
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
                                    'class' => 'form-control read-date',
                                    'required' => 'required',
                                    'readonly' => true,
                                    'style' => 'width:105px;'
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
            <?php echo form_label('Шинижлгээний төрөл', 'Шинижлгээний төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsCrimeTypeDropdown; ?>
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
                    'rows' => 4,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-lg-6">

        <div class="form-group">
            <?php echo form_label('Хэргийн утга', 'Хэргийн утга', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_textarea(array(
                    'name' => 'protocolValue',
                    'id' => 'protocolValue',
                    'value' => $row->protocol_value,
                    'rows' => 3,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Ирүүлсэн обьект', 'Ирүүлсэн обьект', array('required' => 'required', 'class' => 'col-md-4 control-label text-right object-count', 'defined' => TRUE)); ?>
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
            <?php echo form_label('Шинжээчид тавигдсан асуултууд', 'Шинжээчид тавигдсан асуултууд', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsQuestionDropDown; ?>
            </div>
        </div>
        <div id="nifs-economy-additional-question"><?php
            if ($row->question_id == 31) {
                echo '<div class="form-group">';
                echo form_label('Шинжээчид тавигдсан асуултууд', 'Шинжээчид тавигдсан асуултууд', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE));
                echo '<div class="col-md-8">';
                echo form_textarea(array(
                    'name' => 'question',
                    'id' => 'question',
                    'value' => $row->question,
                    'rows' => 2,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                echo '</div>';
                echo '</div>';
            } else {
                echo form_hidden('question', $row->question);
            }
            ?></div>
        <?php echo $controlHrPeopleExpertMultiListDropdown; ?>
        <div id="init-control-economy-extra-expert-value-html" class="<?php echo ($row->extra_expert_value != '' ? 'show' : 'hide'); ?>">
            <div class="form-group">
                <?php echo form_label('', '', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => FALSE)); ?>
                <div class="col-md-8">
                    <?php echo form_textarea(array('name' => 'extraExpertValue', 'value' => $row->extra_expert_value, 'rows' => 3, 'class' => 'form-control', 'readonly' => true)); ?>
                </div>
            </div>
        </div>
    </div><!-- end col -->
    <div class="clearfix"></div>
</fieldset>

<?php echo form_close(); ?>
