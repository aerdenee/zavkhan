<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Удирдах хуудас</title>
        <base href="<?php echo base_url(); ?>">
        <link href="assets/global.min.<?php echo CSS_JS_VERSION; ?>.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="assets/global.min.<?php echo CSS_JS_VERSION; ?>.js"></script>
        <script type="text/javascript" src="assets/system/core/_authentication.js"></script>
    </head>
    <style type="text/css">
        .validation-valid-label {
            display: none !important;
        }
        ._background-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
        }
        .page-content {
            background-color: rgba(0,0,0,0.6);
            height: 100%;
            margin: auto;
            position: inherit;
            width: 100%;
        }
        .card-header .card-title {
            margin: auto;
            text-transform: uppercase;
            font-size: 13px;
        }
    </style>

    <body class="bg-slate-800">
        <video autoplay="" muted="" loop="" class="_background-video">
            <source src="assets/system/img/backgrounds/stars.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Content area -->
                <div class="content d-flex justify-content-center align-items-center">

                    <!-- Login card -->

                    <?php echo form_open('systemowner/login', array('class' => 'login-form')); ?>
                    <div class="card mb-0" style="border: none;">
                        <div class="card-header bg-primary text-white header-elements-inline">
                            <h6 class="card-title text-center">Бид шинжлэх ухааны бодит үнэнд тулгуурласан дүгнэлт гаргана</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 hidden-sm hidden-xs">
                                    <img src="assets/theme/forensics/img/logo3d.png">
                                </div>

                                <div class="col-md-7">

                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <?php
                                        echo form_input(array(
                                            'name' => 'user',
                                            'id' => 'user',
                                            'maxlength' => '50',
                                            'class' => 'form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Нэвтрэх нэр'
                                        ));
                                        ?>
                                        <div class="form-control-feedback">
                                            <i class="icon-user text-muted"></i>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <?php
                                        echo form_password(array(
                                            'name' => 'password',
                                            'id' => 'password',
                                            'maxlength' => '50',
                                            'class' => 'form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Нууц үг'
                                        ));
                                        ?>
                                        <div class="form-control-feedback">
                                            <i class="icon-lock2 text-muted"></i>
                                        </div>
                                    </div>
                                    
                                    <?php echo $controlSystemLangLoginDropdown;?>

                                    <div class="form-group d-flex align-items-center">

                                        <div class="form-check mb-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input-styled" name="stacked-radio-left" checked>
                                                Нэвтрэх нэр хадгалах
                                            </label>
                                        </div>

                                        <a href="javascript:;" class="ml-auto">Нууц үг сэргээх</a>
                                    </div>

                                    <div class="form-group">
                                        <?php echo form_button('authentication', 'Нэвтрэх <i class="icon-circle-right2 ml-2"></i>', 'class="btn btn-primary btn-block"', 'submit'); ?>
                                    </div>

                                    <div class="form-group text-center text-muted content-divider">
                                        <span class="px-2">Холбоо барих</span>
                                    </div>

                                    <span class="form-text text-center text-muted">Програм хангамжтай холбоотой асуудлаар <br><a href="mailto:support@nifs.gov.mn">support@nifs.gov.mn</a> мэйл хаягаар хандан зөвлөгөө авна уу? </span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!-- /login card -->    

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->
</html>
