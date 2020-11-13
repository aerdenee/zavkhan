<?php echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-layout', 'enctype' => 'multipart/form-data', 'method' => 'get')); ?>
<br>
<div class="form-group row">
    <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-10">
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
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(function () {

    });

</script>