<?php

namespace Massfice\Application\Customs;

use Massfice\Loader\KeyLoader;

class RedisCreatorLoader extends KeyLoader {
    public function load() {
        $value = "=".base64_encode(random_bytes(42))."+";

        Redis::getInstance()->getClient()->set($this->key, $value);

        return $value;
    }
}

?>