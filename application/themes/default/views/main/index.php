<div>

    <div class="form_container">

            <div class="one_quarter">
                <a href="https://secure.viadeo.com/oauth-provider/authorize2?response_type=code&display=popup&lang=fr&client_id=<?= $this->session->userdata('viadeo_api_client_id') ?>&redirect_uri=<?= base_url('/main/connect') ?>"
                   class="button magenta">Connexion à Viadeo</a>
            </div>
            <div class="clearfix"></div>
    </div>


</div>