<?php echo form_open(Scategory::$path . 'index/' . $modId, array('class' => 'form-horizontal', 'id' => 'form-category-search', 'enctype' => 'multipart/form-data', 'method'=>'get')); ?>
<style type="text/css">
    .form-group,
    .ui-dialog-content .form-group:last-child, .ui-dialog-content p:last-child {
        margin-bottom: 20px;
    }
</style>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label col-md-2 col-sm-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-10">
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
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(function () {

    });

</script>