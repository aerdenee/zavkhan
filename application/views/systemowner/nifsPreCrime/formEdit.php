<?php
echo form_open('javascript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-pre-crime', 'enctype' => 'multipart/form-data', 'method' => 'get'));
echo form_hidden('preCrimeKey', $row['key']);
?>

<div class="form-group row">
    <?php echo form_label('Өмнөх дүгнэлтийн дугаар', 'Өмнөх дүгнэлтийн дугаар', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'preCrimeCreateNumber',
            'id' => 'preCrimeCreateNumber',
            'value' => $row['create_number'],
            'class' => 'form-control control-journal-number',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Шинжээч', 'Шинжээч', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'preCrimeExpert',
            'id' => 'preCrimeExpert',
            'value' => $row['expert'],
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));

        //echo $controlHrPeopleMultiListDropdown;
        ?>
    </div>

</div>
<div class="form-group row">
    <?php echo form_label('Дүгнэлтийн утга', 'Дүгнэлтийн утга', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_textarea(array(
            'name' => 'preCrimeCrimeValue',
            'id' => 'preCrimeCrimeValue',
            'value' => $row['crime_value'],
            'rows' => 2,
            'class' => 'form-control'
        ));
        ?>    
    </div>

</div>

<?php echo form_close(); ?>