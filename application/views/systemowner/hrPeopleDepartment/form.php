<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-hr-people-department', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('catId', $row->cat_id);
echo form_hidden('lat', $row->lat);
echo form_hidden('lng', $row->lng);
?>
<div class="form-group row">
    <?php echo form_label('Хамаарал', 'Хамаарал', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">

        <?php echo $controlHrPeopleDepartmentParentMultiRowDropdown; ?>

    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Салбар, хэлтэс', 'Салбар, хэлтэс', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $row->title,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Товч нэр', 'Товч нэр', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'shortTitle',
            'id' => 'shortTitle',
            'value' => $row->short_title,
            'maxlength' => '500',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'address',
            'id' => 'address',
            'value' => $row->address,
            'maxlength' => '500',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <?php
        echo form_input(array(
            'name' => 'phone',
            'id' => 'phone',
            'value' => $row->phone,
            'maxlength' => '500',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>

<div class="form-group row">
    <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-2">
        <?php
        echo form_input(array(
            'name' => 'orderNum',
            'id' => 'orderNum',
            'value' => $row->order_num,
            'maxlength' => '10',
            'class' => 'form-control text-right',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Контрол', 'Контрол', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'isActiveControl'), 1, ($row->is_active_control == 1 ? TRUE : '')); ?>
                Нээх
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'isActiveControl'), 0, ($row->is_active_control == 0 ? TRUE : '')); ?>
                Нээх
            </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-md-10">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'isActive'), 1, ($row->is_active == 1 ? TRUE : '')); ?>
                Нээх
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'isActive'), 0, ($row->is_active == 0 ? TRUE : '')); ?>
                Нээх
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12">
        <script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize&key=<?php echo DEFAULT_GOOGLE_MAP_APK_KEY; ?>"></script>

        <div id="init-google-map" class="init-google-map" style="width: 100%; height: 400px;"></div>
    </div>
</div>

<?php echo form_close(); ?>
<script type="text/javascript">
    function initialize() {
        _mapGoogle = new google.maps.Map(document.getElementById('init-google-map'), {
            zoom: 15,
            center: new google.maps.LatLng(<?php echo $row->lat; ?>, <?php echo $row->lng; ?>),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var myMarker = new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $row->lat; ?>, <?php echo $row->lng; ?>),
            draggable: true
        });

        google.maps.event.addListener(myMarker, 'dragend', function (evt) {
            console.log(evt.latLng.lat());
            $('input[name="lat"]').val(evt.latLng.lat());
            $('input[name="lng"]').val(evt.latLng.lng());
        });

        _mapGoogle.setCenter(myMarker.position);
        myMarker.setMap(_mapGoogle);

    }
</script> 