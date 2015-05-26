<div class="form_pagination_wrapper">
    <div class="form_pagination_container">
        <div id="btn_close_form_pagination" class="">
            <button class="button rounded red">X</button>
        </div>

            <div class="onehundred">
                <?php if(empty($errors)): ?>
                <h2>Trop de résultats (<?= $nb_results ?>)</h2>
                <?php else: ?>
                <h2>Attention !</h2>
                <?php endif; ?>
            </div>

        <div class="onehundred">
            <?php if(empty($errors)): ?>
            <p>Veuillez affinez votre requête en fermant cette fenêtre.</p>
            <p>Vous pouvez aussi récupérer les <?=  $this->session->userdata('viadeo_api_result_max_limit') ?> premiers résultats.</p>
            <?php else: ?>
            <?= $errors ?>
            <?php endif; ?>
        </div>

        <?php if(empty($errors)): ?>
        <button type="button" id="btn_confirm_form_pagination" class="button magenta alignright">Récupérer</button>
        <?php endif; ?>
<!--
            <div class="onehundred">
                Veuillez selectionner une page, jusqu'à 1000 résultats seront alors récupérés pour cette page là.
            </div>

            <br/>
            <div class="onehundred">
                <label for="ti_page">
                    N° de Page
                </label>

                <div class="onehundred">
                    <input id="ti_page" name="page" type="number" style="width: 200px;"
                           placeholder="n° de page compris entre 1 et <?= $nb_pages ?>" min="1" max="<?= $nb_pages ?>"/>
                    <button type="button" id="btn_confirm_form_pagination" style="margin-left: 15px;" class="button magenta">Valider</button>
                </div>
            </div>
-->

            <div class="clearfix"></div>
    </div>
</div>