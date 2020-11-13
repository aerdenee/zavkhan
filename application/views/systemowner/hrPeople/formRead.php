<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-nifs-crime', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>
<fieldset>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label('Дугаар', 'Дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_number(array(
                    'name' => 'createNumber',
                    'id' => 'createNumber',
                    'value' => $row->create_number,
                    'class' => 'form-control control-number',
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
                    <label class="col-md-6 control-label">
                        <?php 
                        echo form_hidden('isMixx', $row->is_mixx);
                        echo form_checkbox(array('id' => 'mixCheckBox', 'name' => 'mixCheckBox', 'class' => 'radio', 'onclick' => '_isMixxNifsCrime({elem:this})', 'disabled' => true), 1, ($row->is_mixx == '1' ? true : false)); ?>
                        Бүрэлдэхүүнтэй эсэх </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <?php echo form_label('Үндэслэл', 'Үндэслэл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlMotiveDropdown; ?>
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
                            'class' => 'form-control init-date',
                            'required' => 'required',
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
            <?php echo form_label('Томилсон байгууллага', 'Томилсон байгууллага', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlPartnerDropdown; ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Албан тушаалтаны нэр', 'Албан тушаалтаны нэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
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
            <?php echo form_label('Шинжилгээний төрөл', 'Шинжилгээний төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlCategoryListDropdown; ?>
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
        
        <div class="form-group">
            <?php echo form_label('Холбогдох мэдээлэл', 'Холбогдох мэдээлэл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_textarea(array(
                    'name' => 'shortInfo',
                    'id' => 'shortInfo',
                    'value' => $row->short_info,
                    'rows' => 3,
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
                <span class="help-block"><i class="icon-help"></i> Нэхэмжлэгч, эзэн холбогдогчийн мэдээллийг энд бичнэ</span>
            </div>
        </div>
        
    </div><!-- end col -->

    <div class="col-lg-6">
        <div class="form-group">
            <?php echo form_label('Хэргийн төрөл', 'Хэргийн төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlCrimeTypeDropdown; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Хэргийн утга', 'Хэргийн утга', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'value',
                    'id' => 'value',
                    'value' => $row->value,
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'readonly' => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Мөр бэхжүүлсэн шинжээч', 'Мөр бэхжүүлсэн шинжээч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo $controlLatentPrintExpertDropDown;
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label('Ирүүлсэн обьект' . ($row->object_count > 0 ? ' (' . $row->object_count . ')' : '') , 'Ирүүлсэн обьект' . ($row->object_count > 0 ? ' ' . $row->object_count . '' : ''), array('required' => 'required', 'class' => 'col-md-4 control-label text-right object-count', 'defined' => TRUE)); ?>
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

        <?php
        $this->question = explode('|:||:|', $row->question);

        foreach ($this->question as $key => $value) {
            if (empty($key)) {

                echo '<div class="form-group">';
                echo form_label('Шинжээчид тавигдсан асуултууд', 'Шинжээчид тавигдсан асуултууд', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE));
                echo '<div class="col-md-8">';
                echo '<div class="input-group">';

                echo form_input(array(
                    'name' => 'question[]',
                    'value' => $value,
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'readonly' => true
                ));

                echo '<span class="input-group-btn">';
                echo form_button('addQuestion', '<i class="fa fa-plus"></i>', 'class="btn btn-primary" onclick="_addQuestion({elem: this});"', 'button');
                echo '</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '<span id="quistionHtml">';
            } else {
                echo '<div class="form-group">';
                echo form_label('', '', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => FALSE));
                echo '<div class="col-md-8">';
                echo '<div class="input-group">';

                echo form_input(array(
                    'name' => 'question[]',
                    'value' => $value,
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'readonly' => true
                ));

                echo '<span class="input-group-btn">';
                echo form_button('removeQuestion', '<i class="fa fa-remove"></i>', 'class="btn btn-primary" onclick="_removeQuestion({elem: this});"', 'button');
                echo '</span>';
                echo '</div>';
                echo '</div>';
                echo '<div class="clearfix"></div>';
                echo '</div>';
                echo '<div class="clearfix"></div>';
            }
        }
        echo '</span>';

        if ($controlExpertListDropdown) {
            echo $controlExpertListDropdown;
        } else {
            echo '<div class="form-group">';
                echo form_label('Шинжээч', 'Шинжээч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE));
                echo '<div class="col-md-8">';
                echo '<div class="input-group">';
                    echo $controlExpertDropDown;
                    echo '<span class="input-group-btn">';
                        echo form_button('addNifsCrimeExpertButton', '<i class="fa fa-plus"></i>', 'class="btn btn-primary" onclick="_addExpert({elem:this});" ' . ($row->is_mixx == 0 ? ' disabled="disabled"' : ''), 'button');
                    echo '</span>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<div id="addExpertHtml"></div>';
        }
        ?>
        
        

    </div><!-- end col -->
    <div class="clearfix"></div>
</fieldset>
<fieldset>
    <legend> </legend>
    <div class="col-md-4">
        <div class="form-group _margin-bottom-0">
            <?php echo form_label('Хэргийн дугаар', 'Хэргийн дугаар', array('required' => 'required', 'class' => 'col-md-6 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-6">
                <?php
                echo form_number(array(
                    'name' => 'protocolNumber',
                    'id' => 'protocolNumber',
                    'value' => $row->protocol_number,
                    'class' => 'form-control control-number',
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
                <div class="form-group _margin-bottom-0">
                    <?php echo form_label('Тогтоолын огноо', 'Тогтоолын огноо', array('required' => 'required', 'class' => 'col-md-7 control-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-md-5">
                        <?php
                        echo form_input(array(
                            'name' => 'protocolInDate',
                            'id' => 'protocolInDate',
                            'value' => date('Y-m-d', strtotime($row->protocol_in_date)),
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'readonly' => true
                        ));
                        ?>
                        <span class="help-block"><i class="icon-help"></i> Ирсэн</span>
                    </div>    
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group _margin-bottom-0">
                    <?php echo form_label('Тогтоолын огноо', 'Тогтоолын огноо', array('required' => 'required', 'class' => 'col-md-7 control-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-md-5">
                        <?php
                        echo form_input(array(
                            'name' => 'protocolOutDate',
                            'id' => 'protocolOutDate',
                            'value' => date('Y-m-d', strtotime($row->protocol_out_date)),
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'readonly' => true
                        ));
                        ?>
                        <span class="help-block"><i class="icon-help"></i> Дуусах</span>
                    </div>    
                </div>
            </div>
            <div class="clearfix"></div>
            
        </div>
    </div>
</fieldset>

<?php echo form_close(); ?>