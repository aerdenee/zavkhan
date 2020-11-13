<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $pageMeta->pageTitle; ?></title>
        <base href="<?php echo base_url(); ?>">
        <meta name="keywords" content="<?php echo $pageMeta->keywords; ?>"/>
        <meta name="description" content="<?php echo $pageMeta->description; ?>"/>
        <meta name="author" content="<?php echo $pageMeta->author; ?>"/>
        <link rel="shortcut icon" type="image/png" href="<?php echo $pageMeta->favicon; ?>"/>
        <meta property="og:title" content="<?php echo $pageMeta->contentTitle; ?>" />
        <meta property="og:description" content="<?php echo $pageMeta->description; ?>" />
        <meta property="og:url" content="<?php echo current_url(); ?>" />
        <meta property="og:image" content="<?php echo $pageMeta->contentImage; ?>">
        <meta property="og:site_name" content="<?php echo $pageMeta->author; ?>" />

        <link href="assets/global.min.<?php echo CSS_JS_VERSION; ?>.css" rel="stylesheet" type="text/css">
        <?php
        if (isset($cssFile)) {
            foreach ($cssFile as $css) {
                echo '<link href="' . $css . '?v=' . CSS_JS_VERSION . '" rel="stylesheet" type="text/css" media="screen"/>' . "\n";
            }
        }
        ?>
        <script type="text/javascript" src="assets/global.min.<?php echo CSS_JS_VERSION; ?>.js"></script>
        <?php
        echo '<script type="text/javascript">';
        echo 'var _globalDatePickerNextMonth = "Дараагийн сар";';
        echo 'var _globalDatePickerPrevMonth = "Өмнөх сар";';
        echo 'var _globalDatePickerChooseMonth = "Сар сонгох";';
        echo 'var _globalDatePickerChooseYear = "Жил сонгох";';
        echo 'var _globalDatePickerListMonth = [\'1 сар\', \'2 сар\', \'3 сар\', \'4 сар\', \'5 сар\', \'6 сар\', \'7 сар\', \'8 сар\', \'9 сар\', \'10 сар\', \'11 сар\', \'12 сар\'];';
        echo 'var _globalDatePickerListWeekDayShort = [\'Да\', \'Мя\', \'Лх\', \'Пү\', \'Ба\', \'Бя\', \'Ня\'];';
        echo 'var _globalDatePickerChooseToday = "Өнөөдөр";';
        echo 'var _globalDatePickerChooseClear = "Цэвэрлэх";';
        echo 'var _globalDatePickerChooseClose = "Хаах";';
        echo 'var _globalPermission = ' . json_encode($this->session->authentication) . ';';
        echo 'var _uIdCurrent = ' . $this->session->adminUserId . ';';
        echo 'var _peopleId = ' . $this->session->adminPeopleId . ';';
        echo 'var _departmentId = ' . $this->session->adminDepartmentId . ';';
        echo '</script>';

//        if (isset($jsFile)) {
//            foreach ($jsFile as $js) {
//                echo '<script src="' . $js . '?v=' . CSS_JS_VERSION . '" type="text/javascript" async defer></script>' . "\n";
//            }
//        }
        if (!IS_DEFAULT_SYSTEM_USER) {
        echo '<style type="text/css">
            #root-container {
                background-image: url(/assets/system/img/backgrounds/background-logo.png);
            }
        </style>';    
        }
        ?>
        
    </head>

    <body class="sidebar-xs">

        <div class="navbar navbar-expand-md navbar-dark">
            <div class="navbar-brand wmin-0 mr-5">
                <a href="dashboard" class="d-inline-block">
                    <img src="<?php echo (IS_DEFAULT_SYSTEM_USER ? 'assets/system/img/logo.svg' : 'assets/theme/forensics/img/logo.svg'); ?>" alt="">
                </a>
            </div>

            <div class="d-md-none">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                    <i class="icon-tree5"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbar-mobile">
                <?php 
                if (!IS_DEFAULT_SYSTEM_USER) {
                ?>
                <ul class="navbar-nav">

                    <li class="nav-item dropdown">

                        <form action="sknowledgebase/searchList/7" method="get" class="navbar-nav-search">
                            <div class="form-group-feedback form-group-feedback-right">
                                <input type="search" name="keyword" class="form-control wmin-md-200" placeholder="Програм ашиглах заавар хайх...">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 font-size-sm text-muted"></i>
                                </div>
                            </div>
                        </form>

                    </li>
                </ul>
                <?php } ?>
                <span class="ml-md-3 mr-md-auto"></span>

                <ul class="navbar-nav">

                    <?php
                    echo controlSystemLangDropdown();
                    echo controlSystemCloseYearDropdown();
                    ?>


                    <li class="nav-item dropdown dropdown-user">
                        <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo $this->session->adminPic; ?>" class="rounded-circle mr-2 _profile-image-border" height="34" alt="">
                            <span><?php echo $this->session->adminFullName; ?></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="sprofile/about" class="dropdown-item"><i class="icon-user-plus"></i> Хувийн мэдээлэл</a>
                            <a href="sprofile/password" class="dropdown-item"><i class="icon-cog5"></i> Нууц үг солих</a>
                            <a href="sprofile/forensics" class="dropdown-item"><i class="icon-coins"></i> Шинжилгээ</a>
                            <div class="dropdown-divider"></div>
                            <a href="/systemowner/logout" class="dropdown-item"><i class="icon-switch2"></i> Системээс гарах</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>


        <div class="navbar navbar-expand-md navbar-light navbar-sticky" style="">
            <div class="text-center d-md-none w-100">
                <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-navigation">
                    <i class="icon-unfold mr-2"></i>
                    Хөтөч меню
                </button>
            </div>

            <div class="navbar-collapse collapse" id="navbar-navigation">
                <?php
                echo systemMenu();
                if ($this->session->userdata['adminAccessTypeId'] == 3 and ! IS_DEFAULT_SYSTEM_USER) {
                    echo '<ul class="navbar-nav ml-xl-auto">
                    <li class="nav-item">
                        <a href="/snifsSearch" class="navbar-nav-link">
                            <i class="icon-search4 mr-2"></i>
                            Нэгдсэн хайлт
                        </a>
                    </li>
                </ul>';
                }
                ?>


                <ul class="navbar-nav ml-md-auto profile-stiky">

                    <li class="nav-item dropdown dropdown-user">
                        <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo $this->session->adminPic; ?>" class="rounded-circle mr-2" height="34" alt="">
                            <span><?php echo $this->session->adminFullName; ?></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="sprofile/about" class="dropdown-item"><i class="icon-user-plus"></i> Хувийн мэдээлэл</a>
                            <a href="sprofile/password" class="dropdown-item"><i class="icon-cog5"></i> Нууц үг солих</a>
                            <a href="sprofile/forensics" class="dropdown-item"><i class="icon-coins"></i> Шинжилгээ</a>
                            <div class="dropdown-divider"></div>
                            <a href="/systemowner/logout" class="dropdown-item"><i class="icon-switch2"></i> Системээс гарах</a>
                        </div>
                    </li>
                </ul>

            </div>
        </div>

        <div id="file-download-preparing-file-modal" title="Экспорт" style="display: none;"><div class="p-3">Таны хүсэлтийг боловсруулж байна. Түр хүлээнэ үү...</div></div>
        <div id="file-download-error-modal" title="Экспорт" style="display: none;"><div class="p-3">Экпорт хийх үед алдаа гарлаа. Дахин экспорт хийнэ үү...</div></div>
        <div class="page-content" id="root-container">

