<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-class', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $modId);
echo form_hidden('orderNum', $row->order_num);

if (!$this->input->is_ajax_request()) {
    echo '<div class="panel panel-flat">';
    echo '<div class="panel-heading">';
    echo '<h5 class="panel-title">' . $module->title . '</h5>';
    echo '<div class="heading-elements">';
    echo '<ul class="icons-list">';
    echo '<li><a href="' . Sclass::$path . 'index/' . $modId . '"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>';
    echo '</ul>';
    echo '</div>';
    echo '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
    echo '</div>';
    echo '<div class="panel-body">';
}
?>
<br>
<div class="col-md-7">
    <div class="form-group">
        <?php echo form_label('Сургалт', 'Сургалт', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-10">
            <?php
            echo $controlCategoryListDropdown;
            ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Хугацаа', 'Хугацаа', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                'name' => 'startDate',
                'id' => 'startDate',
                'value' => $row->start_date,
                'maxlength' => '10',
                'class' => 'form-control init-date pull-left',
                'required' => 'required'
            ));

            echo form_input(array(
                'name' => 'endDate',
                'id' => 'endDate',
                'value' => $row->end_date,
                'maxlength' => '10',
                'class' => 'form-control init-date pull-left',
                'required' => 'required',
                'style' => 'margin-left:10px'
            ));
            ?>
            <div class="clearfix"></div>
            <span class="help-block offset3" style="margin: 0 !important;">Сургалт эхлэх, дуусах огноо</span>        
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Төлбөр', 'Төлбөр', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                'name' => 'price',
                'id' => 'price',
                'value' => $row->price,
                'maxlength' => '10',
                'class' => 'form-control pull-left',
                'required' => 'required',
                'style' => 'text-align:right; width:150px; margin-right:10px;'
            ));
            ?>
            <span class="pull-left control-label">төгрөг</span>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Төлөв', 'Төлөв', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-10">
            <div class="radio-list">
                <label class="radio-inline">
                    <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '1', (intval($row->is_active) == 1 ? TRUE : '')); ?>
                    Нээх </label>
                <label class="radio-inline">
                    <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '0', (intval($row->is_active) == 0 ? TRUE : '')); ?>
                    Хаах </label>
            </div>
        </div>
    </div>
</div>

<div class="col-md-5">
    <div class="form-group">
        <?php echo form_label('Багш', 'Багш', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo $controlAuthorDropdown;
            ?>
        </div>
    </div>
    <?php echo form_label('Нэмэлт тайлбар бичнэ', 'Нэмэлт тайлбар бичнэ', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE)); ?>
    <?php
    echo form_textarea(array(
        'name' => 'introText',
        'id' => 'introText',
        'value' => $row->intro_text,
        'rows' => 5,
        'class' => 'form-control',
        'placeholder' => 'Нэмэлт тайлбар бичнэ'
    ));
    ?>    
</div>


<?php
if (!$this->input->is_ajax_request()) {
    echo '<div class="form-group">';
    echo form_label(' ', ' ', array('class' => 'control-label col-lg-2 text-right', 'defined' => FALSE));
    echo '<div class="col-lg-10 text-left">';
    echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({modId:' . $modId . ', mode:\'' . $mode . '\'});"', 'button');
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

echo form_close();


