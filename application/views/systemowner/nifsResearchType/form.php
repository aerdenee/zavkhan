<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-nifs-research-type', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>
<div class="form-group">
    <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php echo $controlCategoryListDropdown;?>
    </div>
</div>


<div class="form-group">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
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

<div class="form-group">
    <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
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
<div class="form-group">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), 1, ($row->is_active == 1 ? TRUE : FALSE)); ?>
                Нээх </label>
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), 0, ($row->is_active == 0 ? TRUE : FALSE)); ?>
                Хаах </label>
        </div>
    </div>
</div>
<?php echo form_close(); ?>