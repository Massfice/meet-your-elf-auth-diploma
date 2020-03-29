<?php

namespace Massfice\Application\Services;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class OktaSession implements ServiceObject {
    public $session;

    public function url(array $data) : string {
        return "https://dev-444519.okta.com/api/v1/sessions";
    }

    public function data(array $data) : ?ServiceData {
        return $data["sessionToken"];
    }

    public function prepare(&$curl, array $data) : array {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

        return [];
    }

    public function callback(int $code, array $exec) {
        if($code != 401) {
            $this->session = $exec["id"];
        }
    }
}