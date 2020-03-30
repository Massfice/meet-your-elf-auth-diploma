<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\ActionManager\BadRequest;
use Massfice\Application\Customs\SidValidator;
use Massfice\Application\Customs\Session\Session;

class SidGET implements \JsonAction {
    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        if(SidValidator::validate($config["sid"])) {
            $sid = $config["sid"];
        } else {
            $session = new Session();
            $sid = $session->getSid();
            $session->set("seed","-1");
        }

        return [
            "sid" => $sid,
        ];
    }

    public function validate(array $data) : \ResponseStatus {
        return \ResponseStatusFactory::create(200);
    }

    public function execute(array $data) : array {
        return $data;
    }
}

?>