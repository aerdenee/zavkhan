<?php
echo form_open('javscript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-scene-search', 'enctype' => 'multipart/form-data', 'method' => 'get'));
echo form_hidden('isActive', 0);
?>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?php
            echo form_label('Бүртгэл №', 'Бүртгэл №', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));
            echo form_input(array(
                'name' => 'createNumber',
                'id' => 'createNumber',
                'placeholder' => '001 гэх мэт',
                'maxlength' => 20,
                'class' => 'form-control text-right',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
                <?php echo form_label('Ирсэн огноо, цаг', 'Ирсэн огноо, цаг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));?>
                <div style="float: left;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'inDate',
                            'id' => 'inDate',
                            'placeholder' => '____-__-__',
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true,
                            'tabindex' => 3
                        ));
                        ?>
                    </div>
                </div>
                <div style="float:left; margin-left: 20px;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'inTime',
                            'id' => 'inTime',
                            'placeholder' => '__-__',
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
            <div class="col-md-6">
                <?php echo form_label('Дууссан огноо, цаг', 'Дууссан огноо, цаг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));?>
                <div style="float: left;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'outDate',
                            'id' => 'outDate',
                            'placeholder' => '____-__-__',
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true,
                            'tabindex' => 3
                        ));
                        ?>
                    </div>
                </div>
                <div style="float:left; margin-left: 20px;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'outTime',
                            'id' => 'outTime',
                            'placeholder' => '__-__',
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
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <?php
            echo form_label('Төрөл', 'Төрөл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));
            echo controlSearchTypeDropdown(array('modId' => $row->mod_id));
            ?>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <?php
            echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));

            echo form_input(array(
                'name' => 'keyword',
                'id' => 'keyword',
                'placeholder' => 'Овог, нэр, утасны дугаар, бусад үгээр хайлт хийнэ',
                'maxlength' => '50',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Цагдаагийн хэлтэс', 'Цагдаагийн хэлтэс', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <span id="control-department-dropdown-html"><?php echo $controlPartnerDropdown; ?></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Үзлэгийн төрөл', 'Үзлэгийн төрөл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlCategoryListDropdown; ?>
        </div>
    </div>
    

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Шинжээч', 'Шинжээч', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlHrPeopleExpertListDropdown; ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Салбар хэлтэс', 'Салбар хэлтэс', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlHrPeopleDepartmentDropdown; ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Гэмт хэргийн төрөл', 'Гэмт хэргийн төрөл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsSceneTypeDropdown; ?>
        </div>
    </div>
    
    

</div>

<?php echo form_close(); ?>