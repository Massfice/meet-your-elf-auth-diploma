<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
use Massfice\Application\System\Views;
use Massfice\Application\System\Cleans;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\System\Services;
use Massfice\Application\System\JsonData;

class registerPOST implements \HtmlAction {
    private function getRegisterData(array $registerPOST) : array {
        $headers = getallheaders();
        $type = $headers["Content-Type"];

        $data = [];

        $username = $registerPOST["schema"]["username"]["field_name"];
        $password = $registerPOST["schema"]["password"]["field_name"];
        $repassword = $registerPOST["schema"]["repassword"]["field_name"];
        $firstName = $registerPOST["schema"]["firstName"]["field_name"];
        $lastName = $registerPOST["schema"]["lastName"]["field_name"];

        if($type != "application/json") {
            $success = $registerPOST["form"]["ExpectedStatusCode-Success"];
            $failure = $registerPOST["form"]["ExpectedStatusCode-Failure"];
            $data = [
                "username" => isset($_POST[$username]) ? $_POST[$username] : "",
                "password" => isset($_POST[$password]) ? $_POST[$password] : "",
                "repassword" => isset($_POST[$repassword]) ? $_POST[$repassword] : "",
                "firstName" => isset($_POST[$firstName]) ? $_POST[$firstName] : "",
                "lastName" => isset($_POST[$lastName]) ? $_POST[$lastName] : "",
                "success" => $success,
                "failure" => $failure
            ];
        } else {
            $success = $loginPOST["api"]["ExpectedStatusCode-Success"];
            $failure = $loginPOST["api"]["ExpectedStatusCode-Failure"];
            $data = [
                "username" => JsonData::get($username) !== null ? JsonData::get($username) : "",
                "password" => JsonData::get($password) !== null ? JsonData::get($password) : "",
                "repassword" => JsonData::get($repassword) !== null ? JsonData::get($repassword) : "",
                "firstName" => JsonData::get($firstName) !== null ? JsonData::get($firstName) : "",
                "firstName" => JsonData::get($lastName) !== null ? JsonData::get($lastName) : "",
                "success" => $success,
                "failure" => $failure
            ];
        }

        return $data;
    }

    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        $registerPOST = $config["registerPOST"];
        $return = $this->getRegisterData($registerPOST);
        return $return;
    }

    public function validate(array $data) : \ResponseStatus {
        $errors = [];

        if(empty($data["username"])) {
            $errors[] = "You have to insert username";
        }

        if(empty($data["password"])) {
            $errors[] = "You have to insert password";
        }

        if(empty($data["repassword"])) {
            $errors[] = "You have to repeat password";
        }

        if(empty($data["firstName"])) {
            $errors[] = "You have to insert name";
        }

        if(empty($data["lastName"])) {
            $errors[] = "You have to insert surname";
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
            $status = \ResponseStatusFactory::create($data["failure"]);
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

    public function onDisplay(array $data) {
        Views::generateView("register_success.tpl",$data);
    }

    public function onError(array $errors) {
        Views::generateView("errors.tpl",[
            "errors" => $errors
        ]);
    }
}

?>