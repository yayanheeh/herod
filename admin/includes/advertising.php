<?php if(isset($_SESSION["logged"]) === true){ ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Advertising</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (@$_POST && $_SESSION["logged"] === true) {
                            database::update_option("ads.728x90", $_POST["728_90"]);
                            database::update_option("ads.300x300", $_POST["300_300"]);
                            echo '<p class="alert alert-success">Settings saved.</p>';
                        }
                        ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="970_250">970x250</label><br>
                                <textarea id="970_250" class="form-control" rows="5" cols="80" name="728_90"><?php option("ads.728x90", true); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="728_90">728x90</label><br>
                                <textarea id="728_90" class="form-control" rows="5" cols="80" name="300_300"><?php option("ads.300x300", true); ?></textarea>
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