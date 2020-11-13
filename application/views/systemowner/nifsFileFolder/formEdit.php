<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-file-folder', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('createdUserId', $row->created_user_id);
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
                <span class="help-block"><i class="icon-help"></i> <span id="nifs-file-folder-in-out-date-diff-work-day">Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ.</span></span>
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
                    'maxlength' => 250,
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
                    'maxlength' => 250,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Томилсон байгууллага', 'Томилсон байгууллага', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlPartnerDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Албан тушаалтны нэр', 'Албан тушаалтны нэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'agentName',
                    'id' => 'agentName',
                    'value' => $row->agent_name,
                    'maxlength' => 250,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Шийдвэрлэх асуудал', 'Шийдвэрлэх асуудал', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsQuestionDropDown; ?>
            </div>
        </div>
        <span id="initFileFolderControlQuestionHtml">
            <?php
            if ($row->question_id == 38) {
                echo '<div class="form-group row">';
                echo form_label('Нас', 'Нас', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE));
                echo '<div class="col-md-8">';
                echo form_input(array(
                    'name' => 'age',
                    'id' => 'age',
                    'value' => $row->age,
                    'maxlength' => '4',
                    'class' => 'form-control init-control-age'
                ));
                echo '</div>';
                echo '</div>';
            } else {
                echo form_hidden('age', 0);
            }
            ?>
        </span>
        <div class="form-group row">
            <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
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

    </div>
    <div class="col-6">
        <div class="form-group row">
            <?php echo form_label('Өмнөх дүгнэлт', 'Өмнөх дүгнэлт', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <span class="input-group-text bg-primary border-primary text-white cursor-pointer" style="margin: 0.125rem 0 0 0.125rem; padding: 0.1rem 0.4rem; height: 23px; cursor: pointer; float:left;" onclick="_addFormNifsPreCrime({elem: this});"><i class="fa fa-plus"></i></span>
                <span class="tokenfield" id="window-nifs-pre-crime">
                    <?php
                    if ($row->pre_crime != '') {
                        $preCrime = json_decode($row->pre_crime);
                        foreach ($preCrime as $preCrimeKey => $value) {
                            echo '<span class="token" data-key="' . date('YmdHis') . $preCrimeKey . '" ondblclick="_editFormNifsPreCrime({elem:this})">';
                            echo '<span class="token-label">';
                            echo '<input type="hidden" value="' . $preCrimeKey . '" name="preCrimeKey[]">';
                            echo '<input type="hidden" value="' . $value['0'] . '" name="preCrimeCreateNumber[]"> ' . $value['0'] . ' - ';
                            echo '<input type="hidden" value="' . $value['1'] . '" name="preCrimeExpert[]"> ' . $value['1'] . ', ';
                            echo '<input type="hidden" value="' . $value['2'] . '" name="preCrimeCrimeValue[]"> ' . $value['2'];
                            echo '</span>';
                            echo '<a href="javascript:;" onclick="_removeNifsPreCrime({elem: this})" class="close" tabindex="-1" aria-label="Remove">×</a></span>';
                        }
                    }
                    ?>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Ирүүлсэн объект' . ($row->object_count > 0 ? ' (' . $row->object_count . ')' : ''), 'Ирүүлсэн объект' . ($row->object_count > 0 ? ' ' . $row->object_count . '' : ''), array('required' => 'required', 'class' => 'col-md-4 control-label text-right object-count', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_hidden('objectCount', $row->object_count);
                echo form_textarea(array(
                    'name' => 'object',
                    'id' => 'object',
                    'value' => $row->object,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Хэргийн төрөл', 'Хэргийн төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlCategoryListDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Ахалсан шинжээч', 'Ахалсан шинжээч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlSeniorExpertDropDown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Бичсэн шинжээч', 'Бичсэн шинжээч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlCreateExpertDropDown; ?>
            </div>
        </div>
        <?php echo $controlHrPeopleMultiListDropdown; ?>
        <div id="initFileFolderControlExpertHtmlExtra" class="<?php echo ($row->extra_expert_value != '' ? 'show' : 'hide'); ?>">
            <div class="form-group row">
                <?php echo form_label('', '', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => FALSE)); ?>
                <div class="col-md-8">
                    <?php echo form_textarea(array('name' => 'extraExpertValue', 'value' => $row->extra_expert_value, 'rows' => 3, 'class' => 'form-control')); ?>
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
                    <i class="icon-help"></i> <span id="nifs-file-folder-protocol-in-out-date-diff-work-day">Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ.</span>
                </span>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>
