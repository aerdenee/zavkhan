<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-motive', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('parentId', $row->parent_id);
?>

<div class="form-group row">
    <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
        <?php echo $controlCategoryListDropdown; ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $row->title,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-2">
        <?php
        echo form_input(array(
            'name' => 'orderNum',
            'id' => 'orderNum',
            'value' => $row->order_num,
            'maxlength' => '10',
            'class' => 'form-control integer',
            'required' => 'required'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'isActive'), 1, ($row->is_active == 1 ? TRUE : '')); ?>
                Нээх
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'isActive'), 0, ($row->is_active == 0 ? TRUE : '')); ?>
                Нээх
            </label>
        </div>
    </div>
</div>
<?php echo form_close(); ?>