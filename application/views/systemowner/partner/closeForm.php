<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-close')); 
echo form_hidden('id', $row->id);
?>
<div class="form-group">
    <?php echo form_label('Шинжилгээний дугаар', 'Шинжилгээний дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_input(array(
            'value' => $row->create_number,
            'class' => 'form-control control-number',
            'required' => 'required',
            'readonly' => true
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Хаагдсан огноо', 'Хаагдсан огноо', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">

            <?php
            echo form_input(array(
                'name' => 'closeDate',
                'id' => 'closeDate',
                'value' => ($row->close_date == '0000-00-00 00:00:00' ? date('Y-m-d') : date('Y-m-d', strtotime($row->close_date))),
                'maxlength' => '10',
                'class' => 'form-control init-date',
                'required' => 'required'
            ));
            ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Ачаалал', 'Ачаалал', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_number(array(
            'name' => 'weight',
            'id' => 'weight',
            'value' => $row->weight,
            'maxlength' => '10',
            'class' => 'form-control control-number',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Дүгнэлт', 'Дүгнэлт', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_textarea(array(
            'name' => 'closeDescription',
            'id' => 'closeDescription',
            'value' => $row->close_description,
            'rows' => 5,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Шийдвэр', 'Шийдвэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php echo $controlSolutionDropDown; ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Хаах төрөл', 'Хаах төрөл', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8"> <?php echo $controlCloseTypeDropDown; ?></div>
</div>

<?php echo form_close(); ?>