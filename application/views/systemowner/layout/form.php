<?php
echo form_open('', array('class' => 'form-horizontal col-12', 'id' => 'form-layout', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('catId', 0);
echo form_hidden('modId', $row->mod_id);
?>

<div class="form-group row">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $row->title,
            'maxlength' => 500,
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Layout', 'Layout', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">        
        <?php
        echo form_input(array(
            'name' => 'layout',
            'id' => 'layout',
            'value' => $row->layout,
            'maxlength' => '500',
            'class' => 'form-control'
        ));
        ?>
        <span class="help-block">Мэдээний дугаарыг таслалаар тусгаарлан цувруулан бичнэ. Жнь: 5,9,20,30 гэх мэт</span>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), 1, (intval($row->is_active) == 1 ? TRUE : '')); ?>
                Нээх </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), 0, (intval($row->is_active) == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
<?php echo form_close(); ?>