<?php

// header("Content-Type: application/json");
// echo json_encode(getallheaders());

require_once dirname(__DIR__,1)."/vendor/autoload.php";

use Massfice\Application\System\Cleans;



echo "Type: " . Cleans::getType() . "<br>";
echo "Action: " . Cleans::getAction() . "<br>";
echo "Method: " . $_SERVER["REQUEST_METHOD"];

?>