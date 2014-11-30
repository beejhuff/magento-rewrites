<?php
require_once('vendor/autoload.php');

use MagentoRewrites\Inspect;

$inspector = new MagentoRewrites\Inspect;

$file = file_get_contents("../../Modules/Simple-Image-Helper/app/code/community/Hackathon/SimpleImageHelper/etc/config.xml");

var_dump($inspector->run($file));
