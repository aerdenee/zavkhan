<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-student-finance', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row['id']);
echo form_hidden('modId', $row['mod_id']);
echo form_hidden('catId', $row['cat_id']);
echo form_hidden('isActive', $row['is_active']);
echo form_hidden('studentId', $studentId);
echo form_hidden('classId', $row['class_id']);
echo form_hidden('orderNum', $row['order_num']);
?>

<div class="row pt-10">
    <div class="col-md-12">
        <div class="form-group">
            <?php
            echo form_label('Анги', 'Анги', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE));
            echo '<div class="col-md-9">' . $controlClassListDropdown . '<span class="help-block" style="margin:0 !important;"><div id="class-price"></div><div id="student-income"></div><div id="student-outlet"></div></span></div>';
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_label('Төлөх дүн', 'Төлөх дүн', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE));
            echo '<div class="col-md-9">';
            echo form_input(array(
                'name' => 'income',
                'id' => 'income',
                'value' => $row['income'],
                'maxlength' => '500',
                'class' => 'form-control pull-left',
                'style' => 'text-align:right; width:150px; margin-right:10px;'
            ));
            echo '<span class="pull-left control-label">төгрөг</span>';
            echo '</div>';
            ?>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group">
            <?php
            echo form_label('Буцаах дүн', 'Буцаах дүн', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE));
            echo '<div class="col-md-9">';
            echo form_input(array(
                'name' => 'outlet',
                'id' => 'outlet',
                'value' => $row['outlet'],
                'maxlength' => '500',
                'class' => 'form-control pull-left',
                'style' => 'text-align:right; width:150px; margin-right:10px;'
            ));
            echo '<span class="pull-left control-label">төгрөг</span>';
            echo '</div>';
            ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
