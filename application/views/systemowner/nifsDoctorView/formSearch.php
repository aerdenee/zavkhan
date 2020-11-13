<?php
echo form_open('javascript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-doctor-view-search', 'enctype' => 'multipart/form-data', 'method' => 'get'));
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
    
    <div class="col-md-3">
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
    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label('Хэргийн №', 'Хэргийн №', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo form_input(array(
                'name' => 'protocolNumber',
                'id' => 'protocolNumber',
                'placeholder' => '001 гэх мэт',
                'maxlength' => 20,
                'class' => 'form-control text-right',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Үндэслэл', 'Үндэслэл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsMotiveDropdown; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Ирүүлсэн байгууллага', 'Ирүүлсэн байгууллага', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlPartnerDropdown; ?>
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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('Хэрэг болсон огноо', 'Хэрэг болсон огноо', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'crimeInDate',
                        'id' => 'crimeInDate',
                        'placeholder' => '____-__-__',
                        'maxlength' => '10',
                        'class' => 'form-control init-date',
                        'readonly' => true,
                        'required' => true
                    ));
                    ?>
                    <span class="help-block"><i class="icon-help"></i> Эхлэх</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('&nbsp;', '&nbsp;', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    echo form_input(array(
                        'name' => 'crimeOutDate',
                        'id' => 'crimeOutDate',
                        'placeholder' => '____-__-__',
                        'maxlength' => '10',
                        'class' => 'form-control init-date',
                        'readonly' => true,
                        'required' => true
                    ));
                    ?>
                    <span class="help-block"><i class="icon-help"></i> Дуусах</span>
                </div>

            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group" style="margin-bottom: 0;">
            <style type="text/css">
                .uniform-checker {
                    float: left;
                }

            </style>
            <?php
            echo form_label('Нас', 'Нас', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
            echo '<div class="clearfix"></div>';
            echo form_input(array(
                'name' => 'age1',
                'id' => 'age1',
                'placeholder' => '0',
                'maxlength' => '3',
                'class' => 'form-control',
                'style' => 'width: 50px; float:left; margin-right:20px; text-align:right;'
            ));
            echo form_input(array(
                'name' => 'age2',
                'id' => 'age2',
                'placeholder' => '100',
                'maxlength' => '3',
                'class' => 'form-control',
                'style' => 'width: 50px; float:left; text-align:right; margin-right:20px;'
            ));
            echo '<label style="margin-top: 0px; float:left; font-size:11px;"> ' . form_checkbox(array('name' => 'isAgeInfinitive', 'class' => 'radio', 'style' => 'float:left;'), '1', false) . 'Нас тодорхойгүй </label>';
            ?>
            <div class="clearfix"></div>
            <span class="help-block"><i class="icon-help"></i> 5 <= Нас <= 60, 10<= Нас, Нас <= 80 эвсэл сонго. <span id="in-out-date-diff"></span></span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <select class="select2" name="sex">
                <option value="0"> - Бүгд - </option>
                <option value="1"> - Эр - </option>
                <option value="2"> - Эм - </option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Хэрэг болсон товч утга', 'Хэрэг болсон товч утга', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <span id="control-department-dropdown-html"><?php echo $controlCrimeShortValueDropdown; ?></span>
        </div>
    </div>
    <span id="initDoctorViewControlIsSpermHtml"><input type="hidden" name="isSperm" value="0"></span>
    <span id="initDoctorViewControlIsCrimeShipHtml"><input type="hidden" name="isCrimeShip" value="0"></span>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Шинжээч эмч', 'Шинжээч эмч', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlExpertDropdown; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Хаана', 'Хаана', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlNifsWhereDropdown; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Хэргийн төрөл', 'Хэргийн төрөл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <?php echo $controlCategoryListDropdown; ?>
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
                        'maxlength' => '10',
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
                        'maxlength' => '10',
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
            <?php echo form_label('Төлбөр', 'Төлбөр', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
            <select class="select2" name="payment">
                <option value="0"> - Бүгд - </option>
                <option value="1"> - Төлбөр төлсөн - </option>
                <option value="2"> - Төлбөр төлөөгүй - </option>
                <option value="3"> - Чөлөөлсөн - </option>
            </select>
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
            <?php echo form_label('Ажил', 'Ажил', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>

            <?php echo $controlWorkDropdown; ?>
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