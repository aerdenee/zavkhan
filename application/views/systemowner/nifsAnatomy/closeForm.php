<?php echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-close')); 
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>
<div class="form-group row">
    <?php echo form_label('Актын дугаар', 'Актын дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_input(array(
            'value' => $row->create_number,
            'class' => 'form-control _control-create-number',
            'required' => 'required',
            'readonly' => true
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Хийгдсэн огноо', 'Хийгдсэн огноо', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">

            <?php
            echo form_input(array(
                'name' => 'beginDate',
                'id' => 'beginDate',
                'value' => ($row->begin_date == '0000-00-00 00:00:00' ? date('Y-m-d') : date('Y-m-d', strtotime($row->begin_date))),
                'maxlength' => '10',
                'class' => 'form-control init-date',
                'readonly' => true,
                'placeholder' => '____-__-__'
            ));
            ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Дууссан огноо', 'Дууссан огноо', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">

            <?php
            echo form_input(array(
                'name' => 'endDate',
                'id' => 'endDate',
                'value' => ($row->end_date == '0000-00-00 00:00:00' ? date('Y-m-d') : date('Y-m-d', strtotime($row->end_date))),
                'maxlength' => '10',
                'class' => 'form-control init-date',
                'readonly' => true,
                'placeholder' => '____-__-__'
            ));
            ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Шалтгаан', 'Шалтгаан', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php echo $controlNifsSolutionDropdown; ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Спирт илэрсэн эсэх', 'Спирт илэрсэн эсэх', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php echo $controlNifsCloseTypeDropdown; ?>
    </div>
</div>

<div class="form-group row">
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

<?php echo form_close(); ?>