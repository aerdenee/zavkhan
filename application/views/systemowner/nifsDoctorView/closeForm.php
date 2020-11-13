<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-close'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
?>
<div class="form-group row">
    <?php echo form_label('Актын дугаар', 'Актын дугаар', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">
        <?php
        echo form_input(array(
            'value' => $row->create_number,
            'class' => 'form-control _control-create-number',
            'required' => 'required',
            'readonly' => true
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Хаагдсан огноо', 'Хаагдсан огноо', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8">

        <?php
        echo form_input(array(
            'name' => 'closeDate',
            'id' => 'closeDate',
            'value' => ($row->close_date == '0000-00-00 00:00:00' ? date('Y-m-d') : date('Y-m-d', strtotime($row->close_date))),
            'maxlength' => '10',
            'class' => 'form-control init-date',
            'readonly' => true,
            'placeholder' => '____-__-__'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Гэмтлийн зэрэг', 'Гэмтлийн зэрэг', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
    <div class="col-md-8"> <?php echo $controlNifsCloseTypeDropdown; ?></div>
</div>


<?php
if (($row->short_value_id == '9' or $row->short_value_id == '3') AND $row->is_crime_ship == 1) {
    echo '<div class="form-group row">';
    echo '<label class="col-md-4 control-label text-right">Эр бэлгийн эс:</label>';
    echo '<div class="col-md-8">';
    echo '<div class="form-check form-check-inline">';
    echo '<label class="form-check-label">';
    echo form_radio(array('name' => 'isSperm', 'class' => 'radio'), 1, ($row->is_sperm == 1 ? TRUE : ''));
    echo ' Илэрсэн';
    echo '</label>';
    echo '</div>';
    echo '<div class="form-check form-check-inline">';
    echo '<label class="form-check-label">';
    echo form_radio(array('name' => 'isSperm', 'class' => 'radio'), 0, ($row->is_sperm == 0 ? TRUE : ''));
    echo ' Илрээгүй';
    echo '</label>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group row">';
    echo '<label class="col-md-4 control-label text-right">БЗХӨ:</label>';
    echo '<div class="col-md-8">';
    echo '<div class="form-check form-check-inline">';
    echo '<label class="form-check-label">';
    echo form_radio(array('name' => 'isBzhu', 'class' => 'radio'), 1, ($row->is_bzhu == 1 ? TRUE : ''));
    echo ' Илэрсэн';
    echo '</label>';
    echo '</div>';
    echo '<div class="form-check form-check-inline">';
    echo '<label class="form-check-label">';
    echo form_radio(array('name' => 'isBzhu', 'class' => 'radio'), 0, ($row->is_bzhu == 0 ? TRUE : ''));
    echo ' Илрээгүй';
    echo '</label>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group row">';
    echo '<label class="col-md-4 control-label text-right">Охин хальс:</label>';
    echo '<div class="col-md-8">';
    echo '<div class="form-check form-check-inline">';
    echo '<label class="form-check-label">';
    echo form_radio(array('name' => 'isSkin', 'class' => 'radio'), 1, ($row->is_skin == 1 ? TRUE : ''));
    echo ' Гэмтэлгүй';
    echo '</label>';
    echo '</div>';
    echo '<div class="form-check form-check-inline">';
    echo '<label class="form-check-label">';
    echo form_radio(array('name' => 'isSkin', 'class' => 'radio'), 2, ($row->is_skin == 2 ? TRUE : ''));
    echo ' Шинэ';
    echo '</label>';
    echo '</div>';
    echo '<div class="form-check form-check-inline">';
    echo '<label class="form-check-label">';
    echo form_radio(array('name' => 'isSkin', 'class' => 'radio'), 3, ($row->is_skin == 3 ? TRUE : ''));
    echo ' Хуучин';
    echo '</label>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group row">';
    echo '<label class="col-md-4 control-label text-right">Гэмтлийн зэрэг:</label>';
    echo '<div class="col-md-8">';
    echo $controlNifsInjuryDropdown;
    echo '</div>';
    echo '</div>';

} else {
    echo form_hidden('isSperm', 0);
    echo form_hidden('isBzhu', 0);
    echo form_hidden('isSkin', 0);
}
?>
<?php echo form_close(); ?>