<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Удирдах хуудас</title>
        <base href="<?php echo base_url(); ?>">
        <link href="assets/global.min.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="assets/global.min.js"></script>

    </head>

    <body class="login-container login-cover">

        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main content -->
                <div class="content-wrapper">

                    <!-- Form with validation -->
                    <form action="index.html" class="form-validate">
                        <div class="panel panel-body login-form">
                            <div class="text-center">
                                <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                                <h5 class="content-group">Login to your account <small class="display-block">Your credentials</small></h5>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
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

                            <div class="form-group has-feedback has-feedback-left">
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

                            <div class="form-group login-options">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline">
                                            <?php
                                            echo form_checkbox(array(
                                                'name' => 'mycbx[]',
                                                'value' => 0,
                                                'checked' => set_checkbox('mycbx[]', 0, false),
                                                'class' => 'styled'
                                            ));
                                            ?>
                                            Нууц үг санах
                                        </label>
                                    </div>

                                    <div class="col-sm-6 text-right">
                                        <a href="<?php echo MY_ADMIN . '/password';?>">Нууц үг сэргээх</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn bg-blue btn-block">Нэвтрэх <i class="icon-arrow-right14 position-right"></i></button>
                            </div>


                            <div class="content-divider text-muted form-group"><span>Холбоо барих</span></div>
                            <span class="help-block text-center no-margin">Програм хангамжтай холбоотой асуудлаар <br>7044-7044 дугаарын утсанд ажлын цагаар хандан зөвлөгөө авна уу? </span>
                        </div>
                    </form>
                    <!-- /form with validation -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>
        <!-- /page container -->


        <!-- Footer -->
        <div class="footer text-white text-center">
            &copy; 2015-<?php echo date('Y'); ?> он, "Nifs" систем<br>
            Beta version
        </div>
        <!-- /footer -->
        <?php
        if (isset($jsFile)) {
            foreach ($jsFile as $js) {
                echo '<script src="' . $js . '" type="text/javascript"></script>' . "\n";
            }
        }
        ?>
    </body>
</html>
