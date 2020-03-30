<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
use Massfice\Application\System\Services;
use Massfice\Application\Customs\Session\Session;

require_once(Import::option("Massfice\\Action\\","ActionCreator"));

class SidDELETE extends SidAction {
    public function load(array $data, array $config) : array {
        return [
            "sid" => $config["sid"]
        ];
    }

    public function validate(array $data) : \ResponseStatus {
        return \ResponseStatusFactory::create(200);
    }

    public function execute(array $data) : array {
        $session = new Session($data["sid"]);
        $seed = $session->get("seed");

        if($seed != null) {
            Services::execute("OktaLogout",[
                "session" => $seed
            ]);
        }

        $session = $session->destroy();

        return [
            "Status" => "Success"
        ];
    }
}

?>