<?php
echo form_open('', array('class' => 'form-vertical', 'id' => 'form-student-class', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row['id']);
echo form_hidden('modId', $row['mod_id']);
echo form_hidden('studentId', $studentId);
echo form_hidden('orderNum', $row['order_num']);
?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?php
            echo form_label('Багш', 'Багш', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
            echo $controlAuthorDropdown;
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_label('Төлөв', 'Төлөв', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
            echo $controlStatusListDropdown;
            ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php
            echo form_label('Анги', 'Анги', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE));
            echo $controlClassListDropdown;
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_label('&nbsp;', '&nbsp;', array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE));
            ?>
            <div class="form-group" style="min-height:20px;">
                <span id="class-price"></span>
            </div>
        </div>        
    </div>
</div>
