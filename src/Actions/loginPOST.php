<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));
use Massfice\Application\System\Views;
use Massfice\Application\System\Services;
use Massfice\Application\System\JsonData;
use Massfice\Application\Customs\Session\Session;

class LoginPOST extends SidAction implements \HtmlAction {
    private $seed;
    private $session;

    private function getLoginData(array $loginPOST) : array {
        $headers = getallheaders();
        $type = $headers["Content-Type"];

        $data = [];

        $username = $loginPOST["schema"]["username"]["field_name"];
        $password = $loginPOST["schema"]["password"]["field_name"];
        $redirect = $loginPOST["schema"]["redirect"]["field_name"];
        $sid = $loginPOST["schema"]["sid"]["field_name"];

        if($type != "application/json") {
            $success = $loginPOST["form"]["ExpectedStatusCode-Success"];
            $failure = $loginPOST["form"]["ExpectedStatusCode-Failure"];
            $data = [
                "username" => isset($_POST[$username]) ? $_POST[$username] : "",
                "password" => isset($_POST[$password]) ? $_POST[$password] : "",
                "redirect" => isset($_POST[$redirect]) ? $_POST[$redirect] : "",
                "sid" => isset($_POST[$sid]) ? $_POST[$sid] : "",
                "success" => $success,
                "failure" => $failure
            ];
        } else {
            $success = $loginPOST["api"]["ExpectedStatusCode-Success"];
            $failure = $loginPOST["api"]["ExpectedStatusCode-Failure"];
            $data = [
                "username" => JsonData::get($username) !== null ? JsonData::get($username) : "",
                "password" => JsonData::get($password) !== null ? JsonData::get($password) : "",
                "redirect" => JsonData::get($redirect) !== null ? JsonData::get($redirect) : "",
                "sid" => JsonData::get($sid) !== null ? JsonData::get($sid) : "",
                "success" => $success,
                "failure" => $failure
            ];
        }

        $data["type"] = $type;
        return $data;
    }

    public function load(array $data, array $config) : array {
        $loginPOST = $config["loginPOST"];
        $return = $this->getLoginData($loginPOST);
        return $return;
    }

    public function validate(array $data) : \ResponseStatus {
        $this->session = new Session($data["sid"]);

        $seed = $session->get("seed");

        if($seed == "-1") {
            $status = \ResponseStatusFactory::create(400);
            $status->addError("You can't login, if you are logged it already");
            return $status;
        }

        $errors = [];

        if(empty($data["username"])) {
            $errors[] = "You have to insert username";
        }

        if(empty($data["password"])) {
            $errors[] = "You have to insert password";
        }

        if((empty($data["redirect"]) && $data["type"] != "application/json") || empty($data["sid"])) {
            $errors[] = "Redirect or sid missing";
        }

        if(count($errors) == 0) {
            $service = Services::execute("OktaLogin",[
                "username" => $data["username"],
                "password" => $data["password"]
            ]);

            $service = Services::execute("OktaSession",[
                "sessionToken" => $service
            ]);

            if($service->session != null) {
                $status = \ResponseStatusFactory::create($data["success"]);
                if($data["type"] != "application/json") {
                    $status->setLocation($data["redirect"]);
                }
                $this->seed = $service->session;
            } else {
                $status = \ResponseStatusFactory::create($data["failure"]);
                $status->addError("Ivalid credentials");
            }
        } else {
            $status = \ResponseStatusFactory::create($data["failure"]);
            foreach($errors as $error) {
                $status->addError($error);
            }
        }
        
        return $status;
    }

    public function execute(array $data) : array {
        $this->session->set("seed",$this->seed);
        return [
            "Status" => "Ok"
        ];
    }

    public function onDisplay(array $data) {

    }
    
    public function onError(array $errors) {
        Views::generateView("errors.tpl",[
            "errors" => $errors
        ]);
    }

}

?>