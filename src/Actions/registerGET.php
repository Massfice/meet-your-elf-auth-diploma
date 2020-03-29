<?php

namespace Massfice\Application\Actions;

use Massfice\Smart\Import;
use Massfice\Application\System\Views;
use Massfice\Application\System\Cleans;
require_once(Import::option("Massfice\\Action\\","ActionCreator"));

class registerGET implements \HtmlAction {
    public function verify() : \VerifyStatus {
        return new \VerifyStatus();
    }

    public function load(array $data, array $config) : array {
        $cdata = $config["registerPOST"];
        return $cdata;
    }

    public function validate(array $data) : \ResponseStatus {
        $status = \ResponseStatusFactory::create(200);

        return $status;
    }

    public function execute(array $data) : array {
        return $data;
    }

    public function onDisplay(array $data) {
        Views::generateView("register_form.tpl",[
            "type" => $data["form"]["Content-Type"],
            "endpoint" => $data["form"]["Endpoint"],
            "method" => $data["Method"],
            "username" => $data["schema"]["username"]["field_name"],
            "password" => $data["schema"]["password"]["field_name"],
            "repassword" => $data["schema"]["repassword"]["field_name"],
            "firstName" => $data["schema"]["firstName"]["field_name"],
            "lastName" => $data["schema"]["lastName"]["field_name"]
        ]);
    }

    public function onError(array $errors) {
        Views::generateView("errors.tpl",[
            "errors" => $errors
        ]);
    }
}

?>