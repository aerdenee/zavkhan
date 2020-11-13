<?php echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get')); ?>
<div class="row">
    <div class="col-6">
        <div class="form-group row mb-0">
            <div class="col-6">
                <div class="form-group">
                    <?php
                    echo form_label('Огноо /эхлэл/', 'Огноо /эхлэл/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'inDate',
                        'id' => 'inDate',
                        'placeholder' => '___-__-__',
                        'maxlength' => '10',
                        'class' => 'form-control init-date control-date',
                        'readonly' => true,
                        'required' => true,
                        'style' => 'width:100px;'
                    ));
                    ?>
                </div>

            </div>
            <div class="col-6">
                <div class="form-group">
                    <?php
                    echo form_label('Огноо /дуусах/', 'Огноо /дуусах/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'outDate',
                        'id' => 'outDate',
                        'placeholder' => '___-__-__',
                        'maxlength' => '10',
                        'class' => 'form-control init-date',
                        'readonly' => true,
                        'required' => true,
                        'style' => 'width:100px;'
                    ));
                    ?>
                </div>
            </div>
        </div>

    </div>
    <div class="col-6">
        <div class="form-group">
            <?php
            echo form_label('Салбар хэлтэс', 'Салбар хэлтэс', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
            echo $controlHrPeopleDepartmentDropdown;
            ?>
        </div>
    </div>
    <div class="col-12">

        <div class="form-group mb-0">
                <?php
                echo form_input(array(
                    'name' => 'keyword',
                    'id' => 'keyword',
                    'placeholder' => 'Түлхүүр үг',
                    'maxlength' => '50',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>