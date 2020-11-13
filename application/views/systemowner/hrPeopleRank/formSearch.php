<?php
echo form_open('javascript:;', array('class' => 'form-horizontal col-md-12', 'id' => 'form-hr-people-rank', 'enctype' => 'multipart/form-data', 'method' => 'get'));
?>
<div class="row">
    <div class="col-md-12">
        <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
        <?php
        echo form_input(array(
            'name' => 'keyword',
            'id' => 'keyword',
            'placeholder' => 'Түлхүүр үгээ бичээд хайлт хийнэ',
            'maxlength' => '50',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>

<?php echo form_close(); ?>