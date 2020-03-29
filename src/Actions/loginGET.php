<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
use Massfice\Application\System\Views;
use Massfice\Application\System\Cleans;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));

use Massfice\Application\Customs\Session\Session;

class loginGET implements \HtmlAction {
    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        $cdata = $config["loginPOST"];
        @$cdata["schema"]["redirect"]["value"] = $_GET["redirect"];
        @$cdata["schema"]["sid"]["value"] = $_GET["sid"];
        return $cdata;
    }

    public function validate(array $data) : \ResponseStatus {
        $status = \ResponseStatusFactory::create(200);
        $type = Cleans::getType();

        if($type == "html" && $data["schema"]["redirect"]["value"] == null) {
            $status->addError("You have to set redirect value.");
        }
        
        if($type == "html" && $data["schema"]["sid"]["value"] == null) {
            $status->addError("You have to set sid value.");
        }

        return $status;
    }

    public function execute(array $data) : array {
        return $data;
    }

    public function onDisplay(array $data) {
        Views::generateView("login_form.tpl",[
            "type" => $data["form"]["Content-Type"],
            "endpoint" => $data["form"]["Endpoint"],
            "method" => $data["Method"],
            "username" => $data["schema"]["username"]["field_name"],
            "password" => $data["schema"]["password"]["field_name"],
            "redirect" => $data["schema"]["redirect"]["field_name"],
            "redirect_value" => $data["schema"]["redirect"]["value"],
            "sid" => $data["schema"]["sid"]["field_name"],
            "sid_value" => $data["schema"]["sid"]["value"]
        ]);
    }

    public function onError(array $errors) {
        Views::generateView("errors.tpl",[
            "errors" => $errors
        ]);
    }
}

?>