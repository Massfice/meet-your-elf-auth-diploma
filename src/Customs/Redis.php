<?php

namespace Massfice\Application\Customs;

use Predis\Client;

class Redis {
    private static $instance;

    private $client;

    private function __construct() {
        $redis_url =
            "redis://h:pc7f67c6330794294f83d59553b71cedbe61723e7775b6e946dde97ff936d9bb4@ec2-108-128-144-158.eu-west-1.compute.amazonaws.com:10819";
        $this->client = new Client($redis_url);
    }

    public function __destruct() {
        $this->client->disconnect();
    }

    public static function getInstance() : self {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getClient() : Client {
        return $this->client;
    }
}

?>