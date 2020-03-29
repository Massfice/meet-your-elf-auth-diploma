<?php

// header("Content-Type: application/json");
// echo json_encode(getallheaders());

require_once dirname(__DIR__,1)."/vendor/autoload.php";

use Massfice\Application\System\Cleans;
use Massfice\Action\JsonActionFactory;
use Massfice\Action\JsonAction;

echo "Type: " . Cleans::getType() . "<br>";
echo "Action: " . Cleans::getAction() . "<br>";
echo "Method: " . $_SERVER["REQUEST_METHOD"] . "<br>";

$factory = new JsonActionFactory("\\Massfice\\Application\\Actions\\");
$action = $factory->create("sidGET");

var_dump($action);

interface TestInterface {

}

class test implements TestInterface, JsonAction{

}

// $test = new Test();
var_dump(class_implements("test"));

?>