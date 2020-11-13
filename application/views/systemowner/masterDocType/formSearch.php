<?php echo form_open('javascript:;', array('class' => 'form-horizontal col-md-12', 'id' => 'form-master-doc-type-search', 'enctype' => 'multipart/form-data', 'method' => 'get')); ?>
<div class="form-group" style="margin: 0;">
    <?php //echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label col-md-2 col-sm-2 text-right', 'defined' => TRUE)); ?>

    <?php
    echo form_input(array(
        'name' => 'keyword',
        'id' => 'keyword',
        'placeholder' => 'Түлхүүр үг',
        'maxlength' => '50',
        'class' => 'form-control',
        'required' => 'required'
    ));
    ?>

</div>
<?php echo form_close(); ?>