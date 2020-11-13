<div id="googleMap" style="width:100%;height:400px;"></div>
<?php
$centerLat = $centerLng = 0;
$latLngNumber = 0;
if ($map) {

    foreach ($map as $rowMap) {

        $latLngNumber++;
        $centerLat = $centerLat + $rowMap->lat;
        $centerLng = $centerLng + $rowMap->lng;
    }

    $centerLat = $centerLat / $latLngNumber;
    $centerLng = $centerLng / $latLngNumber;
}
?>
<script>
    function myMap() {
        var mapProp = {
            center: new google.maps.LatLng(<?php echo $centerLat; ?>, <?php echo $centerLng; ?>),
            zoom: 18,
        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        var myMarker = new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $centerLat; ?>, <?php echo $centerLng; ?>),
            animation: google.maps.Animation.DROP,
            icon: '/assets<?php echo DEFAULT_THEME; ?>img/marker.png'
        });

        myMarker.setMap(map);
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo DEFAULT_GOOGLE_MAP_APK_KEY; ?>&callback=myMap"></script>
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-6">

                <ul class="contact-icon">
                    <li class="contact-icon-address"><span><?php echo $row->address; ?></span></li>
                    <?php echo ($row->show_email == 1 ? '<li class="contact-icon-email">' . $row->email . '</li>' : ''); ?>
                </ul>

            </div>

            <div class="col-md-6">
                <ul class="contact-icon">
                    <?php echo ($row->show_mobile == 1 ? '<li class="contact-icon-mobile">' . $row->mobile . '</li>' : ''); ?>
                    <?php echo ($row->show_phone == 1 ? '<li class="contact-icon-phone">' . $row->phone . '</li>' : ''); ?>
                    <?php echo ($row->show_fax == 1 ? '<li class="contact-icon-fax">' . $row->fax . '</li>' : ''); ?>
                    <?php echo ($row->show_email == 1 ? '<li class="contact-icon-email">' . $row->email . '</li>' : ''); ?>
                </ul>
            </div>
        </div>
    </div>
</section>

