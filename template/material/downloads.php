<?php require_once(__DIR__ . "/../../system/config.php"); ?>
<div class="col-md-4 text-center">
    <div class="video-details">
        <p class="lead">{{video_title}} <small><span id="video-details">({{video_duration}})</span></small></p>
        <img class="img-thumbnail" src="{{video_thumbnail}}"
             alt="{{video_title}}">
    </div>
    <div id="share-buttons">
        <a href="https://www.facebook.com/sharer/sharer.php?u={{page_link}}"
           class="btn btn-sm btn-social btn-fill btn-facebook" target="_blank"><i class="fab fa-facebook fa-fw"></i>
            Facebook</a>
        <a href="https://twitter.com/home?status=Download%20{{video_title}}%20here%20{{page_link}}"
           class="btn btn-sm btn-social btn-fill btn-twitter" target="_blank"><i class="fab fa-twitter fa-fw"></i>
            Twitter</a>
        <a href="https://plus.google.com/share?url={{page_link}}"
           class="btn btn-sm btn-social btn-fill btn-google" target="_blank"><i class="fab fa-google fa-fw"></i>
            Google+</a>
        <a href="https://pinterest.com/pin/create/button/?url={{page_link}}&media={{video_thumbnail}}&description={{video_title}}"
           class="btn btn-sm btn-social btn-fill btn-pinterest" target="_blank"><i
                    class="fab fa-pinterest-p fa-fw"></i> Pinterest</a>
        <a href="http://www.tumblr.com/share/link?url={{page_link}}&name={{video_title}}"
           class="btn btn-sm btn-social btn-fill btn-tumblr" target="_blank"><i
                    class="fab fa-tumblr fa-fw"></i> Tumblr</a>
        <a href="http://reddit.com/submit?url={{page_link}}&title={{page_link}}"
           class="btn btn-sm btn-social btn-fill btn-reddit" target="_blank"><i
                    class="fab fa-reddit fa-fw"></i> Reddit</a>
    </div>
</div>
<div class="col-md-8 video-links">
    <?php if (isset($_GET["video"]) === true) { ?>
    {{video_links}}
    <?php } ?>
    <?php if (isset($_GET["audio"]) === true) { ?>
    {{audio_links}}
    <?php } ?>
</div>
<div class="col-md-12">
    <?php if ($template_config["ads"] == "true") { ?>
        <div class="ad text-center">
            <?php option("ads.728x90", true); ?>
        </div>
    <?php } ?>
</div>