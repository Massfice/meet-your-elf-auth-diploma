<?php

namespace Massfice\Application\Customs;

use Massfice\Action\Standart\NotFound;

abstract class ActionFactory {

    protected $namespace;
    protected $interface;

    public function __construct(string $namespace) {
        $this->namespace = $namespace;
        $this->interface = $this->getInterfaceName();

    }

    public function create(string $name) : JsonAction {
        $action = $this->namespace.ucfirst($name);
        echo $action."<br>";
        if($this->check($name)) {
            var_dump(new $name());
            return new $action();
        } else {
            var_dump(new NotFound());
            return new NotFound();
        }
    }

    public function check(string $name) : bool {
        $action = $this->namespace.ucfirst($name);
        return class_exists($action) && isset(class_implements($action)[$this->interface]);
    }

    abstract protected function getInterfaceName() : string;
}

?>