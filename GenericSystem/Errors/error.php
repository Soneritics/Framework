<h3 class="center"><strong>Fout opgetreden!</strong></h3>
<div id="loginForm" class="form-horizontal" novalidate="novalidate">
    <div class="form-group">
        Er is een onbekende fout opgetreden.
    </div>
    <div class="form-group">
        <code><?php echo $this->escape($exception->msg); ?></code>
    </div>
    <div class="form-group">
        <div class="col-lg-12 clearfix form-actions">
            <a href="/" id="loginBtn" class="btn btn-danger right"><span class="icon16 fa fa-backward white"></span> Beginscherm</a>
        </div>
    </div>
</div>