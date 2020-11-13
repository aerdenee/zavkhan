<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-send-document', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('moduleId', $row->module_id);
echo form_hidden('contId', $row->cont_id);
echo form_hidden('createdUserId', $row->created_user_id);
echo form_hidden('typeId', $row->type_id);
?>
<div class="row">

    <div class="col-6">

        <div class="form-group row">
            <?php echo form_label('Дугаар', 'Дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
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
                <span class="help-block"><i class="icon-help"></i> <span id="nifs-send-document-in-out-date-diff-work-day">Шинжилгээг зөвхөн ажлын өдрүүдээр хийнэ.</span></span>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Ирүүлсэн обьект' . ($row->object_count > 0 ? ' (' . $row->object_count . ')' : ''), 'Ирүүлсэн обьект' . ($row->object_count > 0 ? ' ' . $row->object_count . '' : ''), array('required' => 'required', 'class' => 'col-4 col-form-label text-right object-count', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php
                echo form_hidden('objectCount', $row->object_count);
                echo form_textarea(array(
                    'name' => 'sendObject',
                    'id' => 'sendObject',
                    'value' => $row->send_object,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
                <span class="help-block">
                    <i class="icon-help"></i> <span>Обьектоо бичээд дундуур зуруус татаад тоог бичнэ. Бичиж дуусаад ENTER товч дараад дараагийн обьектыг бичнэ. <i>/Бээлий-2/</i></span>
                </span>
            </div>
        </div>

    </div><!-- end col -->

    <div class="col-6">
        

        <div class="form-group row">
            <?php echo form_label('Салбар, хэлтэс', 'Салбар, хэлтэс', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo $controlHrPeopleDepartmentDropdown;
                ?>
            </div>
        </div>
        
        <?php echo $controlNifsQuestionDropDown; ?>

        <div class="form-group row">
            <?php echo form_label('Нэмэлт асуулт', 'Нэмэлт асуулт', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'questionExtra',
                    'id' => 'questionExtra',
                    'value' => $row->question_extra,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <?php echo $controlHrPeopleExpertMultiListDropdown; ?>

    </div><!-- end col -->
</div>
<?php echo form_close(); ?>
