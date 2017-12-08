<?php

require __DIR__ . "/../vendor/autoload.php";

use Curl\Curl;

$ch = new Curl();
$a = $ch->get("https://m.facebook.com");