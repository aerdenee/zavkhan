<form action="javascript:;" class="_theme-footer-form _theme-footer">
    <div class="form-group">
        <label>お名前（姓）:</label>
        <input type="text" class="form-control" name="name" placeholder="お名前（名）">
    </div>

    <div class="form-group">
        <label>お電話番号:</label>
        <input type="text" class="form-control" name="phone" placeholder="※ハイフン記入">
    </div>
    
    <div class="form-group">
        <label>メールアドレス:</label>
        <input type="text" class="form-control" name="email" placeholder="メールアドレス">
    </div>

    <div class="form-group">
        <label>お問い合わせ内容:</label>
        <textarea rows="3" cols="3" class="form-control" name="body" placeholder="お問い合わせ内容"></textarea>
    </div>

    <div class="form-group">
        <div class="_send-button" onclick="_sendMail({elem: this});">送信</div>
        <div class="_theme-send-mail-result"></div>
    </div>
</form>