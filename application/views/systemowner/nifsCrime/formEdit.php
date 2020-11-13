<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-crime', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>


<div class="row">
    <div class="col-8">
        <div class="row">
            <div class="col-4">
                <div class="form-group row mb-1">
                    <?php echo form_label('Дугаар', 'Дугаар', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-8">
                        <?php
                        echo form_input(array(
                            'name' => 'createNumber',
                            'id' => 'createNumber',
                            'value' => $row->create_number,
                            'class' => 'form-control control-journal-number',
                            'required' => 'required',
                            'tabindex' => 1
                        ));
                        ?>

                    </div>
                </div>
            </div>

            <div class="col-8">
                <div class="form-group row mb-1">
                    <?php echo form_label('Шинжилгээ', 'Шинжилгээ', array('required' => 'required', 'class' => 'col-3 col-form-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-9">
                        <div class="row">
                            <div class="col-6">
                                <?php echo $controlNifsResearchTypeDropdown; ?>
                            </div>
                            <div class="col-6">
                                <?php echo $controlNifsIsMixxCheckBox; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
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
                <span class="help-block"><i class="icon-help"></i> <span id="nifs-crime-in-out-date-diff-work-day">Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ.</span></span>
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Томилсон байгууллага', 'Томилсон байгууллага', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8"><?php echo $controlPartnerDropdown; ?></div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Албан тушаалтаны нэр', 'Албан тушаалтаны нэр', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php
                echo form_input(array(
                    'name' => 'agentName',
                    'id' => 'agentName',
                    'value' => $row->agent_name,
                    'maxlength' => '500',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Шинжилгээний төрөл', 'Шинжилгээний төрөл', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8"><?php echo $controlCategoryListDropdown; ?></div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php
                echo form_input(array(
                    'name' => 'description',
                    'id' => 'description',
                    'value' => $row->description,
                    'rows' => 3,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Холбогдох мэдээлэл', 'Холбогдох мэдээлэл', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php
                echo form_input(array(
                    'name' => 'shortInfo',
                    'id' => 'shortInfo',
                    'value' => $row->short_info,
                    'rows' => 3,
                    'class' => 'form-control'
                ));
                ?>
                <span class="help-block"><i class="icon-help"></i> Нэхэмжлэгч, эзэн холбогдогчийн мэдээлэл</span>
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Хэргийн төрөл', 'Хэргийн төрөл', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php echo $controlNifsMasterCaseDropdown; ?>
            </div>
        </div>

    </div><!-- end col -->

    <div class="col-6">
        <div class="form-group row">
            <?php echo form_label('Хэргийн өнгө', 'Хэргийн өнгө', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php echo $controlNifsCrimeTypeDropdown; ?>
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Хэргийн утга', 'Хэргийн утга', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php
                echo form_input(array(
                    'name' => 'crimeValue',
                    'id' => 'crimeValue',
                    'value' => $row->crime_value,
                    'rows' => 3,
                    'class' => 'form-control'
                ));
                ?>
                <span class="help-block"><i class="icon-help"></i> Нэхэмжлэгч, эзэн холбогдогчийн мэдээлэл</span>
            </div>
        </div>


        <!--        <div class="form-group row">
        <?php echo form_label('Газар, хэлтэс', 'Газар, хэлтэс', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
                    <div class="col-8">
        <?php echo $controlHrPeopleDepartmentDropdown; ?>
                        <span class="help-block"><i class="icon-help"></i> Мөр бэхжүүлсэн шинжээчид хамаарах газар, хэлтэс</span>
                    </div>
                </div>-->
        <div class="form-group row">
            <?php echo form_label('Мөр бэхжүүлсэн шинжээч', 'Мөр бэхжүүлсэн шинжээч', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8"><div id="nifs-latent-print-expert"><?php echo $controlHrPeopleLatentPrintExpertListDropdown; ?></div></div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Ирүүлсэн обьект' . ($row->object_count > 0 ? ' (' . $row->object_count . ')' : ''), 'Ирүүлсэн обьект' . ($row->object_count > 0 ? ' ' . $row->object_count . '' : ''), array('required' => 'required', 'class' => 'col-4 col-form-label text-right object-count', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php
                echo form_hidden('objectCount', $row->object_count);
                echo form_textarea(array(
                    'name' => 'crimeObject',
                    'id' => 'crimeObject',
                    'value' => $row->crime_object,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
                <span class="help-block">
                    <i class="icon-help"></i> <span>Обьектоо бичээд дундуур зуруус татаад тоог бичнэ. Бичиж дуусаад ENTER товч дараад дараагийн обьектыг бичнэ. <i>/Бээлий-2/</i></span>
                </span>
            </div>
        </div>

        <?php
        $this->question = explode('|:||:|', $row->question);

        foreach ($this->question as $key => $value) {
            if (empty($key)) {



                echo '<div class="form-group row">';
                echo form_label('Шинжээчийн асуулт', 'Шинжээчийн асуулт', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE));
                echo '<div class="col-8">';

                echo '<div class="input-group">';
                echo form_input(array(
                    'name' => 'question[]',
                    'value' => $value,
                    'maxlength' => '500',
                    'class' => 'form-control border-right-0'
                ));
                echo '<span class="input-group-append">';
                echo '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_addQuestion({elem: this});">';
                echo '<i class="icon-plus-circle2"></i>';
                echo '</span>';
                echo '</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '<span id="quistionHtml">';
            } else {

                echo '<div class="form-group">';
                echo form_label('&nbsp;', '&nbsp;', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => FALSE));
                echo '<div class="col-8">';
                echo '<div class="input-group">';

                echo form_input(array(
                    'name' => 'question[]',
                    'value' => $value,
                    'maxlength' => '500',
                    'class' => 'form-control border-right-0'
                ));

                echo '<span class="input-group-append">';
                echo '<span class="input-group-text bg-pink border-pink text-white cursor-pointer" onclick="_removeQuestion({elem: this});">';
                echo '<i class="fa fa-remove"></i>';
                echo '</span>';
                echo '</span>';
                echo '</div>';
                echo '</div>';
                echo '<div class="clearfix"></div>';
                echo '</div>';
                echo '<div class="clearfix"></div>';
            }
        }
        echo '</span>';

        echo $controlHrPeopleExpertMultiListDropdown;
        ?>

        <div id="initCrimeControlExpertHtmlExtra" class="<?php echo ($row->extra_expert_value != '' ? 'show' : 'hide'); ?>">
            <div class="form-group row">
                <?php echo form_label('&nbsp;', '&nbsp;', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => FALSE)); ?>
                <div class="col-8">
                    <?php echo form_textarea(array('name' => 'extraExpertValue', 'value' => $row->extra_expert_value, 'rows' => 3, 'class' => 'form-control')); ?>
                </div>
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
                    <i class="icon-help"></i> <span id="nifs-crime-protocol-in-out-date-diff-work-day">Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ.</span>
                </span>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>