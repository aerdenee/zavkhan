<?php
echo form_open('javascript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-send-document-search', 'enctype' => 'multipart/form-data', 'method' => 'get'));
?>
<div class="row">
    
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
            <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
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
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Бүртгэлийн №', 'Бүртгэлийн №', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
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

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">

                    <?php
                    echo form_label('Огноо /эхлэл/', 'Огноо /эхлэл/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'inDate',
                        'id' => 'inDate',
                        'placeholder' => 'Эхлэх огноо',
                        'maxlength' => '10',
                        'class' => 'form-control init-date control-date',
                        'readonly' => true,
                        'required' => true
                    ));
                    ?>

            </div>
            <div class="col-md-6">
                    <?php
                    echo form_label('Огноо /дуусах/', 'Огноо /дуусах/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'outDate',
                        'id' => 'outDate',
                        'placeholder' => 'Дуусах огноо',
                        'maxlength' => '10',
                        'class' => 'form-control init-date',
                        'readonly' => true,
                        'required' => true
                    ));
                    ?>

            </div>
            <div class="col-md-12">
                <span class="help-block"><i class="icon-help"></i> Шинжилгээнд шилжүүлсэн болон дуусах огноо. <span id="in-out-date-diff"></span></span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Шинжээч', 'Шинжээч', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlExpertDropdown; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Шинжилгээний төрөл', 'Шинжилгээний төрөл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsCrimeTypeDropdown; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('Хаасан огноо', 'Хаасан огноо', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'closeInDate',
                        'id' => 'closeInDate',
                        'placeholder' => '____-__-__',
                        'maxlength' => '10',
                        'class' => 'form-control init-date control-date',
                        'readonly' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('Хаасан огноо', 'Хаасан огноо', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'closeOutDate',
                        'id' => 'closeOutDate',
                        'placeholder' => '____-__-__',
                        'maxlength' => '10',
                        'class' => 'form-control init-date',
                        'readonly' => true
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">

        <div class="form-group">
            <div class="form-group">
                <?php echo form_label('Шинжилгээний төлөв', 'Шинжилгээний төлөв', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
                <?php echo $controlNifsStatusDropdown; ?>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Дүгнэлтийн утга', 'Дүгнэлтийн утга', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>

            <?php echo $controlNifsCloseTypeDropdown; ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Асуулт', 'Асуулт', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsQuestionDropDown; ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Салбар хэлтэс', 'Салбар хэлтэс', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlHrPeopleDepartmentDropdown; ?>
        </div>
    </div>

</div>

<?php echo form_close(); ?>