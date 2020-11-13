<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-media-video-photo', 'enctype' => 'multipart/form-data'));
echo form_hidden('pollId', $pollId);
echo form_hidden('modId', $modId);
echo form_hidden('id', $row->id);
echo form_hidden('typeId', $row->type_id);
?>
<div style="padding: 10px; display: block;"></div>
<div class="form-group">
    <?php echo form_label('Хариултын төрөл', 'Хариултын төрөл', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('name' => 'raiodType', 'class' => 'radio'), 1, ($row->type_id == 1 ? TRUE : '')); ?>
                Нэг сонголттой </label>
            <label class="radio-inline">
                <?php echo form_radio(array('name' => 'raiodType', 'class' => 'radio'), 2, ($row->type_id == 2 ? TRUE : '')); ?>
                Олон сонголттой </label>
            <label class="radio-inline">
                <?php echo form_radio(array('name' => 'raiodType', 'class' => 'radio'), 3, ($row->type_id == 3 ? TRUE : '')); ?>
                Текст </label>
        </div>
    </div>
</div>
<?php if ($row->id != ''): ?>
    <div class="clearfix"></div>
    <div class="form-group">
        <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'orderNum',
                'id' => 'orderNum',
                'value' => $row->order_num,
                'maxlength' => '4',
                'class' => 'form-control integer order-num',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
<?php endif; ?>
<div class="form-group">
    <?php echo form_label('Нийтлэх /Монгол/', 'Нийтлэх /Монгол/', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), 1, ($row->is_active_mn == 1 ? TRUE : '')); ?>
                Нээх </label>
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), 0, ($row->is_active_mn == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Хариулт Монгол', 'Хариулт Монгол', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'titleMn',
            'id' => 'titleMn',
            'value' => $row->title_mn,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Нийтлэх /English/', 'Нийтлэх /English/', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), 1, ($row->is_active_en == 1 ? TRUE : '')); ?>
                Нээх </label>
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), 0, ($row->is_active_en == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Хариулт English', 'Хариулт English', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'titleEn',
            'id' => 'titleEn',
            'value' => $row->title_en,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Хариу', 'Хариу', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            'name' => 'result',
            'id' => 'result',
            'value' => $row->result,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(function () {
        $('input[name="raiodType"]', '#form-media-video-photo').on('click', function (event) {
            $("input[name='type_id']", '#form-media-video-photo').val($(this).val());
        });
    });
</script>