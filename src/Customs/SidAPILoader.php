<?php

namespace Massfice\Application\Customs;

use Massfice\Loader\Loader;
use Massfice\Application\System\JsonData;

class SidAPILoader implements Loader {
    public function load() {
        return JsonData::get("sid");
    }
}

?>