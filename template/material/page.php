<div class="main main-raised" >
    <div class="container container-padding">
        <?php echo $content["content_text"]; ?>
        <?php if (isset($template_config["ads"]) == "true") { ?>
            <div class="ad text-center">
                <?php option("ads.300x300", true); ?>
            </div>
        <?php } ?>
        <div class="section text-center">
            <div class="row">
                <?php include(__DIR__ . "/about.php") ?>
            </div>
        </div>
    </div>
</div>