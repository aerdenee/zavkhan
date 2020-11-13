<script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize&key=<?php echo DEFAULT_GOOGLE_MAP_APK_KEY; ?>"></script>

<div id="init-google-map" class="init-google-map"></div>
<div id="map-data" style="position: absolute; bottom: 0; z-index: 999; background-color: #c4ecb0; display: inline-block; padding: 10px;">
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Уртраг/Longitude', 'Уртраг/Longitude', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8"><?php echo form_input(array('name' => 'lng', 'id' => 'lng', 'value' => $param->coordinates->lng, 'class' => 'form-control _changeCoordinate', 'required' => 'required')); ?></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Өргөрөг/Latitude', 'Өргөрөг/Latitude', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8"><?php echo form_input(array('name' => 'lat', 'id' => 'lat', 'value' => $param->coordinates->lat, 'class' => 'form-control _changeCoordinate', 'required' => 'required')); ?></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>


<script type="text/javascript">
    $(function () {
        $('._changeCoordinate').on('change', function () {
            $('#' + _formMapGoogleRootId).find('input[name="param"]').val('{"type":"coordinate","coordinates":{"lat":' + $('#map-data').find('input[name="lat"]').val() + ',"lng":' + $('#map-data').find('input[name="lng"]').val() + '},"style":{"image":""}}');
        });
    });
    function initialize() {
        _mapGoogle = new google.maps.Map(document.getElementById('init-google-map'), {
            zoom: 15,
            center: new google.maps.LatLng(<?php echo $param->coordinates->lat; ?>, <?php echo $param->coordinates->lng; ?>),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var myMarker = new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $param->coordinates->lat; ?>, <?php echo $param->coordinates->lng; ?>),
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
</script> 
