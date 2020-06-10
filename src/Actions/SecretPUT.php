<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\Customs\TokenManager;
use Massfice\Application\Customs\RedisCreatorLoader;
use Massfice\Application\System\Cleans;

class SecretPUT implements \JsonAction {
    private $data;
    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        $uri_param_zero = Cleans::get(0);
        $refresh = isset($uri_param_zero) && $uri_param_zero == "refresh";
        return [
            "token" => TokenManager::get(),
            "refresh" => $refresh 
        ];
    }

    public function validate(array $data) : \ResponseStatus {
        if($data["token"] == null) {
            $status = \ResponseStatusFactory::create(400);
            $status->addError("No bearer token provided");
            return $status;
        }
        $this->data = TokenManager::read($data["token"]);

        if(isset($this->data["email"])) {
            return \ResponseStatusFactory::create(200);
        } else {
            $status = \ResponseStatusFactory::create(401);
            $status->addError("Invalid token");
            return $status;
        }
    }

    public function execute(array $data) : array {
        $status = "Ok";
        $shouldRefresh = true;
        try {
            $loader = new RedisCreatorLoader($this->data["email"]);
            $loader->load();
        } catch(\Exception $e) {
            $status = "Unexpected error";
            $shouldRefresh = false;
        }

        if($data["refresh"] && $shouldRefresh) {
            $refreshed_token = TokenManager::create([
                "email" => $this->data["email"],
                "name" => $this->data["name"],
                "surname" => $this->data["surname"],
                "expire" => $this->data["expire_in"]
            ])["token"];
        } else {
            $refreshed_token = null;
        }

        return [
            "status" => $status,
            "refreshed_token" => $refreshed_token
        ];
    }
}

?>