<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-urgudul-track', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_textarea(array(
    'name' => 'description',
    'id' => 'description',
    'value' => $row->description,
    'rows' => 4,
    'class' => 'form-control',
    'placeholder' => 'Өргөдөл шийдэгдэх явц хэрхэн үргэлжилж буй талаар бичнэ үү',
    'required' => true
));
echo form_close();