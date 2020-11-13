<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-module-menu', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
?>

<div class="form-group">
    <?php echo form_label('Хамаарал', 'Хамаарал', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php echo $controlParentModuleMenuListDropdown; ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Модуль', 'Модуль', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php echo $controlModuleListDropdown; ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Тохиргоо', 'Тохиргоо', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isMainModule', 'name' => 'isMainModule', 'class' => 'radio', 'disabled' => true), 1, ($row->is_main_module == 1 ? TRUE : '')); ?>
                Үндсэн модуль меню </label>
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isMainModule', 'name' => 'isMainModule', 'class' => 'radio','disabled' => true), 0, ($row->is_main_module == 0 ? TRUE : '')); ?>
                Тохиргоо меню </label>
        </div>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $row->title,
            'class' => 'form-control',
            'required' => 'required',
            'readonly' => true
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Icon', 'Icon', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_input(array(
            'name' => 'icon',
            'id' => 'icon',
            'value' => $row->icon,
            'class' => 'form-control',
            'required' => 'required',
            'readonly' => true
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Class name', 'Class', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_input(array(
            'name' => 'menuClass',
            'id' => 'menuClass',
            'value' => $row->menu_class,
            'class' => 'form-control',
            'readonly' => true
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Css style', 'Css style', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_input(array(
            'name' => 'menuCss',
            'id' => 'menuCss',
            'value' => $row->menu_css,
            'class' => 'form-control',
            'readonly' => true
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_number(array(
            'name' => 'orderNum',
            'id' => 'orderNum',
            'value' => $row->order_num,
            'class' => 'form-control control-number',
            'required' => 'required',
            'readonly' => true
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>
