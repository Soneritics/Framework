<?php
    $this->form
        ->start()
        ->setMethod('post')
        ->setAction('/login')
        ->setParam('id', 'loginForm')
        ->setClass('form-horizontal')
        ->render();
?>
    <div class="form-group">
        <label for="email" class="col-lg-12 control-label">E-mailadres:</label>
        <div class="col-lg-12">
            <input type="text" placeholder="Vul hier je e-mailadres in" value="" class="form-control uniform-input text" name="email" id="email">
            <span class="icon16 fa-user fa right gray marginR10"></span>
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-lg-12 control-label">Wachtwoord:</label>
        <div class="col-lg-12">
            <input type="password" class="form-control uniform-input password" value="" name="password" id="password">
            <span class="icon16 fa fa-lock right gray marginR10"></span>
            <span class="forgot help-block"><a href="/wachtwoord-vergeten">Wachtwoord vergeten?</a></span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12 clearfix form-actions">
            <button id="loginBtn" class="btn btn-info right" type="submit"><span class="icon16 fa fa-sign-in white"></span> Login</button>
        </div>
    </div>
<?php $this->form->end()->render(); ?>