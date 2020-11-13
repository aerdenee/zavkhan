<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-tour-calendar', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('contId', $row->cont_id);
echo form_hidden('modId', $row->mod_id);
?>
<div class="clearfix margin-top-20"></div>
<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-7">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('name' => 'isActive', 'class' => 'radio'), 1, ($row->is_active == 1 ? TRUE : FALSE)); ?>
                Нээх </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('name' => 'isActive', 'class' => 'radio'), 0, ($row->is_active == 0 ? TRUE : FALSE)); ?>
                Хаах </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Эхлэх, дуусах огноо', 'Эхлэх, дуусах огноо', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <div style="display: inline-block; margin-right: 20px;">
            <?php
            echo form_input(array(
                'name' => 'inDate',
                'id' => 'inDate',
                'value' => $row->in_date,
                'maxlength' => '10',
                'class' => 'form-control init-date',
                'required' => 'required',
                'readonly' => true
            ));
            ?>
        </div>
        <div style="display: inline-block; margin-right: 20px;">
            <?php
            echo form_input(array(
                'name' => 'outDate',
                'id' => 'outDate',
                'value' => $row->out_date,
                'maxlength' => '10',
                'class' => 'form-control init-date',
                'required' => 'required',
                'readonly' => true
            ));
            ?>
        </div>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Аялалын үнэ', 'Аялалын үнэ', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-2">
        <?php
        echo form_input(array(
            'name' => 'price',
            'id' => 'price',
            'value' => $row->price,
            'maxlength' => '10',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12">
        <?php
        echo form_textarea(array(
            'name' => 'introText',
            'id' => 'introText',
            'value' => $row->intro_text,
            'rows' => 4,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>