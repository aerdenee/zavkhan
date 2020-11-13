<!DOCTYPE html>
<html lang="<?php echo $this->session->themeLanguage['code']; ?>">
    <head>
        <title><?php echo $page['pageTitle']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="<?php echo $page['metaKey']; ?>" />
        <meta name="description" content="<?php echo $page['metaDesc']; ?>">
        <meta content="" name="author"/>
        <link rel="shortcut icon" type="image/png" href="assets<?php echo DEFAULT_THEME; ?>img/favicon.svg"/>
        <base href="<?php echo base_url(); ?>">
        <meta property="og:locale" content="<?php echo $this->session->themeLanguage['code']; ?>_<?php echo strtoupper($this->session->themeLanguage['code']); ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?php echo $page['ogTitle']; ?>" />
        <meta property="og:description" content="<?php echo $page['ogDescription']; ?>" />
        <meta property="og:url" content="<?php echo $page['ogUrl']; ?>" />
        <meta property="og:image" content="<?php echo $page['ogImage']; ?>">
        <meta property="og:site_name" content="<?php echo base_url(); ?>" />

        <link href="assets/theme.min.css" rel="stylesheet" type="text/css">

        <?php
        if (isset($cssFile)) {
            foreach ($cssFile as $css) {
                echo '<link href="' . $css . '" rel="stylesheet" type="text/css" media="screen"/>' . "\n";
            }
        }
        ?>
        <script type="text/javascript" src="assets/theme.min.js"></script>
    </head>
    <body>

        <!-- Main navbar -->
        <header class="hidden-sm hidden-xs">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <a href="/" class="_theme-logo">
                            <img src="/assets/theme/zavkhan/img/logo.svg" alt="">
                        </a>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="title">
                                    <div class="titleLine"></div>Өнөөдөр
                                </div>
                                <div class="content">
                                    <?php echo date('Y/m/d');?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <a href="javascript:;">
                                    <div class="_theme-widget-header" id="currencyRate">
                                        <ul class="_theme-widget-vertical-ticker">
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>АНУ доллар												
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/system/icons/flag/usd.png">
                                                    2,758.4												
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>Евро												
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/system/icons/flag/eur.png">
                                                    2,986.7<i class="fa fa-caret-up"></i>												
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>ОХУ рубль												
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/system/icons/flag/rub.png">
                                                    43.3<i class="fa fa-caret-up"></i>												
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>БНХАУ юань												
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/system/icons/flag/cny.png">
                                                    394.0<i class="fa fa-caret-down"></i>												
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>БНСУ вон												
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/system/icons/flag/krw.png">
                                                    2.3<i class="fa fa-caret-down"></i>												
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </a>							
                            </div>
                            <div class="col-md-3">
                                <a href="javascript:;" id="weatherLink">
                                    <div class="_theme-widget-header" id="weather">
                                        <ul>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>Улаанбаатар
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/theme/zavkhan/img/weather_10.png">
                                                    -6℃
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>Дархан
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/theme/zavkhan/img/weather_10.png">
                                                    -9℃
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>Эрдэнэт
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/theme/zavkhan/img/weather_10.png">
                                                    -5℃
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>Замын-Үүд
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/theme/zavkhan/img/weather_10.png">
                                                    -2℃
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <div class="titleLine"></div>
                                                    Сүхбаатар
                                                </div>
                                                <div class="content">
                                                    <img src="/assets/theme/zavkhan/img/weather_10.png">
                                                    -9℃
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <div class="title">
                                    <div class="titleLine"></div>Шинэчилсэн
                                </div>
                                <div class="content">
                                    <?php echo $lastUpdateDate;?>
                                </div>
                            </div>
                        </div>
                    </div>					

                    <div class="col-md-3 text-right">
                        <div class="_theme-header-social">
                            <?php
                            $social = json_decode($contact->social);
                            foreach ($social as $key => $rowSocial) {
                                if ($rowSocial->show == 1) {
                                    echo '<a target="_blank" href="' . $rowSocial->url . '" class="' . $rowSocial->class . '"><i class="fa fa-' . $rowSocial->class . '"></i></a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
        </header>

        <!-- /main navbar -->

        <!-- Alternative navbar -->

        <div class="navbar navbar-expand-xl navbar-dark bg-teal navbar-sticky">
            <div class="container">
                <div class="d-xl-none">
                    <div class="navbar-brand">
                        <a href="/" class="d-inline-block">
                            <img src="/assets/theme/zavkhan/img/logo.svg" alt="">
                        </a>
                    </div>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-mixed">
                        <i class="icon-menu7"></i>
                    </button>
                </div>

                <div class="navbar-collapse collapse" id="navbar-mixed">
                    <?php echo $mainMenu; ?>

                </div>
            </div>
        </div>

        <!-- /alternative navbar -->

        