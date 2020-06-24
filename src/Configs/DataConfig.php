<?php

namespace Massfice\Application\Configs;

use Massfice\Application\Customs\OktaToken;

class DataConfig {
    private static function getAccessPost(string $type) : array {
        switch($type) {
            case "login":
                $endpoint = "https://meet-your-elf-auth.herokuapp.com/public/token/json";
                $expected_success = 200;
                $expected_failure = [
                    "badrequest" => 400,
                    "unauthorized" => 401
                ];
                $aditional_schema = []; 
            break;
            case "register":
                $endpoint = "https://meet-your-elf-auth.herokuapp.com/public/register/json";
                $expected_success = 203;
                $expected_failure = 400;
                $aditional_schema = [
                    "repassword",
                    "firstName",
                    "lastName"
                ];
            break;
        }

        $return = [
            "Content-Type" => "application/json",
            "Endpoint" => $endpoint,
            "ExpectedStatusCode-Success" => $expected_success,
            "ExpectedStatusCode-Failure" => [
                "application/json" => $expected_failure,
                "other" => 415
            ],
            "Method" => "POST",
            "schema" => [
                "username" => [
                    "field_name" => "username",
                    "required" => true,
                    "type" => "string"
                ],
                "password" => [
                    "field_name" => "password",
                    "required" => true,
                    "type" => "string"
                ]
            ]
        ];

        foreach($aditional_schema as $item) {
            $return["schema"][$item] = [
                "field_name" => $item,
                "required" => true,
                "type" => "string"
            ];
        }

        return $return; 
    }

    public static function getData() : array {
        $loginPOST = self::getAccessPost("login");
        $registerPOST = self::getAccessPost("register");

        return [
            "loginPOST" => $loginPOST,
            "registerPOST" => $registerPOST,
            "About" => [
                "author" => [
                    "name" => "Adrian",
                    "surname" => "Larysz",
                    "email" => "adrian.marian.tomasz.larysz@gmail.com"
                ],
                "shared_with" => [
                    "Another Dimension"
                ],
                "guthub_link" => "https://github.com/Massfice/meet-your-elf-auth-diploma"
            ]
        ];
    }
}

?>