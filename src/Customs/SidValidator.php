<?php

namespace Massfice\Application\Customs;

class SidValidator {
    public static function validate(?string $sid) {
        return file_exists(__DIR__."/Session/data/sess_".$sid);
    }
}

?>