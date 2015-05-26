<div id="btn_logout" class="">
    <a href="<?= base_url('main/disconnect') ?>" class="button rounded red">X</a>
</div>
<div>
    <div class="form_container">
        <form id="form_search" action="<?= base_url('main/search_handler') ?>" method="post">

            <?php
            $validation_errors = validation_errors();
            if (!empty($validation_errors)):
                ?>
                <div class="form_error_container one_full">
                    <?= $validation_errors; ?>
                </div>
                <?php endif; ?>

            <input type="hidden" id="ih_page" name="page" value="1"/>

            <div class="one_full">
                <h2>Ajuster les filtres</h2>
            </div>

            <div class="onehundred">
                <div class="one_quarter">
                    <label for="ti_position">
                        Fonction
                    </label>
                    <input id="ti_position"
                           name="position" type="text"
                           placeholder="Fonction (ex: chef de projet)" maxlength="255" class="onehundred"
                           value="<?= set_value('position') ?>"/>

                    <div class="onehundred">
                        <select id="cb_position_option" name="position_option">
                            <option value="all" <?= set_select('position_option', 'all', true) ?>>Logique multi mots-clés</option>
                            <option value="all" <?= set_select('position_option', 'all') ?>>tous (all) - par défaut</option>
                            <option value="any" <?= set_select('position_option', 'any') ?>>n'importe lequel (any)</option>
                            <option value="strict" <?= set_select('position_option', 'strict') ?>>exact (strict)</option>
                        </select>
                    </div>
                </div>

                <div class="one_quarter">
                    <label for="ti_company">
                        Société
                    </label>
                    <input id="ti_company" name="company" type="text"
                           placeholder="Société (ex: edf)" maxlength="255" class="onehundred"
                           value="<?= set_value('company') ?>"/>

                    <div class="onehundred">
                        <select id="cb_company_option" name="company_option">
                            <option value="all" <?= set_select('company_option', 'all', true) ?>>Logique multi mots-clés</option>
                            <option value="all" <?= set_select('company_option', 'all') ?>>tous (all) - par défaut</option>
                            <option value="any" <?= set_select('company_option', 'any') ?>>n'importe lequel (any)</option>
                            <option value="strict" <?= set_select('company_option', 'strict') ?>>exact (strict)</option>
                        </select>
                    </div>
                </div>

                <div class="one_quarter">

                </div>

                <div class="one_quarter">
                    <label for="ti_department">
                        Département
                    </label>
                    <input id="ti_department" name="department" type="text"
                           placeholder="Département (ex: 44)" maxlength="255" class="onehundred"
                           value="<?= set_value('department') ?>"/>
                </div>
            </div>

            <div class="one_full">
                <h2>Configurer les paramètres</h2>
            </div>

            <div class="onehundred">

                <div class="one_quarter">
                    <label for="ti_domain">
                        Domaine
                    </label>
                    <input id="ti_domain" name="domain" type="text"
                           placeholder="domaine (ex:sfr.com)" maxlength="255" class="onehundred"
                           value="<?= set_value('domain') ?>"/>
                </div>

                <div class="one_quarter">
                    <label for="ti_mail_pattern">
                        Pattern d'email
                    </label>
                    <select id="ti_mail_pattern" name="mail_pattern" class="onehundred">
                        <optgroup label="">
                            <option value="prenom.nom" <?= set_select('mail_pattern', 'prenom.nom', true) ?>>prenom.nom</option>
                            <option value="prenom-nom" <?= set_select('mail_pattern', 'prenom-nom') ?>>prenom-nom</option>
                            <option value="prenom_nom" <?= set_select('mail_pattern', 'prenom_nom') ?>>prenom_nom</option>
                            <option value="prenomnom" <?= set_select('mail_pattern', 'prenomnom') ?>>prenomnom</option>
                        </optgroup>
                        <optgroup label="-------">
                            <option value="nom.prenom" <?= set_select('mail_pattern', 'nom.prenom') ?>>nom.prenom</option>
                            <option value="nom-prenom" <?= set_select('mail_pattern', 'nom-prenom') ?>>nom-prenom</option>
                            <option value="nom_prenom" <?= set_select('mail_pattern', 'nom_prenom') ?>>nom_prenom</option>
                            <option value="nomprenom" <?= set_select('mail_pattern', 'nomprenom') ?>>nomprenom</option>
                        </optgroup>
                        <optgroup label="-------">
                            <option value="p.nom" <?= set_select('mail_pattern', 'p.nom') ?>>p.nom</option>
                            <option value="p-nom" <?= set_select('mail_pattern', 'p-nom') ?>>p-nom</option>
                            <option value="p_nom" <?= set_select('mail_pattern', 'p_nom') ?>>p_nom</option>
                            <option value="pnom" <?= set_select('mail_pattern', 'pnom') ?>>pnom</option>
                        </optgroup>
                        <optgroup label="-------">
                            <option value="prenom.n" <?= set_select('mail_pattern', 'prenom.n') ?>>prenom.n</option>
                            <option value="prenom-n" <?= set_select('mail_pattern', 'prenom-n') ?>>prenom-n</option>
                            <option value="prenom_n" <?= set_select('mail_pattern', 'prenom_n') ?>>prenom_n</option>
                            <option value="prenomn" <?= set_select('mail_pattern', 'prenomn') ?>>prenomn</option>
                        </optgroup>
                        <optgroup label="-------">
                            <option value="prenom" <?= set_select('mail_pattern', 'prenom') ?>>prenom</option>
                            <option value="nom" <?= set_select('mail_pattern', 'nom') ?>>nom</option>
                        </optgroup>
                    </select>
                </div>

                <!--
                <div class="one_quarter">
                    <label for="cb_result_limit">
                        Nombre de Résultat Max
                    </label>

                    <div style="padding-top: 15px;">
                        <select id="cb_result_limit" name="result_limit">
                            <option value="" <?= set_select('result_limit', '', true) ?>>Selectionnez...</option>
                            <option value="5" <?= set_select('result_limit', 5) ?>>5</option>
                            <option value="20" <?= set_select('result_limit', 20) ?>>20</option>
                            <option value="50" <?= set_select('result_limit', 50) ?>>50</option>
                            <option value="100" <?= set_select('result_limit', 100) ?>>100</option>
                            <option value="200" <?= set_select('result_limit', 200) ?>>200</option>
                            <option value="500" <?= set_select('result_limit', 500) ?>>500</option>
                            <option value="1000" <?= set_select('result_limit', 1000) ?>>1000</option>
                        </select>
                    </div>
                </div>-->

                <div class="one_quarter">
                    <button type="button" onclick="get_search_count()" class="button large magenta">Requêter</button>
                </div>

            </div>

            <div class="one_full">
                <h2>Liste des 5 dernières entrées en base</h2>
                <table id="table-last-5-entries" class="table-striped">
                    <thead>
                    <tr>
                        <th>created</th>
                        <th>first_name</th>
                        <th>last_name</th>
                        <th>company</th>
                        <th>position</th>
                        <th>city</th>
                        <th>domain</th>
                        <th>email généré</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($list_last_entries as $entry):
                        $rand_mail_index = rand(1, 13);
                        $created = mdate('%d/%m/%Y %h:%i', strtotime($entry->created));
                        ?>
                    <tr>
                        <td><?= $created ?></td>
                        <td><?= $entry->first_name ?></td>
                        <td><?= $entry->last_name ?></td>
                        <td><?= $entry->company ?></td>
                        <td><?= $entry->position ?></td>
                        <td><?= $entry->city ?></td>
                        <td><?= $entry->domain ?></td>
                        <td><?= $entry->mail ?></td>
                        <!--<td><?php //echo $entry->{'mail_' . $rand_mail_index} ?></td>-->
                    </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="clearfix"></div>
        </form>
    </div>
</div>

<?php if (!empty($search_results)) : ?>
<div id="search_result_popup_container" title="Résultats de la recherche" style="display: none;">
    <div id="search_result_popup">
        <p>
            <span class="notification red flat"> <?= $search_results['nb_deleted'] ?></span> supprimé(s).
        </p>

        <p>
            <span class="notification yellow flat"><?= $search_results['nb_updated'] ?></span> mis à jour.
        </p>

        <p>
            <span class="notification green flat"><?= $search_results['nb_added'] ?></span> ajouté(s).
        </p>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        show_search_results_popup('#search_result_popup_container');
    });
</script>
<?php endif; ?>