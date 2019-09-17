<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="description" content="<?php echo $config["description"]; ?>">
    <meta name="author" content="<?php echo $config["author"]; ?>"/>
    <meta name="generator" content="All in One Video Downloader"/>
    <?php
    if (isset($_GET["page"]) != "") {
        $content = content($slug);
        $title = $content["content_name"];
        $description = $content["content_meta"];
    } else {
        $title = $config["title"];
        $description = $config["description"];
    }
    ?>
    <title><?php echo $title; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <meta itemprop="name" content="<?php echo $title; ?>">
    <meta itemprop="description" content="<?php echo $description; ?>">
    <meta itemprop="image" content="<?php echo $config["url"]; ?>/assets/img/social-media-banner.jpg">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo $title; ?>">
    <meta name="twitter:description" content="<?php echo $description; ?>">
    <meta name="twitter:image:src" content="<?php echo $config["url"]; ?>/assets/img/social-media-banner.jpg">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:type" content="article">
    <meta property="og:image" content="<?php echo $config["url"]; ?>/assets/img/social-media-banner.jpg">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:site_name" content="<?php echo $title; ?>">
    <link rel="stylesheet" href="<?php echo $config["url"]; ?>/template/material/css/material.css"/>
    <link rel="stylesheet" href="<?php echo $config["url"]; ?>/template/material/css/custom.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="shortcut icon" href="<?php echo $config["url"]; ?>/assets/img/favicon.png"/>
    <?php
    $bg = array('bg-1.jpg', 'bg-2.jpg', 'bg-3.jpg', 'bg-4.jpg', 'bg-5.jpg');
    $i = rand(0, count($bg) - 1);
    $selected_bg = $config["url"] . "/template/material/img/" . $bg[$i];
    ?>
</head>
<body id="body" class="landing-page sidebar-collapse">
<nav class="navbar navbar-color-on-scroll navbar-transparent    fixed-top  navbar-expand-lg " color-on-scroll="100"
     id="sectionsNav">
    <div class="container">
        <div class="navbar-translate">
            <a class="navbar-brand" href="<?php echo $config["url"]; ?>">
                <?php echo $config["title"]; ?> </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php
                build_menu();
                if (isset($template_config["tos"]) == "true") {
                    echo "<li class='nav-item'><a class='nav-link' href='?page=tos'>" . $lang["terms-of-service"] . "</a></li>";
                }
                if (isset($template_config["contact"]) == "true") {
                    echo "<li class='nav-item'><a class='nav-link' href='?page=contact'>" . $lang["contact"] . "</a></li>";
                }
                ?>
                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <i class="material-icons">language</i> <?php echo $lang["language"]; ?>
                    </a>
                    <div class="dropdown-menu dropdown-with-icons">
                        <?php list_languages(); ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php if (isset($_GET["page"]) != "") { ?>
    <div class="page-header header-filter" data-parallax="true"
         style="background-image: url('<?php echo $selected_bg; ?>'); height: 22vh;">
    </div>
<?php } else { ?>
<div class="page-header header-filter" data-parallax="true"
     style="background-image: url('<?php echo $selected_bg; ?>')">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h1 class="title"><?php echo $lang["homepage-slogan"]; ?></h1>
                <form>
                    <div class="form-group">
                        <div class="input-group">
                            <input name="url" type="url" id="url" class="form-control"
                                   placeholder="<?php echo $lang["placeholder"]; ?>">
                            <input type="hidden" name="token" id="token" value="<?php echo $_SESSION["token"]; ?>">
                            <button type="button" class="btn btn-warning btn-download" data-toggle="popover"
                                    data-placement="bottom" data-trigger="manual"
                                    data-content="<?php echo $lang["error-alert"] . " " . $lang["try-again"]; ?>"
                                    id="send">
                                <i class="btn-icons material-icons">cloud_download</i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>