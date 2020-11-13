<?php
echo form_open(Sstudentfinance::$path . 'index/' . $modId, array('class' => 'form-horizontal', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get'));
echo form_hidden('isActive', 0);
?>

<div class="col-md-3">
    <?php
    echo form_label('Элссэн огноо', 'Элссэн огноо', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
    echo form_input(array(
        'name' => 'createdDate',
        'id' => 'createdDate',
        'placeholder' => 'Элссэн огноо',
        'maxlength' => '10',
        'class' => 'form-control init-date',
        'readonly' => true,
        'required' => true
    ));
    ?>
</div>
<div class="col-md-3">
    <?php
    echo form_label('Төгссөн огноо', 'Төгссөн огноо', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
    echo form_input(array(
        'name' => 'endDate',
        'id' => 'endDate',
        'placeholder' => 'Төгссөн огноо',
        'maxlength' => '10',
        'class' => 'form-control init-date',
        'readonly' => true,
        'required' => true
    ));
    ?>
</div>
<div class="col-md-6">
    <?php 
    echo form_label('Оюутны код', 'Оюутны код', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); 
    echo form_input(array(
        'name' => 'code',
        'id' => 'code',
        'placeholder' => 'Оюутны код',
        'maxlength' => '10',
        'class' => 'form-control',
        'required' => true
    ))
    ?>
</div>
<div class="clearfix" style="margin-bottom: 20px;"></div>
<div class="col-md-4">
    <?php 
    echo form_label('Анги', 'Анги', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));
    echo $controlClassListDropdown;?>
</div>
<div class="col-md-8">
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
<div class="clearfix" style="margin-bottom: 20px;"></div>
<div class="col-md-4">
    <?php echo form_label('Нийслэл/аймаг', 'Нийслэл/аймаг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <?php echo $controlCityDropdown; ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-4">
    <?php echo form_label('Дүүрэг/сум', 'Дүүрэг/сум', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <span id="address-soum-html"><?php echo $controlSoumDropdown; ?></span>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-4">
    <?php echo form_label('Хороо/баг', 'Хороо/баг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <span id="address-street-html"><?php echo $controlStreetDropdown; ?></span>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>


<?php echo form_close(); ?>