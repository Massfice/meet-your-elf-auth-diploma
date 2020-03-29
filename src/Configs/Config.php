<?php

namespace Massfice\Application\Configs;

use Massfice\Application\Customs\Session\SessionSettings;

class Config {
    public static function configure() {
        SessionSettings::set();
    }
}

?>