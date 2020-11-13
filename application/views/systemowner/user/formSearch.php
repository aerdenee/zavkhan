<?php
echo form_open('javascript:;', array('class' => 'form-vertical col-md-12', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get'));
?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Овог', 'Овог', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo form_input(array(
                'name' => 'lname',
                'id' => 'lname',
                'placeholder' => 'Овог...',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Нэр', 'Нэр', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo form_input(array(
                'name' => 'fname',
                'id' => 'fname',
                'placeholder' => 'Нэр...',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo form_input(array(
                'name' => 'phone',
                'id' => 'phone',
                'placeholder' => 'Утас...',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Мэйл', 'Мэйл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo form_input(array(
                'name' => 'email',
                'id' => 'email',
                'placeholder' => 'Мэйл...',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Газар, хэлтэс', 'Газар, хэлтэс', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo $controlHrPeopleDepartmentDropdown;
            ?>
        </div>
    </div>
    
</div>
<?php echo form_close(); ?>