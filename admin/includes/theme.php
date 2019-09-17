<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Theme Settings</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $config = json_decode(option("theme.general"), true);
                        if (@$_POST && $_SESSION["logged"] === true) {
                            database::update_option("theme.menu", $_POST["menu"]);
                            unset($_POST["menu"]);
                            database::update_option("theme.general", json_encode($_POST));
                            echo '<p class="alert alert-success">Settings saved.</p>';
                        }
                        ?>
                        <form method="post">
                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="true" name="about"
                                               type="checkbox" <?php check_config($config, "about"); ?>>
                                        Show About Area
                                        <span class="form-check-sign"><span class="check"></span></span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="true" name="ads"
                                               type="checkbox" <?php check_config($config, "ads"); ?>>
                                        Show Ads
                                        <span class="form-check-sign"><span class="check"></span></span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="true" name="tos"
                                               type="checkbox" <?php check_config($config, "tos"); ?>>
                                        Show ToS page link
                                        <span class="form-check-sign"><span class="check"></span></span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="true" name="contact"
                                               type="checkbox" <?php check_config($config, "contact"); ?>>
                                        Show Contact page link
                                        <span class="form-check-sign"><span class="check"></span></span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="true" name="social"
                                               type="checkbox" <?php check_config($config, "social"); ?>>
                                        Show links of social media
                                        <span class="form-check-sign"><span class="check"></span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fab fa-facebook"></i>
                                        </div>
                                    </div>
                                    <input type="text" value="<?php echo $config["facebook"]; ?>"
                                           placeholder="Facebook Username" name="facebook" class="form-control">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fab fa-twitter"></i>
                                        </div>
                                    </div>
                                    <input type="text" value="<?php echo $config["twitter"]; ?>"
                                           placeholder="Twitter Username" name="twitter" class="form-control">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fab fa-google-plus"></i>
                                        </div>
                                    </div>
                                    <input type="text" value="<?php echo $config["google"]; ?>"
                                           placeholder="Google+ Username" name="google" class="form-control">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fab fa-youtube"></i>
                                        </div>
                                    </div>
                                    <input type="text" value="<?php echo $config["youtube"]; ?>"
                                           placeholder="YouTube Username" name="youtube" class="form-control">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fab fa-instagram"></i>
                                        </div>
                                    </div>
                                    <input type="text" value="<?php echo $config["instagram"]; ?>"
                                           placeholder="Instagram Username" name="instagram" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="menu">Menu Links</label><br>
                                <textarea id="menu" name="menu" class="form-control" cols="50"
                                          rows="5">
                                <?php option("theme.menu", true); ?>
                                </textarea>
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