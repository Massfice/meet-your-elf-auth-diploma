<?php

namespace Massfice\Application\Customs;

use Massfice\Application\System\Cleans;

class CorsConfig {
    private $options;

    public function __construct() {
        $this->options = [];
    }

    public function setOption(string $action, string $method, array $origins, array $headers) {
        $this->options[$action][$method] = [
            'origins' => $origins,
            'headers' => $headers
        ];
    }

    public function configure() {
        header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, HEAD");
        $headers = \getallheaders();
        if(!isset($headers["Origin"])) {
            return false;
        } else {
            $origin = $headers["Origin"];
        }

        $action = Cleans::getAction();
        $isOptions = $_SERVER["REQUEST_METHOD"] === "OPTIONS";

        if($isOptions) {
            $method = $headers["Access-Control-Request-Method"];
        } else {
            $method = $_SERVER["REQUEST_METHOD"];
        }

        $origins = $this->options[$action][$method]['origins'];
        $headers = $this->options[$action][$method]['headers'];

        if(\in_array($origin,$origins)) {
            header("Access-Control-Allow-Origin: ".$origin);
            
            $str = "";
            foreach($headers as $header) {
                $str = $str.$header.", ";
            }
            $str = rtrim($str, ", ");

            if($str !== "") {
                header("Access-Control-Allow-Headers: ".$str);
            };

        }

        if($isOptions) {
            die();
        }
    }
}

?>