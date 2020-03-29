<?php

// header("Content-Type: application/json");
// echo json_encode(getallheaders());

require_once dirname(__DIR__,1)."/vendor/autoload.php";

use Massfice\Application\System\Cleans;



echo Cleans::getType() . "<br>";
echo Cleans::getAction();

?>