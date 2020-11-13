<?php echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get')); ?>

<div class="form-group row">
    <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php echo $controlCategoryListDropdown; ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php echo $controlPartnerDropdown; ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'col-md-2 col-form-label text-md-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
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
<?php echo form_close(); ?>