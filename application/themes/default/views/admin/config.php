<div id="btn_logout" class="" xmlns="http://www.w3.org/1999/html">
    <a href="<?= base_url('admin/disconnect') ?>" class="button rounded red">X</a>
</div>
<div>
    <div class="form_container">
        <form id="form_search" action="<?= base_url('admin/config_handler') ?>" method="post">

            <?php
            $validation_errors = validation_errors();
            if( !empty($validation_errors) ):
            ?>
            <div class="form_error_container one_full">
                <?= $validation_errors; ?>
            </div>
            <?php endif; ?>

            <div class="one_full">
                <h2>Configuration des paramètres</h2>
            </div>

            <div class="one_full">
                <h4>Viadeo API</h4>
            </div>

            <div class="one_full">
                <div class="one_quarter">
                    <label for="ti_viadeo_api_client_id">
                        Client ID
                    </label>
                    <input id="ti_viadeo_api_client_id" name="viadeo_api[client_id]" type="text" placeholder="Client ID" value="<?= $list_params['viadeo_api_client_id']->value ?>" maxlength="255" class="onehundred"/>
                </div>

                <div class="one_quarter">
                    <label for="ti_viadeo_api_client_secret">
                        Client Secret
                    </label>
                    <input id="ti_viadeo_api_client_secret" name="viadeo_api[client_secret]" type="password" placeholder="Client Secret" value="<?= $list_params['viadeo_api_client_secret']->value ?>" maxlength="255" class="onehundred"/>
                </div>

                <div class="one_quarter">
                    <label for="ti_viadeo_api_result_max_limit">
                        Limite de résultat <small>( max 2500 )</small>
                    </label>
                    <input id="ti_viadeo_api_result_max_limit" name="viadeo_api[result_max_limit]" type="number" min="0" step="100" max="2500" value="<?= $list_params['viadeo_api_result_max_limit']->value ?>" maxlength="4" class="onehundred"/>
                </div>
            </div>

            <div class="one_full">
                <h4>Identifiants de l'interface d'administration</h4>
            </div>

            <div class="one_full">

                <div class="one_quarter">
                    <label for="ti_login">
                        Login
                    </label>
                    <input id="ti_login" name="login" type="text" placeholder="Login" value="<?= $admin->login ?>" maxlength="255" class="onehundred"/>
                </div>

                <div class="one_quarter">
                    <label for="ti_pwd_1">
                        Password <small>( saisir 2 fois )</small>
                    </label>
                    <input id="ti_pwd_1" name="pwd_1" type="password" maxlength="255" class="onehundred"/>
                    <input id="ti_pwd_2" name="pwd_2" type="password" maxlength="255" class="onehundred"/>
                </div>

            </div>

            <div class="one_full">
                <div class="one_quarter">
                    <button type="submit" class="button large magenta">Appliquer</button>
                </div>
            </div>

            <div class="clearfix"></div>
        </form>
    </div>
</div>