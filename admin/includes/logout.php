<?php
session_destroy();
$config = json_decode(option(), true);
redirect($config["url"] . "/admin");