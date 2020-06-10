<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));


abstract class InfoAction implements \JsonAction {
    abstract protected function getInfoKey() : string;

    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        $cdata = $config[$this->getInfoKey()];
        return $cdata;
    }

    public function validate(array $data) : \ResponseStatus {
        $status = \ResponseStatusFactory::create(200);

        return $status;
    }

    public function execute(array $data) : array {
        return $data;
    }
}

?>