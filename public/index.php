<?php

// header("Content-Type: application/json");
// echo json_encode(getallheaders());

require_once dirname(__DIR__,1)."/vendor/autoload.php";

use Massfice\Application\System\Cleans;
use Massfice\Action\JsonActionFactory;

use Massfice\Action\JsonAction;
use Massfice\Action\VerifyStatus;
use Massfice\ResponseStatus\ResponseStatus;
use Massfice\ResponseStatus\ResponseStatusFactory;

echo "Type: " . Cleans::getType() . "<br>";
echo "Action: " . Cleans::getAction() . "<br>";
echo "Method: " . $_SERVER["REQUEST_METHOD"] . "<br>";

$factory = new JsonActionFactory("\\Massfice\\Application\\Actions\\");
$action = $factory->create("sidGET");

var_dump($action);

interface TestInterface {
}

class test implements JsonAction {
    public function verify() : VerifyStatus {
        return new VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        return [];
    } 

    public function validate(array $data) : ResponseStatus {
        return ResponseStatusFactory::create(200);
    }

    public function execute(array $data) : array {
        return $data;
    }
}

$factory2 = new JsonActionFactory("");
$testAction = $factory2->create("test");

var_dump($testAction);

// $test = new Test();
var_dump(class_implements("\\Massfice\\Application\\Actions\\sidGET"));

?>