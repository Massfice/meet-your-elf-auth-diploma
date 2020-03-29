<?php

namespace Massfice\Application\Customs\Session;

class SessionSettings {
    public static function set() {
        session_name("authenticator-session");
        session_save_path(__DIR__."/data");
    }
}

?>