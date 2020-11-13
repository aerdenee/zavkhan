<div class="card">


    <div class="card-body">
        
        <form action="javascript:;" id="form-contact">
                    <div class="form-group">
                        <label><?php echo ($this->session->userdata['themeLanguage']['id'] == 2 ? 'Your Name (required)' : 'Танай нэр');?>:</label>
                        <input type="text" name="name" class="form-control" required="true">
                    </div>

                    <div class="form-group">
                        <label><?php echo ($this->session->userdata['themeLanguage']['id'] == 2 ? 'Your Email (required)' : 'Танай мэйл хаяг');?>:</label>
                        <input type="text" name="email" class="form-control" required="true">
                    </div>

                    <div class="form-group">
                        <label><?php echo ($this->session->userdata['themeLanguage']['id'] == 2 ? 'Your Message (required)' : 'Захиа');?>:</label>
                        <textarea name="body" rows="5" cols="5" class="form-control" required="true"></textarea>
                    </div>
                <div class="pull-left" id="contact-form-result"></div>
                    <div class="pull-right">
                        <button type="button" onclick="_sendContactForm({_this:this});" class="btn btn-primary"><?php echo ($this->session->userdata['themeLanguage']['id'] == 2 ? 'Submit' : 'Илгээх');?> <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>
        
    </div>
</div>