<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-research-accommodation', 'enctype' => 'multipart/form-data')); ?>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo form_label('Ирэх огноо', 'Ирэх огноо', array('required' => 'required', 'class' => 'control-label col-md-5 col-sm-5 text-right', 'defined' => TRUE)); ?>
                    <div class="col-md-7 col-sm-7">
                        <?php
                        echo form_input(array(
                            'name' => 'dateIn',
                            'id' => 'dateIn',
                            'placeholder' => 'Ирэх огноо',
                            'maxlength' => '50',
                            'class' => 'form-control',
                            'required' => 'required',
                            'readonly' => true
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo form_label('Буцах огноо', 'Буцах огноо', array('required' => 'required', 'class' => 'control-label col-md-5 col-sm-5 text-right', 'defined' => TRUE)); ?>
                    <div class="col-md-7 col-sm-7">
                        <?php
                        echo form_input(array(
                            'name' => 'dateOut',
                            'id' => 'dateOut',
                            'placeholder' => 'Буцах огноо',
                            'maxlength' => '50',
                            'class' => 'form-control',
                            'required' => 'required',
                            'readonly' => true
                        ));
                        ?>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    function setOrganizationId(organizationId) {
        $('input[name="organizationId"]', '#form-check-date').val(organizationId);
    }

</script>