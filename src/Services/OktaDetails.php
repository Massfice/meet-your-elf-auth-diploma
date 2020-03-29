<?php

namespace Massfice\Application\Services;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class OktaDetails implements ServiceObject {
    public $name;
    public $email;
    public $userId;
    public $expire;
    private $code;

    public function url(array $data) : string {
        $session = $data["session"] != "" ? $data["session"] : "-1";
        return "https://dev-444519.okta.com/api/v1/sessions/".$session;
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
        $this->code = $code;
        if($code != 404) {
            $this->name = $exec["_links"]["user"]["name"];
            $this->email = $exec["login"];
            $this->userId = $exec["userId"];
            $this->expire = $exec["expiresAt"];
        }
    }

    public function getCode() : int {
        return $this->code;
    }
}

?>