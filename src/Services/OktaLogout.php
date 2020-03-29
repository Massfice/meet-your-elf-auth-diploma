<?php

namespace Massfice\Application\Services;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class OktaLogout implements Serviceobject {
    public $code;

    public function url(array $data) : string {
        return "https://dev-444519.okta.com/api/v1/sessions/".$data["session"];
    }

    public function data(array $data) : ?ServiceData {
        return null;
    }

    public function prepare(&$curl, array $data) : array {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        
        return [
            "Authorization: SSWS 00DiF9BRoS5No6BO9jJyVzPDdKyPannGtAFC2JOHY4"
        ];
    }

    public function callback(int $code, array $exec) {
        $this->code = $code;
    }
}

?>