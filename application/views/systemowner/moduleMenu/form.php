<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-module-menu', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
?>

<div class="form-group row">
    <?php echo form_label('Хамаарал', 'Хамаарал', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php echo $controlParentModuleMenuListDropdown; ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Модуль', 'Модуль', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php echo $controlModuleListDropdown; ?>
        <span class="help-block"><i class="icon-help"></i> Үүсгэж буй меню бүр заавал модуль сонгосон байх ёстой.</span>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Тохиргоо', 'Тохиргоо', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php echo $controlModuleMenuTypeRadioButton; ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $row->title,
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Icon', 'Icon', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'icon',
            'id' => 'icon',
            'value' => $row->icon,
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Class name', 'Class', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'menuClass',
            'id' => 'menuClass',
            'value' => $row->menu_class,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Css style', 'Css style', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'menuCss',
            'id' => 'menuCss',
            'value' => $row->menu_css,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Багана', 'Багана', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'columnCount',
            'id' => 'columnCount',
            'value' => $row->column_count,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>

<div class="form-group <?php echo ($row->menu_type_id == 5 ? '' : 'moduleMenuLocalContent');?> row">
    
    <?php echo form_label('Ангилал', 'Ангилал', array('class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <span id="moduleMenuCategory"><?php echo $controlCategoryDropdown;?></span>
    </div>
</div>
<div class="form-group <?php echo ($row->menu_type_id == 5 ? '' : 'moduleMenuLocalContent');?> row">
    <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <span id="moduleMenuContent"><?php echo $controlContentDropdown;?></span>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'col-md-3 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'orderNum',
            'id' => 'orderNum',
            'value' => $row->order_num,
            'class' => 'form-control control-number',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>