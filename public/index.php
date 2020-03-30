<?php

// header("Content-Type: application/json");
// echo json_encode(getallheaders());

require_once dirname(__DIR__,1)."/vendor/autoload.php";

use Massfice\Application\System\Cleans;
use Massfice\Application\Customs\JsonActionFactory;

use Massfice\Action\JsonAction;
use Massfice\Action\VerifyStatus;
use Massfice\ResponseStatus\ResponseStatus;
use Massfice\ResponseStatus\ResponseStatusFactory;


echo "Type: " . Cleans::getType() . "<br>";
echo "Action: " . Cleans::getAction() . "<br>";
echo "Method: " . $_SERVER["REQUEST_METHOD"] . "<br>";

$factory = new JsonActionFactory("\\Massfice\\Application\\Actions\\");
echo "kur";
$action = $factory->create("sidGET");
echo "wa";
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
// var_dump(class_implements("\\Massfice\\Application\\Actions\\sidGET"));

// function check(string $name, string $namespace, string $interface) : bool {
//     $action = $namespace.ucfirst($name);
//     $class = new $action();
//     var_dump($class);
//     echo "...<br>";
//     var_dump(class_exists($action));
//     echo "...<br>";
//     echo "[ ---- ". $action ." ---- ]";
//     echo "...<br>";
//     echo "...<br>";
//     echo "...<br>";
//     var_dump(class_implements($action));
//     echo "...<br>";
//     echo "...<br>";
//     echo "...<br>";
//     echo "Interface: ". $interface;

//     return class_exists($action) && isset(class_implements($action)[$interface]);
// }

// echo "<br><br><br>";
// var_dump(check("sidGET","\\Massfice\\Application\\Actions\\","Massfice\Action\JsonAction"));

var_dump($factory->check("sidGET"));

?>