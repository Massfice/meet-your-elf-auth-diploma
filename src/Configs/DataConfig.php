<?php

namespace Massfice\Application\Configs;

use Massfice\Application\Customs\Sid;

class DataConfig {
    private static function getLoginPost() : array {
        $login_endpoint = "https://meet-your-elf-auth.herokuapp.com/public/login/";
        $login_html_endpoint = $login_endpoint."html";
        $login_api_endpoint = $login_endpoint."json";

        return [
            "form" => [
                "Content-Type" => "application/x-www-form-urlencoded",
                "Endpoint" => $login_html_endpoint,
                "ExpectedStatusCode-Success" => 303,
                "ExpectedStatusCode-Failure" => 200
            ],
            "api" => [
                "Content-Type" => "application/json",
                "Endpoint" => $login_api_endpoint,
                "ExpectedStatusCode-Success" => 200,
                "ExpectedStatusCode-Failure" => 401
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
                ],
                "redirect" => [
                    "field_name" => "redirect",
                    "required" => [
                        "form" => true,
                        "api" => false
                    ],
                    "type" => "string"
                ],
                "sid" => [
                    "field_name" => "sid",
                    "required" => true,
                    "type" => "string"
                ]
            ]
        ];
    }

    private static function getRegisterPost() : array {
        $register_endpoint = "https://meet-your-elf-auth.herokuapp.com/public/register/";
        $register_html_endpoint = $register_endpoint."html";
        $register_api_endpoint = $register_endpoint."json";

        return [
            "form" => [
                "Content-Type" => "application/x-www-form-urlencoded",
                "Endpoint" => $register_html_endpoint,
                "ExpectedStatusCode-Success" => 203,
                "ExpectedStatusCode-Failure" => 200
            ],
            "api" => [
                "Content-Type" => "application/json",
                "Endpoint" => $register_api_endpoint,
                "ExpectedStatusCode-Success" => 203,
                "ExpectedStatusCode-Failure" => 400
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
                ],
                "repassword" => [
                    "field_name" => "repassword",
                    "required" => true,
                    "type" => "string"
                ],
                "firstName" => [
                    "field_name" => "firstName",
                    "required" => true,
                    "type" => "string"
                ],
                "lastName" => [
                    "field_name" => "lastName",
                    "required" => true,
                    "type" => "string"
                ]
            ]
        ];
    }

    public static function getData() : array {
        $loginPOST = self::getLoginPost();
        $login_html_endpoint = $loginPOST["form"]["Endpoint"]."/?redirect={redirect}&sid={sid}";
        $registerPOST = self::getRegisterPost();

        return [
            "login_root" => $login_html_endpoint,
            "loginPOST" => $loginPOST,
            "registerPOST" => $registerPOST,
            "sid" => Sid::get()
        ];
    }
}

?>