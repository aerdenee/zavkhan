<!-- Form with validation -->
<form action="javascript:;" class="form-validate" id="password-reset">

        <div class="form-group has-feedback has-feedback-left">
            <?php
            echo form_input(array(
                'name' => 'email',
                'id' => 'email',
                'maxlength' => '50',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Бүртгэлтэй мэйл хаяг'
            ));
            ?>
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
            </div>
        </div>
</form>
<!-- /form with validation -->