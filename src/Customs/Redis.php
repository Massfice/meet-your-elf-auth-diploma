<?php

namespace Massfice\Application\Customs;

use Predis\Client;

class Redis {
    private static $instance;

    private $client;

    private function __construct() {
        $this->client = new Client([
            'host' => 'ec2-52-48-169-213.eu-west-1.compute.amazonaws.com',
            'port' => 13789,
            'password' => 'pc7f67c6330794294f83d59553b71cedbe61723e7775b6e946dde97ff936d9bb4',
            'timeout' => 300
        ]);
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