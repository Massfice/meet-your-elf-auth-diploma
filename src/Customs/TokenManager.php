<?php

namespace Massfice\Application\Customs;

use Firebase\JWT\JWT;
use Massfice\Loader\Load;
use Exception;

class TokenManager {
    public static function create(array $okta_array) : array {
        $tokenId    = base64_encode(random_bytes(32));
        $generalTokenId = base64_encode(random_bytes(32));
        $issuedAt   = time();
        $notBefore = $issuedAt;
        $expire = $notBefore + $okta_array["expire"];
        $issuer = "http://localhost/--%20DIPLOMA%20--/meet-your-elf-auth";

        $algorithm = Redis::getInstance()->getClient()->get("__algorithm__");

        $data = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $issuer,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'data' => $okta_array                  // Data related to the signer user
        ];

        $jwt = JWT::encode(
            $data,      //Data to be encoded in the JWT
            Load::firstNotNull(new RedisLoader($okta_array["email"]), new RedisCreatorLoader($okta_array["email"])), // The signing key
            $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        $generalData = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $generalTokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $issuer,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'data' => [
                "token" => $jwt,
                "email" => $okta_array["email"]
            ]                  // Data related to the signer user
        ];

        $jwt = JWT::encode(
            $generalData,      //Data to be encoded in the JWT
            Redis::getInstance()->getClient()->get("__general__"), // The signing key
            $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        return [
            "token" => $jwt
        ];
    }

    public static function read(string $token) : array {
        try {
            $algorithm = Redis::getInstance()->getClient()->get("__algorithm__");
            $decodedGeneralToken = JWT::decode($token,Redis::getInstance()->getClient()->get("__general__"),[
                $algorithm
            ]);
            $token = $decodedGeneralToken->data->token;

            $data = JWT::decode($token,Redis::getInstance()->getClient()->get($decodedGeneralToken->data->email),[
                $algorithm
            ]);
            
            $data = $data->data;
            return [
                "name" => $data->name,
                "surname" => $data->surname,
                "email" => $data->email,
                "expire_in" => $data->expire
            ];
        } catch(Exception $e) {
            return [];
        }
    }

    public static function get() : ?string {
        @$header = \getallheaders()["Authorization"];
        if($header == null) {
            return $header;
        }

        if(substr($header, 0, 7) !== 'Bearer ') {
            return null;
        }

        return trim(substr($header, 7));
    }
}

?>