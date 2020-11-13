<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-permission', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', 0);
echo form_hidden('catId', 0);
echo $moduleMenu;
echo form_close();
?>
