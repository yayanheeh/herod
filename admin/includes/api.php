<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">API Keys</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (@$_POST && $_SESSION["logged"] === true) {
                            database::update_option("api_key.soundcloud", $_POST["soundcloud"]);
                            database::update_option("api_key.flickr", $_POST["flickr"]);
                            database::update_option("api_key.instagram", $_POST["instagram"]);
                            database::update_option("api_key.recaptcha", $_POST["recaptcha"]);
                            echo '<p class="alert alert-success">Settings saved.</p>';
                        }
                        ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="soundcloud">Recaptcha</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                    </div>
                                    <input id="recaptcha" class="form-control" type="text" name="recaptcha"
                                           value="<?php option("api_key.recaptcha", true); ?>"
                                           placeholder="Google Recaptcha API key">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="soundcloud">Soundcloud</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fab fa-soundcloud"></i>
                                        </div>
                                    </div>
                                    <input id="soundcloud" class="form-control" type="text" name="soundcloud"
                                           value="<?php option("api_key.soundcloud", true); ?>"
                                           placeholder="Soudcloud API key">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="flickr">Flickr</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fab fa-flickr"></i>
                                        </div>
                                    </div>
                                    <input id="flickr" class="form-control" type="text" name="flickr"
                                           value="<?php option("api_key.flickr", true); ?>"
                                           placeholder="Flickr API Key">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fab fa-instagram"></i>
                                        </div>
                                    </div>
                                    <input id="flickr" class="form-control" type="text" name="instagram"
                                           value="<?php option("api_key.flickr", true); ?>"
                                           placeholder="username:password">
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-info btn-fill btn-wd">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>