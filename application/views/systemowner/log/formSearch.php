<?php
echo form_open('javscript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-log', 'enctype' => 'multipart/form-data', 'method' => 'get'));
?>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <div class="row">
                <div class="col-6">
                    <?php
                    echo form_label('Лог он', 'Лог он', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));
                    echo $controlLogDateYearDropdown;
                    ?>        
                </div>
                <div class="col-6">
                    <?php
                    echo form_label('Лог сар', 'Лог сар', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));
                    echo $controlLogDateMonthDropdown;
                    ?>        
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <div class="row">
                <div class="col-6">
                    <?php
                    echo form_label('Эхлэх өдөр', 'Эхлэх өдөр', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));
                    echo '<span id="beginDayHtml">' . $controlLogBeginDayDropdown . '</span>';
                    ?>        
                </div>
                <div class="col-6">
                    <?php
                    echo form_label('Дуусах өдөр', 'Дуусах өдөр', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));
                    echo '<span id="endDayHtml">' . $controlLogEndDayDropdown . '</span>';
                    ?>        
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <?php echo form_label('Үйлдэл', 'Үйлдэл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <select name="crudType" class="select2">
                <option value="" selected="selected"> - Сонгох - </option>
                <option value="create"> Нэмэх </option>
                <option value="update"> Засах </option>
                <option value="delete"> Устгах </option>
                <option value="close"> Хаах </option>
            </select>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <?php echo form_label('Хэрэглэгч', 'Хэрэглэгч', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
            <?php
            echo $controlUserDropDown;
            ?>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <?php
            echo form_label('IP хаяг', 'IP хаяг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE));
            echo form_input(array(
                'name' => 'ipAddress',
                'id' => 'ipAddress',
                'placeholder' => '127.0.0.1',
                'maxlength' => '15',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>

</div>

<?php echo form_close(); ?>