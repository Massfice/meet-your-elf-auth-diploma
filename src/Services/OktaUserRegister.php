<?php

namespace Massfice\Application\Services;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class OktaUserRegister implements ServiceObject {
    public $code;

    public function url(array $data) : string {
        return "https://dev-444519.okta.com/api/v1/users?activate=true";
    }

    public function data(array $data) : ?ServiceData {
        return new class($data["username"],$data["password"],$data["firstName"],$data["lastName"]) implements ServiceData {
            public $profile;
            public $credentials;
            public function __construct(string $username, string $password, string $firstName, string $lastName) {
                $this->profile = new class($firstName,$lastName,$username) {
                    public $firstName;
                    public $lastName;
                    public $login;
                    public $email;

                    public function __construct(string $firstName, string $lastName, string $username) {
                        $this->firstName = $firstName;
                        $this->lastName = $lastName;
                        $this->login = $username;  
                        $this->email = $username;               
                    }
                };

                $this->credentials = new class($password) {
                    public $password;

                    public function __construct(string $password) {
                        $this->password = $password;
                    }
                };
            }
        };
    }

    public function prepare(&$curl, array $data) : array {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

        return [
            "Authorization: SSWS 00Sn9xxWPmvmVZbDZNplbxLTmdHZOpNvYwyjvqhSBX"
        ];
    }

    public function callback(int $code, array $exec) {
        $this->code = $code;
    }
}

?>