<?php

namespace Massfice\Application\Customs;

class JsonActionFactory extends ActionFactory {

    protected function getInterfaceName() : string {
        return "Massfice\Action\JsonAction";
    }
}

?>