<?php

namespace Massfice\Application\Services;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class OktaLogin implements ServiceObject {
    public $sessionToken;
    private $status;
    private $code;

    public function url(array $data) : string {
        return "https://dev-444519.okta.com/api/v1/authn";
    }

    public function data(array $data) : ?ServiceData {
        return new class($data["username"],$data["password"]) implements ServiceData {
            public $username;
            public $login;
            public $options;

            public function __construct(string $username, string $password) {
                $this->username = $username;
                $this->password = $password;
                $this->options = new class() {
                    public $multiOptionalFactorEnroll;
                    public $warnBeforePasswordExpired;

                    public function __construct() {
                        $this->multiOptionalFactorEnroll = false;
                        $this->warnBeforePasswordExpired = false;
                    }
                };
            }
        };
    }

    public function prepare(&$curl, array $data) : array {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

        return [];
    }

    public function callback(int $code, array $exec) {
        @$this->sessionToken = $exec["sessionToken"];
        $this->code = $code;
        $this->status = isset($exec["status"]) ?
            $exec["status"] : 
            (isset($exec["errorSummary"]) ? $exec["errorSummary"] : "Authentication failed");
    }

    public function getCode() : int {
        return isset($this->code) ? $this->code : 401;
    }

    public function getStatus() : string {
        return isset($this->status) ? $this->status : "Authentication failed";
    }
}

?>