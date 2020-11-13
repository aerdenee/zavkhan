<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-grouptour', 'enctype' => 'multipart/form-data')); ?>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo $module->title; ?></h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="<?php echo Sgrouptour::$path . 'index/' . $modId; ?>"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>
            </ul>
        </div>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

    <div class="panel-body">
        <?php
        echo form_hidden('id', $row['id']);
        echo form_hidden('modId', $modId);
        ?>
        <div class="form-group">
            <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-lg-6">
                <?php echo $controlCategoryListDropdown;?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Аялал', 'Аялал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-lg-6">
                <?php echo $controlGroupTourListDropdown;?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Нэг удаагийн аялалд хүний тоо', 'Нэг удаагийн аялалд хүний тоо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-lg-6">
                <?php echo $controlGroupSizeListDropdown;?>
            </div>
        </div>
        
        <div class="form-group">
            <?php echo form_label('Аялал эхлэх хугацаа', 'Аялал эхлэх хугацаа', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-lg-6">
                    <?php
                        echo form_input(array(
                            'name' => 'groupDate',
                            'id' => 'groupDate',
                            'value' => $row['group_date'],
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true
                        ));
                        ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-lg-2">
                <?php
                echo form_input(array(
                    'name' => 'orderNum',
                    'id' => 'orderNum',
                    'value' => $row['order_num'],
                    'maxlength' => '10',
                    'class' => 'form-control integer',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-lg-5">
                <div class="radio-list">
                    <label class="radio-inline">
                        <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '1', (intval($row['is_active']) == 1 ? TRUE : '')); ?>
                        Нээх </label>
                    <label class="radio-inline">
                        <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '0', (intval($row['is_active']) == 0 ? TRUE : '')); ?>
                        Хаах </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-lg-10">
                <?php
                echo form_textarea(array(
                    'placeholder' => 'Тайлбар бичих',
                    'name' => 'introText',
                    'id' => 'introText',
                    'value' => $row['intro_text'],
                    'size' => '50',
                    'rows' => 5,
                    'class' => 'form-control ckeditor'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label(' ', ' ', array('class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
            <div class="col-lg-10 text-left">
                <?php
                echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveFormGroupTour({modId:' . $modId . ', mode:\'' . $mode . '\'});"', 'button');
                ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>



