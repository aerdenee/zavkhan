<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-close'));
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
    <?php echo form_label('Хаагдсан огноо', 'Хаагдсан огноо', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">

        <?php
        $closeDate = date('Y-m-d', strtotime($row->close_date));
        echo form_input(array(
            'name' => 'closeDate',
            'id' => 'closeDate',
            'value' => ($closeDate == '-0001-11-30' ? date('Y-m-d') : $closeDate),
            'maxlength' => '10',
            'class' => 'form-control init-date',
            'readonly' => true,
            'placeholder' => '____-__-__'
        ));
        ?>
    </div>
</div>
<!--<div class="form-group row">
    <?php echo form_label('Илэрсэн эсэх', 'Илэрсэн эсэх', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php echo $controlNifsSolutionDropdown; ?>
    </div>
</div>-->
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
<div class="form-group row">
    <?php echo form_label('Шийдсэн хэлбэр', 'Шийдсэн хэлбэр', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8"> <?php echo $controlNifsCloseTypeDropdown; ?></div>
</div>
<?php echo form_close(); ?>