<div>
    <div class="form_container">

        <form id="form_admin_connect" action="<?= base_url('admin/connect_handler') ?>" method="post">

            <?php
            $validation_errors = validation_errors();
            if (!empty($validation_errors)):
            ?>
            <div class="form_error_container one_full">
                <?= $validation_errors; ?>
            </div>
            <?php endif; ?>

            <div class="one_full">
                <div class="one_quarter">
                    <label for="ti_login">
                        Login
                    </label>
                    <input id="ti_login" name="login" type="text" placeholder="Login" maxlength="255"
                           class="onehundred"/>
                </div>
                <div class="one_quarter">
                    <label for="ti_pwd">
                        Password
                    </label>
                    <input id="ti_pwd" name="pwd" type="password" placeholder="Password" maxlength="255"
                           class="onehundred"/>
                </div>
            </div>
            <div class="one_full">
                <div class="one_quarter">
                    <button type="submit" class="button large magenta">Connexion</button>
                </div>
            </div>

            <div class="clearfix"></div>
        </form>
    </div>
</div>