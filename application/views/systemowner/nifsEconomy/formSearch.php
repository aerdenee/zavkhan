<?php
echo form_open('javascript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get'));
echo form_hidden('isActive', 0);
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
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label('Бүртгэл №', 'Бүртгэл №', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo form_input(array(
                'name' => 'createNumber',
                'id' => 'createNumber',
                'placeholder' => '001 гэх мэт',
                'maxlength' => '50',
                'class' => 'form-control text-right',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label('Хэргийн №', 'Хэргийн №', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo form_input(array(
                'name' => 'protocolNumber',
                'id' => 'protocolNumber',
                'placeholder' => '001 гэх мэт',
                'maxlength' => '50',
                'class' => 'form-control text-right',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Шинжилгээ', 'Шинжилгээ', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <div class="row">
                <div class="col-md-6"><?php echo $controlNifsResearchTypeDropdown; ?></div>
                <div class="col-md-6"><?php echo $controlNifsIsMixxDropdown; ?></div>
            </div>

        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Үндэслэл', 'Үндэслэл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsMotiveDropdown; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('Огноо /эхлэл/', 'Огноо /эхлэл/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'inDate',
                        'id' => 'inDate',
                        'placeholder' => '____-__-__',
                        'maxlength' => 10,
                        'class' => 'form-control init-date control-date',
                        'required' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('Огноо /дуусах/', 'Огноо /дуусах/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'outDate',
                        'id' => 'outDate',
                        'placeholder' => '____-__-__',
                        'maxlength' => 10,
                        'class' => 'form-control init-date',
                        'required' => true
                    ));
                    ?>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Томилсон байгууллага', 'Томилсон байгууллага', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlPartnerDropdown; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Шинжилгээний төрөл', 'Шинжилгээний төрөл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsCrimeTypeDropdown; ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Хэргийн төрөл', 'Хэргийн төрөл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsMasterCaseDropdown; ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Шинжээчид тавигдсан асуултууд', 'Шинжээчид тавигдсан асуултууд', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsQuestionDropDown; ?>
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
            <?php echo form_label('Шийдвэрлэх асуудал', 'Шийдвэрлэх асуудал', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsSolutionDropdown; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('Тогтоолын огноо', 'Тогтоолын огноо', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'protocolInDate',
                        'id' => 'protocolInDate',
                        'placeholder' => '____-__-__',
                        'maxlength' => 10,
                        'class' => 'form-control init-date control-date',
                        'readonly' => true,
                        'required' => true
                    ));
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('Тогтоолын огноо', 'Тогтоолын огноо', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'protocolOutDate',
                        'id' => 'protocolOutDate',
                        'placeholder' => '____-__-__',
                        'maxlength' => 10,
                        'class' => 'form-control init-date',
                        'readonly' => true,
                        'required' => true
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php
            echo form_label('Шинжилгээний төлөв', 'Шинжилгээний төлөв', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
            echo $controlNifsStatusDropdown;
            ?>
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
            <?php
            echo form_label('Салбар хэлтэс', 'Салбар хэлтэс', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
            echo $controlHrPeopleDepartmentDropdown;
            ?>
        </div>
    </div>
</div>

<?php echo form_close(); ?>