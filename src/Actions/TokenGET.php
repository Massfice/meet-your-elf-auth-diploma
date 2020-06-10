<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\Customs\TokenManager;

class TokenGET implements \JsonAction {
    private $data;

    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        return [
            "token" => TokenManager::get()
        ];
    }

    public function validate(array $data) : \ResponseStatus {
        if($data["token"] == null) {
            $status = \ResponseStatusFactory::create(400);
            $status->addError("No bearer token provided");
            return $status;
        }
        $this->data = TokenManager::read($data["token"]);

        if($this->data) {
            return \ResponseStatusFactory::create(200);
        } else {
            $status = \ResponseStatusFactory::create(401);
            $status->addError("Invalid token");
            return $status;
        }
    }

    public function execute(array $data) : array {
        unset($this->data["expire_in"]);
        return $this->data;
    }
}

?>