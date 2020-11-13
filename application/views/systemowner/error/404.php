<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->

    <!-- Head BEGIN -->
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>404 page</title>
        <link rel="shortcut icon" href="/assets/img/favicon.ico">

        <!-- Bootstrap core CSS -->
        <link href="/theme/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/theme/css/bootstrap.css" rel="stylesheet">
        <link href="/theme/fonts/opensans/opensans.css" rel="stylesheet">
        <link href="/theme/fonts/roboto/roboto.css" rel="stylesheet">
        <link href="/theme/css/theme.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <?php
        if (isset($cssFile)) {
            foreach ($cssFile as $css) {
                echo '<link href="' . $css . '" rel="stylesheet" type="text/css" media="screen"/>' . "\n";
            }
        }
        ?>
        <script src="/theme/js/jquery.min.js"></script>
        <script src="/theme/js/jquery.blockui.min.js"></script>
        <script src="/theme/js/bootstrap.min.js"></script>
        <script src="/theme/js/custom.js"></script>
        <?php
        if (isset($jsFile)) {
            foreach ($jsFile as $js) {
                echo '<script src="' . $js . '" type="text/javascript"></script>' . "\n";
            }
        }
        ?>
    </head>
    <!-- Head END -->

    <!-- Body BEGIN -->
    <body class="page-404-full-page">
        <div class="row">
            <div class="col-md-12 page-404">
                <div class="number">
                    404
                </div>
                <div class="details">
                    <h3>Хуудас олдсонгүй</h3>
                    <p>
                        Эхлэл хуудас руу буцах бол<br>
                        <a href="<?php echo ($this->session->userLang==1 ? '/': '/en');?>"> энд дарна уу</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>