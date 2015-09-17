<?php

use Base\Faq as BaseFaq;

class Faq extends BaseFaq {

    public function getCompositeKey() {
        return $this->getGroupId() . "." . $this->getIndex();
    }

}
