<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>All in One Video Downloader Installer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto
        }

        .form-signin .checkbox {
            font-weight: 400
        }

        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px
        }

        .form-signin .form-control:focus {
            z-index: 2
        }

        .form-signin input[type=email] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0
        }

        .form-signin input[type=password] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0
        }
    </style>
</head>
<body class="text-center">
<form method="post" class="form-signin">
    <?php
    if (@$_POST) {
        include(__DIR__ . "/admin/functions.php");
        $installation_data["name"] = $_POST["author"];
        $installation_data["version"] = $_POST["version"];
        $installation_data["purchaseCode"] = $_POST["purchase_code"];
        $installation_data["url"] = rtrim($_POST["url"], '/\\');
        $installation_data["email"] = $_POST["email"];
        $installation_data["ip"] = gethostbyname(gethostname());
        $installation_data["userIp"] = get_user_ip();
        $installation_data["checksum"] = $_POST["checksum"];
        $installation_data = urlencode(base64_encode(json_encode($installation_data)));
        $config_json = FALSE;
        if ($config_json != "Registration failed. Check input data and try again or contact with seller.") {
            $admin_password = sha1($_POST["password"]);
            $original_db_config = file_get_contents(__DIR__ . "/system/db.php");
            $db_config = str_replace("{{host}}", $_POST["database_host"], $original_db_config);
            $db_config = str_replace("{{name}}", $_POST["database_name"], $db_config);
            $db_config = str_replace("{{user}}", $_POST["database_user"], $db_config);
            $db_config = str_replace("{{pass}}", $_POST["database_password"], $db_config);
            file_put_contents(__DIR__ . "/system/db.php", $db_config);
            $db = new PDO("mysql:host=" . $_POST["database_host"] . ";dbname=" . $_POST["database_name"] . ";charset=utf8mb4", $_POST["database_user"], $_POST["database_password"]);
            $sql = file_get_contents(__DIR__ . '/db.sql');
            $sql = str_replace("{{general_settings}}", $db->quote($config_json), $sql);
            $sql = str_replace("{{admin_email}}", $_POST["email"], $sql);
            $sql = str_replace("{{admin_pass}}", $admin_password, $sql);
            $sql = str_replace("{{admin_name}}", $_POST["author"], $sql);
            file_put_contents(__DIR__."/db.sql", $sql);
            $qr = $db->exec($sql);
            echo '<p class="alert alert-success">Installation completed! <a href="' . $_POST["url"] . '">Go to website</a> <a href="' . $_POST["url"] . '/admin">Go to admin panel</a> </p>';
            echo '<p class="alert alert-warning">Do not forget to delete "install.php" and "db.sql" file!</p>';
        } elseif ($config_json == "You can use one license only one website.") {
            echo '<p class="alert alert-warning">You can use one license for only one website.</p>';
        } else {
            echo '<p class="alert alert-warning">Installation failed. Check input data and try again or contact with seller.</p>';
        }
    }
    ?>
    <img class="mb-4" src="assets/img/favicon.png" alt="all in one video downloader" width="72" height="72">
    <h1 class="h3 mb-3 font-weight-normal">Installation</h1>
    <input name="url" type="url" class="form-control" placeholder="Website URL" required autofocus>
    <input name="author" type="text" class="form-control" placeholder="Website Owner" required>
    <hr>
    <input name="email" type="email" class="form-control" placeholder="Owner's E-mail" required>
    <input name="password" type="password" class="form-control" placeholder="Admin Panel Password" required>
    <input name="password_2" type="password" class="form-control" placeholder="Confirm Password" required>
    <hr>
    nulled
    <hr>
    <input name="database_host" type="text" class="form-control" placeholder="Database Host" required>
    <input name="database_name" type="text" class="form-control" placeholder="Database Name" required>
    <input name="database_user" type="text" class="form-control" placeholder="Database User" required>
    <input name="database_password" type="password" class="form-control" placeholder="Database Password" required>
    <input name="title" type="hidden" class="form-control" placeholder="Website Title"
           value="All in One Video Downloader" required>
    <input name="description" type="hidden" value="">
    <input name="language" type="hidden" value="en">
    <input name="template" type="hidden" value="start">
    <input name="tracking" type="hidden" value="off">
    <input name="auto-update" type="hidden" value="on">
    <input name="version" type="hidden" value="MTYxMTkuMTc2MzEuMTc5MjM=4">
    <input name="checksum" type="hidden" value="<?php echo sha1_file(__DIR__ . "/system/action.php") ?>">
    <button class="btn btn-lg btn-primary btn-block" type="submit">Accept EULA & Install</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2018</p>
</form>
</body>
</html>