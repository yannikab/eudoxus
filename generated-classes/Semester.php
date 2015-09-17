<?php

use Base\Semester as BaseSemester;

class Semester extends BaseSemester {

    function getName() {
        return $this->getPeriod() . 'ο Εξάμηνο';
    }

}
