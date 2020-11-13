<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-partner', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabContentMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabContentEnglish" data-toggle="tab">English</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabContentMongolia">

            <div class="form-group">
                <?php echo form_label('Модуль', 'Модуль', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-md-10">
                    <?php echo $controlModuleListDropdown;?>
                </div>
            </div>
            
            <div class="form-group">
                <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'titleMn',
                        'id' => 'titleMn',
                        'value' => $row->title_mn,
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required'
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
                        'value' => $row->order_num,
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
                            <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), '1', ($row->is_active_mn == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), '0', ($row->is_active_mn == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="tabContentEnglish">
            <div class="form-group">
                <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'titleEn',
                        'id' => 'titleEn',
                        'value' => $row->title_en,
                        'maxlength' => '500',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), '1', ($row->is_active_en == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), '0', ($row->is_active_en == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>