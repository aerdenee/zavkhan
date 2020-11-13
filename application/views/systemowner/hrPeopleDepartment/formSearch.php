<?php
echo form_open(ShrPeopleDepartment::$path . 'index/' . $row->mod_id, array('class' => 'form-horizontal', 'id' => 'form-hr-people-department', 'enctype' => 'multipart/form-data', 'method' => 'get'));
?>
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


<?php echo form_close(); ?>