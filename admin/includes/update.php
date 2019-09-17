<?php
if (isset($_SESSION["logged"]) === true) {
    if (@$_POST["start"] != "") {
        $ch = curl_init();
        $fingerprint = create_fingerprint(rtrim(config("url"), '/\\'), config("purchase_code"));
        $source = "http://api.nicheoffice.web.tr/download/update/" . $fingerprint;
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Niche Office - All in One Video Downloader Update Tool - VERSION:' . config("version"));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $destination = __DIR__ . "/../../system/storage/temp/" . $fingerprint . ".zip";
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);
        $zip = new ZipArchive;
        $res = $zip->open($destination);
        if ($res === true) {
            $zip->extractTo(__DIR__ . "/../../");
            $zip->close();
            include(__DIR__ . "/../../system/update.php");
            unlink($destination);
            $alert = true;
        } else {
            $alert = false;
        }
    }
    ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Software Updates</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($alert) === true) {
                            echo '<p class="alert alert-success">Software updated to latest version.</p>';
                        }
                        if (isset($alert) === false) {
                            echo '<p class="alert alert-warning">Error occurred while updating the software.</p>';
                        }
                        ?>
                        <p>
                            <strong>Changelog</strong>
                        <pre><?php echo changelog(); ?></pre>
                        </p>
                        <form method="post">
                            <button name="start" type="submit" class="btn btn-outline-info"
                                    value="Check & Install Updates" disabled>Check & Install Updates
                            </button>
                        </form>
                        <div class="text-right">
                            <p>
                                <small>You should backup your data before starting update.</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>