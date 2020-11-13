
<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-user', 'enctype' => 'multipart/form-data'));
?>
<fieldset>
    <div class="form-group">
        <?php echo form_label('Одоогийн нууц үг', 'Одоогийн нууц үг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-8">
            <?php
            echo form_password(array(
                'name' => 'currentPassword',
                'id' => 'currentPassword',
                'maxlength' => '20',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Шинэ нууц үг', 'Шинэ нууц үг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
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
    <div class="form-group">
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
</fieldset>
<?php echo form_close(); ?>
