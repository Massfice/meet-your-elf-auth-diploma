<?php

namespace Massfice\Action;

class JsonActionFactory extends ActionFactory {

    protected function getInterfaceName() : string {
        return "Massfice\Action\JsonAction";
    }
}

?>