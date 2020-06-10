<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\System\Services;
use Massfice\Application\System\JsonData;

class RegisterPOST implements \JsonAction {
    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        $registerPOST = $config["registerPOST"];

        $username = $registerPOST["schema"]["username"]["field_name"];
        $password = $registerPOST["schema"]["password"]["field_name"];
        $repassword = $registerPOST["schema"]["repassword"]["field_name"];
        $firstName = $registerPOST["schema"]["firstName"]["field_name"];
        $lastName = $registerPOST["schema"]["lastName"]["field_name"];

        $success = $registerPOST["ExpectedStatusCode-Success"];
        $failure = $registerPOST["ExpectedStatusCode-Failure"];
        
        return [
            "username" => JsonData::get($username) !== null ? JsonData::get($username) : "",
            "password" => JsonData::get($password) !== null ? JsonData::get($password) : "",
            "repassword" => JsonData::get($repassword) !== null ? JsonData::get($repassword) : "",
            "firstName" => JsonData::get($firstName) !== null ? JsonData::get($firstName) : "",
            "lastName" => JsonData::get($lastName) !== null ? JsonData::get($lastName) : "",
            "success" => $success,
            "failure" => $failure,
            "schema" => [
                "username" => $username,
                "password" => $password,
                "repassword" => $repassword,
                "firstName" => $firstName,
                "lastName" => $lastName
            ]
        ];
    }

    public function validate(array $data) : \ResponseStatus {
        $errors = [];
        $type = \getallheaders()["Content-Type"];
        @$failure = $data["failure"][$type];

        if($failure == null) {
            $errors[] = "Unsupported Media Type";
            $failure = $data["failure"]["other"];
        }

        if(count($errors) == 0) {
            
            if(empty($data["username"])) {
                $errors[] = "You have to insert username ( key: ".$data["schema"]["username"]." )";
            }

            if(empty($data["password"])) {
                $errors[] = "You have to insert password ( key: ".$data["schema"]["password"]." )";
            }

            if(empty($data["repassword"])) {
                $errors[] = "You have to repeat password ( key: ".$data["schema"]["repassword"]." )";
            }

            if(empty($data["firstName"])) {
                $errors[] = "You have to insert name ( key: ".$data["schema"]["firstName"]." )";
            }

            if(empty($data["lastName"])) {
                $errors[] = "You have to insert surname ( key: ".$data["schema"]["lastName"]." )";
            }
        }

        if(count($errors) == 0) {
            if($data["password"] != $data["repassword"]) {
                $errors[] = "Passwords are not the same";
            } else {
                $regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{8,}$/m";
                if(preg_match($regex,$data["password"]) !== 1)  {
                    $errors[] = "Password has to contain at least one uppercase, lowercase letter, digit and at least 8 characters";
                }
            }

            if(!filter_var($data["username"], FILTER_VALIDATE_EMAIL) || strpos($data["username"], "/") !== false) {
                $errors[] = "Invalid username format (username should be email)";
            } else {
                $service = Services::execute("OktaUserExist",[
                    "username" => $data["username"]
                ]);

                if($service->exist) {
                    $errors[] = "Username is taken";
                }
            }

        }

        if(count($errors) == 0) {
            return \ResponseStatusFactory::create($data["success"]);
        } else {
            $status = \ResponseStatusFactory::create($failure);
            foreach($errors as $error) {
                $status->addError($error);
            }
            return $status;
        }
    }

    public function execute(array $data) : array {
        $service = Services::execute("OktaUserRegister",[
            "username" => $data["username"],
            "password" => $data["password"],
            "firstName" => $data["firstName"],
            "lastName" => $data["lastName"]
        ]);
        
        return [
            "Status" => $service->code == 200 ? "Success" : "Unexpected Failure",
            "Code" => $service->code
        ];
    }
}

?>