<?php
echo form_open(Slearning::$path . 'index/', array('class' => 'form-horizontal', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get'));
?>
<div class="row">
    <div class="col-md-6">
        <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
        <?php echo $controlCategoryListDropdown;?>
        <div class="clearfix" style="margin-bottom: 20px;"></div>
    </div>
    <div class="col-md-6">
        <?php echo form_label('Харилцагч байгууллага', 'Харилцагч байгууллага', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
        <?php echo $controlPartnerDropdown;?>
        <div class="clearfix" style="margin-bottom: 20px;"></div>
    </div>
    <div class="col-md-6">
        <?php echo form_label('Мэдээлэл оруулсан', 'Мэдээлэл оруулсан', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
        <?php echo $controlAuthorDropdown;?>
        <div class="clearfix" style="margin-bottom: 20px;"></div>
    </div>
    <div class="col-md-6">
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
        <div class="clearfix" style="margin-bottom: 20px;"></div>
        </div>
<!--    
    <div class="col-md-12">
        <?php echo form_label('Агуулгын хэлбэр', 'Агуулгын хэлбэр', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
        <div class="col-md-12"><?php echo $controlThemeLayoutRadio;?></div>
        <div class="clearfix"></div>
    </div>-->
    
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
                <span class="help-block"><i class="icon-help"></i> Хоёр хугацааны хооронд хайлт хийх боломжтой. <span id="in-out-date-diff"></span></span>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>