<?php

namespace Massfice\Application\Services;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class OktaUserExist implements ServiceObject {
    public $exist;

    public function url(array $data) : string {
        $username = $data["username"] != "" ? $data["username"] : "-1";
        return "https://dev-444519.okta.com/api/v1/users/".$username;
    }

    public function data(array $data) : ?ServiceData {
        return null;
    }

    public function prepare(&$curl, array $data) : array {
        return [
            "Authorization: SSWS 00DiF9BRoS5No6BO9jJyVzPDdKyPannGtAFC2JOHY4"
        ];
    }

    public function callback(int $code, array $exec) {
        $this->exist = $code == 200;
    }
}

?>