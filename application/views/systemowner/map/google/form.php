<?php
echo form_open('', array('class' => 'form-horizontal col-12', 'id' => 'form-map', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('contId', $row->cont_id);
echo form_hidden('catId', $row->cat_id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('mapTypeId', $row->map_type_id);
echo form_hidden('drawMode', $row->draw_mode);
?>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize&key=<?php echo DEFAULT_GOOGLE_MAP_APK_KEY; ?>"></script>

<div id="init-google-map" class="init-google-map" style="width: 100%; height: 400px; margin-bottom: 20px;"></div>
<div id="map-data" class="row">
    <div class="col-6">
        <div class="form-group row">
            <?php echo form_label('Уртраг/Longitude', 'Уртраг/Longitude', array('required' => 'required', 'class' => 'control-label col-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-8"><?php echo form_input(array('name' => 'lng', 'id' => 'lng', 'value' => $row->lng, 'class' => 'form-control _changeCoordinate', 'required' => 'required')); ?></div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group row">
            <?php echo form_label('Өргөрөг/Latitude', 'Өргөрөг/Latitude', array('required' => 'required', 'class' => 'control-label col-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-8"><?php echo form_input(array('name' => 'lat', 'id' => 'lat', 'value' => $row->lat, 'class' => 'form-control _changeCoordinate', 'required' => 'required')); ?></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="form-group row">
    <?php echo form_label('Төрөл', 'Төрөл', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-9">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'draw'), 'marker', ($row->draw_mode == 'marker' ? TRUE : ''), 'onclick="setDrawMode({_this:this});"'); ?>
                Marker
            </label>
        </div>

        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'draw'), 'circle', ($row->draw_mode == 'circle' ? TRUE : ''), 'onclick="setDrawMode({_this:this});"'); ?>
                Circle
            </label>
        </div>

        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'draw'), 'polygon', ($row->draw_mode == 'polygon' ? TRUE : ''), 'onclick="setDrawMode({_this:this});"'); ?>
                Polygon
            </label>
        </div>

        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'draw'), 'polyline', ($row->draw_mode == 'polyline' ? TRUE : ''), 'onclick="setDrawMode({_this:this});"'); ?>
                Polyline
            </label>
        </div>


        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('class' => 'radio', 'name' => 'draw'), 'rectangle', ($row->draw_mode == 'rectangle' ? TRUE : ''), 'onclick="setDrawMode({_this:this});"'); ?>
                Circle
            </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-9">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), 1, ($row->is_active == 1 ? TRUE : '')); ?>
                Нээх </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), 0, ($row->is_active == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
</div>

<div class="form-group row">
    <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
    <div class="col-9">
        <?php
        echo form_textarea(array(
            'name' => 'address',
            'id' => 'address',
            'value' => $row->address,
            'rows' => 4,
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(function () {
        $('._changeCoordinate').on('change', function () {
            $('#' + _formMapGoogleRootId).find('input[name="param"]').val('{"type":"coordinate","coordinates":{"lat":' + $('#map-data').find('input[name="lat"]').val() + ',"lng":' + $('#map-data').find('input[name="lng"]').val() + '},"style":{"image":""}}');
        });
    });
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
            var _this = $('#map-data');
            _this.find('input[name="lat"]').val(evt.latLng.lat());
            _this.find('input[name="lng"]').val(evt.latLng.lng());
            $('#' + _formMapGoogleRootId).find('input[name="param"]').val('{"type":"coordinate","coordinates":{"lat":' + evt.latLng.lat() + ',"lng":' + evt.latLng.lng() + '},"style":{"image":""}}');
        });

        _mapGoogle.setCenter(myMarker.position);
        myMarker.setMap(_mapGoogle);

    }
    function setDrawMode(param) {
        var _this = $(param._this);
        $('input[name="drawMode"]').val(_this.val())
    }
</script> 