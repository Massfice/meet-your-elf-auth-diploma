<?php

namespace Massfice\Application\Services;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class OktaTokenDetails implements ServiceObject {
    public $name;
    public $surname;
    public $email;

    public function url(array $data) : string {
        return "https://dev-444519.okta.com/oauth2/default/v1/userinfo";
    }

    public function data(array $data) : ?ServiceData {
        return null;
    }

    public function prepare(&$curl, array $data) : array {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

        return [
            "Authorization: ".$data["token_type"]." ".$data["token"]
        ];
    }

    public function callback(int $code, array $exec) {
        $this->name = $exec["given_name"];
        $this->surname = $exec["family_name"];
        $this->email = $exec["preferred_username"];
    }
}

?>