<?php
echo form_open('javascript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-pre-crime', 'enctype' => 'multipart/form-data', 'method' => 'get'));
?>

<div class="form-group row">
    <?php echo form_label('Өмнөх дүгнэлтийн дугаар', 'Өмнөх дүгнэлтийн дугаар', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'createNumber',
            'id' => 'createNumber',
            'value' => '',
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
            'name' => 'expert',
            'id' => 'expert',
            'value' => '',
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
            'name' => 'crimeValue',
            'id' => 'crimeValue',
            'value' => '',
            'rows' => 2,
            'class' => 'form-control'
        ));
        ?>    
    </div>

</div>

<?php echo form_close(); ?>