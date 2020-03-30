<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\System\Services;
use Massfice\Application\Customs\Session\Session;

class AuthGET extends SidAction {
    public function load(array $data, array $config) : array {
        $session = new Session($config["sid"]);
        $seed = $session->get("seed");
        if($seed == null) {
            $seed = "-1";
        }

        $service = Services::execute("OktaDetails",[
            "session" => $seed
        ]);

        $valid = $service->getCode() != 404;

        if($valid) {
            $details = $service;
        } else {
            $details = [
                "redirectTo" => $config["login_root"],
                "redirectMethod" => "GET"
            ];
        }
        
        return [
            "auth" => $valid,
            "details" => $details,
            "seed" => $seed,
            "code" => $service->getCode()
        ];
    }

    public function validate(array $data) : \ResponseStatus {
        if($data["auth"]) {
            $response = \ResponseStatusFactory::create(200);
            $response->setCache(time()+60,"public");
        } else {
            $response = \ResponseStatusFactory::create(401);
        }

        return $response;
    }

    public function execute(array $data) : array {
        return $data;
    }
}

?>