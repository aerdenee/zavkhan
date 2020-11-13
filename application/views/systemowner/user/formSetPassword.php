<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-user', 'enctype' => 'multipart/form-data'));
?>
<div class="form-group row">
    <?php echo form_label('Нууц үг', 'Нууц үг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_password(array(
            'name' => 'newPassword',
            'id' => 'newPassword',
            'maxlength' => '20',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Нууц үг (давтах)', 'Нууц үг (давтах)', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_password(array(
            'name' => 'confirmPassword',
            'id' => 'confirmPassword',
            'maxlength' => '20',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>
