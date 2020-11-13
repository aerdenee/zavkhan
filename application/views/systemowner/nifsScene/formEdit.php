<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-scene', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>
<div class="row">
    <div class="col-6">

        <div class="form-group row">
            <?php echo form_label('Дугаар', 'Дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
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
                            'readonly' => true,
                            'tabindex' => 4
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
                <div style="width: 120px; float:left; margin-left: 20px;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'outTime',
                            'id' => 'outTime',
                            'value' => date('H:i', strtotime($row->out_date)),
                            'maxlength' => '10',
                            'class' => 'form-control init-pickatime',
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
            <?php echo form_label('Цагдаагийн хэлтэс', 'Цагдаагийн хэлтэс', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlPartnerDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Мөрдөн байцаагч', 'Мөрдөн байцаагч', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'sceneExpert',
                    'id' => 'sceneExpert',
                    'value' => $row->scene_expert,
                    'maxlength' => 255,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <?php echo $controlHrPeopleExpertMultiListDropdown; ?>

        <div id="initSceneControlExpertHtmlExtra" class="<?php echo ($row->extra_expert_value != '' ? 'show' : 'hide'); ?>">
            <div class="form-group row">
                <?php echo form_label('', '', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => FALSE)); ?>
                <div class="col-md-8">
                    <?php echo form_textarea(array('name' => 'extraExpertValue', 'value' => $row->extra_expert_value, 'rows' => 3, 'class' => 'form-control')); ?>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Үзлэгийн төрөл', 'Үзлэгийн төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8"><?php echo $controlCategoryListDropdown; ?></div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Гэмт хэргийн төрөл', 'Гэмт хэргийн төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlNifsSceneTypeDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Хэргийн утга', 'Хэргийн утга', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'sceneValue',
                    'id' => 'sceneValue',
                    'value' => $row->scene_value,
                    'maxlength' => 255,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Ул мөр илэрсэн эсэх', 'Ул мөр илэрсэн эсэх', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('class' => 'radio', 'name' => 'isTrace'), 1, ($row->is_trace == 1 ? TRUE : '')); ?>
                        Тийм
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <?php echo form_radio(array('class' => 'radio', 'name' => 'isTrace'), 0, ($row->is_trace == 0 ? TRUE : '')); ?>
                        Үгүй
                    </label>
                </div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-6">

        <div class="form-group row">
            <?php echo form_label('Гарын мөр бэхжүүлсэн арга', 'Гарын мөр бэхжүүлсэн арга', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlFingerPrintTypeDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Гарын мөр', 'Гарын мөр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right finger-print-count', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'fingerCount',
                    'id' => 'fingerCount',
                    'value' => $row->finger_count,
                    'rows' => 2,
                    'class' => 'form-control _init-number'
                ));
                ?>
            </div>
        </div>
        
        <div class="form-group row">
            <?php echo form_label('Гарын хээний дардас', 'Гарын хээний дардас', array('required' => 'required', 'class' => 'col-md-4 control-label text-right finger-print-count', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'fingerPrintCount',
                    'id' => 'fingerPrintCount',
                    'value' => $row->finger_print_count,
                    'rows' => 2,
                    'class' => 'form-control _init-number'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Гутлын мөр бэхжүүлсэн арга', 'Гутлын мөр бэхжүүлсэн арга', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlBootPrintTypeDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Гутлын мөр', 'Гутлын мөр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right boot-print-count', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'bootPrintCount',
                    'id' => 'bootPrintCount',
                    'value' => $row->boot_print_count,
                    'rows' => 2,
                    'class' => 'form-control _init-number'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Тээврийн хэрэгслийн мөр бэхжүүлсэн арга', 'Тээврийн хэрэгслийн мөр бэхжүүлсэн арга', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlTransportPrintTypeDropdown; ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Тээврийн хэрэгслийн мөр', 'Тээврийн хэрэгслийн мөр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right transport-print-count', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'transportPrintCount',
                    'id' => 'transportPrintCount',
                    'value' => $row->transport_print_count,
                    'rows' => 2,
                    'class' => 'form-control _init-number'
                ));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Бусад ул мөр, эд мөрийн баримт' . ($row->other_print_count > 0 ? ' (' . $row->other_print_count . ')' : ''), 'Бусад ул мөр, эд мөрийн баримт' . ($row->transport_print_count > 0 ? ' ' . $row->transport_print_count . '' : ''), array('required' => 'required', 'class' => 'col-md-4 control-label text-right other-print-count', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_hidden('otherPrintCount', $row->other_print_count);
                echo form_textarea(array(
                    'name' => 'otherPrint',
                    'id' => 'otherPrint',
                    'value' => $row->other_print,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
                <span class="help-block">
                    <i class="icon-help"></i> <span>Баримтжуулсан эд зүйлсээ бичээд дундуур зуруус татаад тоог бичнэ. Бичиж дуусаад ENTER товч дараад дараагийн эд зүйлсийг бичнэ. <i>/Бээлий-2/</i></span>
                </span>
            </div>
        </div>
        

        <div class="form-group row">
            <?php echo form_label('Гэрэл зураг', 'Гэрэл зураг', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'photoCount',
                    'id' => 'photoCount',
                    'value' => $row->photo_count,
                    'rows' => 2,
                    'class' => 'form-control _init-number'
                ));
                ?>
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
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>

    </div><!-- end col -->
</div>
<?php echo form_close(); ?>