<?php

namespace Massfice\Application\Customs\Session;

class Session {
    private $sid;

    private function start() : string {
        @ini_set('session.use_cookies', 0);
        @session_start();
        @session_regenerate_id(false);
        $sid = session_id();

        return $sid;
    }

    private function resume(string $sid) : string {
        @session_id($sid);
        @session_start();

        return $sid;
    }

    public function __construct(?string $sid = null) {
        if($sid == null) {
            $this->sid = $this->start();
        } else {
            $this->sid = $this->resume($sid);
        }
    }

    public function getSid() : string {
        return $this->sid;
    }

    public function get(string $key, string $nullMode = "null") {
        switch($nullMode) {
            case "numeric":
                $null = 0;
            break;

            case "string":
                $null = "";
            break;

            case "null":
            default:
                $null = null;
            break;
        }

        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function set(string $key, $data) {
        $_SESSION[$key] = $data;
    }

    public function destroy() {
        $_SESSION = array();
        session_destroy();
        return null;
    }
}

?>