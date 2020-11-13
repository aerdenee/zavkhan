<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4">

                <div class="widget clearfix">
                    <p><?php echo $contact->address . ' ' . $contact->post_address; ?></p><br>
                    <p>
                        Холбоо барих: <br> И-мэйл хаяг: <?php echo $contact->email; ?>  <br> Утас: <?php echo $contact->mobile; ?>
                    </p>
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="widget clearfix">

                    <p>Сурталчилгаа байршуулах : </p>
                    <p> <?php echo $contactMarket->title . ': ' . $contactMarket->mobile; ?></p>
                    <p> <?php echo $contactMarket->email; ?></p>

                    <?php echo $salesMenu; ?>
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="widget clearfix">
                    <?php echo $footerMenu; ?>
                </div>
            </div>
            <div class="col-md-2 col-xs-12">
                <div class="widget clearfix">
                    <?php
                    $social = json_decode($contact->social);
                    echo '<ul class="list-unstyled">';
                    foreach ($social as $key => $rowSocial) {
                        if ($rowSocial->show == 1) {
                            echo '<li><a target="_blank" href="' . $rowSocial->url . '">' . $rowSocial->label . '</a></li>';
                        }
                    }
                    echo '</ul>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="_copyrights">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-6">
                Copyrights &COPY; 2010-<?php echo date('Y'); ?>, БҮХ ЭРХ ХУУЛИАР ХАМГААЛАГДСАН.
            </div>
            <div class="col-md-6">
                <div class="pull-right pb-2" align="right">
                    <div class="gerege-agency">
                        <div class="gerege-info">
                            Gazelle системийг ашиглан бүтээв
                        </div>

                    </div>

                </div>
            </div>            
        </div>
    </div>
</div>

<?php
if (isset($jsFile)) {
    foreach ($jsFile as $js) {
        echo '<script src="' . $js . '" type="text/javascript"></script>' . "\n";
    }
}
?>
</body>

</html>