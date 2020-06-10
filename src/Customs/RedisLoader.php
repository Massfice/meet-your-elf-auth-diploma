<?php

namespace Massfice\Application\Customs;

use Massfice\Loader\KeyLoader;

class RedisLoader extends KeyLoader{
    public function load() {
        return Redis::getInstance()->getClient()->get($this->key);
    }
}

?>