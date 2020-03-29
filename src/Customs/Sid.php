<?php

namespace Massfice\Application\Customs;

use Massfice\Loader\Load;
use Massfice\Loader\QueryLoader;

class Sid {
    public function get() {
        return Load::firstNotNull(new QueryLoader("sid"), new SidAPILoader());
    }
}

?>