<style type="text/css">
    .form-control {
        margin-bottom: 20px;
    }
</style>
<section class="cs-page-heading-area" style="background-image: url(<?php echo ($row['show_pic'] == 1 ? UPLOADS_CONTENT_PATH . CROP_BIG . $row['pic'] : '/assets/' . DEFAULT_THEME . 'images/default_header1.jpg'); ?>); background-position: bottom; background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 animatedParent animateOnce">

            </div>
        </div>
    </div>
</section>
<section>
    <div class="container background-white">
        <div class="section-content" style="padding-top: 20px;">
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px;">
                    <!--Map Container-->
                    <div class="map-outer">
                        <!--Map Canvas-->
                        <div class="map-contact"
                             data-zoom="6"
                             data-lat="<?php echo $row['lat']; ?>"
                             data-lng="<?php echo $row['lng']; ?>"
                             data-type="roadmap"
                             data-hue="#ffc400"
                             data-title="<?php echo $row['title_' . $this->session->langShortCode]; ?>"
                             data-content="<?php echo $row['address_' . $this->session->langShortCode]; ?>"              
                             style="height: 300px;">
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-item" style="padding:0px;">
                        <div class="content">
                            <?php
                            echo '<h4>' . $this->lang->line('THEME_CONTACT_TITLE') . '</h4>';
                            echo '<div class="small-line-2"></div>';
                            if ($row['show_country'] == '1') {
                                echo $row['country'] . '<br>';
                            }

                            if ($row['show_address'] == '1') {
                                echo $row['address_' . $this->session->langShortCode] . '<br>';
                            }
                            echo '<br>';
                            if ($row['show_phone'] == '1') {
                                echo '<abbr title="Phone">P:</abbr> ' . $row['phone'] . '<br>';
                            }

                            if ($row['show_mobile'] == '1') {
                                echo '<abbr title="Phone">P:</abbr> ' . $row['mobile'] . '<br>';
                            }

                            if ($row['show_fax'] == '1') {
                                echo '<abbr title="Fax">F:</abbr> ' . $row['fax'] . '<br>';
                            }
                            ?>

                        </div>
                        <span class="icon icon-Starship"></span>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Contact Form -->
                    <h4><?php echo $this->lang->line('THEME_CONTACT_SEND_MAIL_TITLE'); ?></h4>
                    <div class="small-line-2"></div>

                    <div id="result-mail"></div>
                    <?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-contact', 'enctype' => 'multipart/form-data')); ?>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" class="form-control" id="campId" name="campId" value="<?php echo $campId; ?>">
                    <input type="hidden" class="form-control" id="catId" name="catId" value="<?php echo $catId; ?>">
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                            //echo form_label('Таны нэр', 'Таны нэр', array('required' => 'required'));
                            echo form_input(array(
                                'name' => 'name',
                                'id' => 'name',
                                'maxlength' => '100',
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => 'Таны нэр'
                            ));
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            //echo form_label('Таны мэйл', 'Таны мэйл', array('required' => 'required'));
                            echo form_input(array(
                                'name' => 'email',
                                'id' => 'email',
                                'maxlength' => '100',
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => 'Таны мэйл'
                            ));
                            ?>
                        </div>
                    </div>                
                    <?php
                    //echo form_label('Захиа', 'Захиа', array('required' => 'required'));
                    echo form_textarea(array(
                        'name' => 'message',
                        'id' => 'message',
                        'rows' => 5,
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Захиа'
                    ));
                    ?>
                    <input name="form_botcheck" class="form-control" type="hidden" value="">
                    <?php echo form_button('send', 'Илгээх', 'class="btn theme-btn cs-my-btn" onclick="sendForm();"', 'button'); ?>
                    <span id="result-mail"></span>
                    <?php echo form_close(); ?>
                    <div class="clearfix"></div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function resetForm() {
        $("#form-contact", "body").find('.form-control').val('');
        $("#form-contact", "body").find('.form-control').removeClass('error');
    }    
    function sendForm() {
        $("#form-contact", "body").validate({errorPlacement: function () {}});
        if ($("#form-contact", "body").valid()) {
            $.ajax({
                type: 'post',
                url: '/contact/send',
                data: $("#form-contact", "body").serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    resetForm();
                    $("#result-mail").html("<div class='"+data.class+"'>"+data.message+"</div>");
                    $.unblockUI();
                }
            });
            $.unblockUI();
        }
    }
    
</script>
