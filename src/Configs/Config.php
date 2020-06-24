<?php

namespace Massfice\Application\Configs;

use Massfice\Application\Customs\CorsConfig;

class Config {
    public static function configure() {
        $allowed_origins = [
            "http://localhost:3000",
            "http://localhost:8000",
            "http://localhost:8080",
            "http://localhost:80",
            "http://localhost"
        ];
        $allowed_headers = [
            "content-type",
            "authorization"
        ];

        $cors_config = new CorsConfig();

        $cors_config->setOption("token","POST",$allowed_origins,$allowed_headers);
        $cors_config->setOption("token","GET",$allowed_origins,$allowed_headers);
        $cors_config->setOption("register","POST",$allowed_origins,$allowed_headers);
        $cors_config->setOption("secret","PUT",$allowed_origins,$allowed_headers);
        
        $cors_config->configure();
    }
}

?>