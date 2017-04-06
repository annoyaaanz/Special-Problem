<div id="resident-login-modal" class="modal fade panel-primary" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content panel panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" title="Exit">&times;</button>
                <h2 class="panel-title">Resident Login</h2>
            </div>
            <div class="modal-body panel-body">
                <form role="form" class="form" method="post" action="#">
                    <fieldset>
                        <div class="form-group col-lg-12"><div class="error-messages-transparent" id="error-messages-resident">&nbsp;</span></div>
                        <div class="labels form-group">
                            <label>Username:</label>
                            <br/>
                            <input type="text" class="form-control" name="resident-username" id="resident-username" placeholder="e.g.: juandelacruz" autofocus>
                        </div>
                        <div class="labels form-group">
                            <label>Password:</label>
                            <input type="password" class="form-control" id="resident-password" placeholder="Password" name="resident-password" value="">
                        </div>
                    </fieldset>    
            </div>
            <div class="panel-footer">
                <div class="form-group">
                    <input type="submit" class="form-control btn btn-primary btn-block" value="Login" title="Login" name="resident-login" id="resident-login"/>
                </div>
                <div>
                    No account yet? Sign up <a href="resident-signup.php">here</a>.
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div id="lgu-login-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content panel panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="panel-title">Local Government Unit Login</h2>
            </div>
            <div class="modal-body panel-body">
                <form role="form" class="form" method="post" id="lgu-form">
                    <fieldset>
                        <div class="form-group col-lg-12"><div class="error-messages-transparent" id="error-messages-lgu">&nbsp;</span></div>
                        <div class="labels form-group">
                            <label>Username:</label>
                            <br/>
                            <input type="text" name="lgu-username" id="lgu-username" class="form-control" placeholder="e.g.: juandelacruz" autofocus>
                        </div>
                        <div class="labels form-group">
                            <label>Password:</label>
                            <input type="password" class="form-control" id="lgu-password" id="lgu-password" placeholder="Password" name="lgu-password" value="">
                        </div>
                    </fieldset>
                
            </div>
            <div class="panel-footer">
                <div>
                    <input type="submit" class="btn btn-primary btn-block login-btn" value="Login" name="lgu-login" id="lgu-login" />
                </div>
                <div>
                    <p>No account yet? Sign up <a href="">here</a>.</p>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div id="admin-login-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content panel panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="panel-title">Admin Login</h2>
            </div>
            <div class="modal-body panel-body">
                <form role="form" class="form" method="POST" action="#">
                    <fieldset>
                        <div><span id="error-messages">&nbsp;</span></div>
                        <div class="labels form-group">
                            <label>Username:</label>
                            <br/>
                            <input type="text" name="admin-username" id="admin-username" class="form-control" placeholder="e.g.: juandelacruz" autofocus/>
                        </div>
                        <div class="labels form-group">
                            <label>Password:</label>
                            <input type="password" class="form-control" id="admin-password" id="admin-password" placeholder="Password" name="admin-password"/>
                        </div>
                    </fieldset>
                
            </div>
            <div class="panel-footer">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block login-btn" name="admin-login" value="Login"/>
                </div>
                <div>
                    <p>No account yet? Sign up <a href="">here</a>.</p>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>