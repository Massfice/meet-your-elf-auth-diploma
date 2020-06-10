<?php

namespace Massfice\Application\Customs;

use Massfice\Application\System\Services;

class OktaToken {
    public static function get(string $username, string $password) : array {
        $query = http_build_query([
            "client_id" => "0oacsn74vCLiS3yNZ4x6",
            "grant_type" => "password",
            "username" => $username,
            "password" => $password,
            "scope" => "openid+profile"
        ]);

        $query = str_replace("%40","@",$query);
        $query = str_replace("%2B","+",$query);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://dev-444519.okta.com/oauth2/default/v1/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        
        $data = \json_decode($server_output, true);
        $valid = !isset($data["error"]) && isset($data["token_type"]) && isset($data["access_token"]);

        $return = [];
        if($valid) {
            $service = Services::execute("OktaTokenDetails",[
                "token_type" => $data["token_type"],
                "token" => $data["access_token"]
            ]);
            $return = [
                "name" => $service->name,
                "surname" => $service->surname,
                "email" => $service->email,
                "expire" => $data["expires_in"]
            ];
        } else {
            $return = [];
        }

        return $return;
    }

}

?>