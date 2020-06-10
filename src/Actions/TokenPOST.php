<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\Customs\OktaToken;
use Massfice\Application\Customs\TokenManager;
use Massfice\Application\System\JsonData;

class TokenPOST implements \JsonAction {
    private $okta_array;

    public function __construct() {
        $this->okta_array = null;
    }

    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        $loginPOST = $config["loginPOST"];

        $username = $loginPOST["schema"]["username"]["field_name"];
        $password = $loginPOST["schema"]["password"]["field_name"];

        $success = $loginPOST["ExpectedStatusCode-Success"];
        $failure = $loginPOST["ExpectedStatusCode-Failure"];
        
        return [
            "username" => JsonData::get($username) !== null ? JsonData::get($username) : "",
            "password" => JsonData::get($password) !== null ? JsonData::get($password) : "",
            "success" => $success,
            "failure" => $failure,
            "schema" => [
                "username" => $username,
                "password" => $password
            ]
        ];
    }

    public function validate(array $data) : \ResponseStatus {
        $errors = [];
        $type = \getallheaders()["Content-Type"];
        @$failure = $data["failure"][$type];
        $failure_type = false;

        if($failure == null) {
            $errors[] = "Unsupported Media Type";
            $failure = $data["failure"]["other"];
        }

        if(count($errors) == 0) {   
            $failure_type = "badrequest";
            if(empty($data["username"])) {
                $errors[] = "You have to insert username ( key: ".$data["schema"]["username"]." )";
            }

            if(empty($data["password"])) {
                $errors[] = "You have to insert password ( key: ".$data["schema"]["password"]." )";
            }

            if(count($errors) == 0) {
                $this->okta_array = OktaToken::get($data["username"], $data["password"]);
                
                if(!$this->okta_array) {
                    $errors[] = "Invalid credentials";
                    $failure_type = "unauthorized";
                }
            }
        }

        if(count($errors) == 0) {
            return \ResponseStatusFactory::create($data["success"]);
        } else {
            if($failure_type) {
                $failure = $failure[$failure_type];
            }
            $status = \ResponseStatusFactory::create($failure);
            foreach($errors as $error) {
                $status->addError($error);
            }
            return $status;
        }
    }

    public function execute(array $data) : array {
        return TokenManager::create($this->okta_array);
    }
}

?>