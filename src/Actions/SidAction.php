<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\Customs\SidValidator;
use Massfice\Action\Standart\BadRequest;
use Massfice\Application\Customs\Sid;

abstract class SidAction implements \JsonAction {
    public function verify() : \VerifyStatus {
        $status = new \VerifyStatus();
        if(!SidValidator::validate(Sid::get())) {
            $status->setSubstitut(new BadRequest());
        }

        return $status;
    }

    abstract function load(array $data, array $config) : array;
    abstract function validate(array $data) : \ResponseStatus;
    abstract function execute(array $data) : array;
}

?>