<?php

namespace Massfice\Application\Customs;

use Massfice\Action\Standart\NotFound;
use Massfice\Action\JsonAction;

abstract class ActionFactory {

    protected $namespace;
    protected $interface;

    public function __construct(string $namespace) {
        $this->namespace = $namespace;
        $this->interface = $this->getInterfaceName();

    }

    public function create(string $name) : JsonAction {
        $action = $this->namespace.$name;
        if($this->check($name)) {
            return new $action();
        } else {
            return new NotFound();
        }
    }

    public function check(string $name) : bool {
        echo "<br>--------------------------------------<br>";
        $action = $this->namespace.$name;
        echo "<br>--------------------------------------<br>";
        echo "Action: ";
        var_dump($action);
        echo "<br>--------------------------------------<br>";
        echo "Class exists: "; var_dump(class_exists($action));
        echo "<br>--------------------------------------<br>";
        echo "Interfaces: "; var_dump(class_implements($action));
        echo "<br>--------------------------------------<br>";
        echo "Interface isset: "; var_dump(isset(class_implements($action)[$this->interface]));
        echo "<br>--------------------------------------<br>";
        echo "<br>--------------------------------------<br>";
        echo "<br><br><br><br>";

        return class_exists($action) && isset(class_implements($action)[$this->interface]);
    }

    abstract protected function getInterfaceName() : string;
}

?>