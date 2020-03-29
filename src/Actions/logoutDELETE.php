<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\System\Services;
use Massfice\Application\Customs\Session\Session;

class logoutDELETE extends SidAction {
    private $session;
    public function load(array $data, array $config) : array {
        $this->session = new Session($config["sid"]);
        return [
            "session" => $this->session->get("seed")
        ];
    }

    public function validate(array $data) : \ResponseStatus {
        if($data["session"] != null && $data["session"] != "-1") {
            return \ResponseStatusFactory::create(200);
        } else {
            $status = \ResponseStatusFactory::create(404);
            $status->addError("You have to be logged in, if you want to logout.");
            return $status;
        }
    }

    public function execute(array $data) : array {
        $service = Services::execute("OktaLogout",[
            "session" => $data["session"]
        ]);

        $this->session->set("seed","-1");

        return [
            "Status" => $service->code == 204 ? "Success" : "Failure"
        ];
    }
}

?>