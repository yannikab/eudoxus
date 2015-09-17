<?php

use Base\Book as BaseBook;

class Book extends BaseBook {

    function getCoverBase64() {
        return base64_encode(stream_get_contents($this->getCover()));
    }

}
