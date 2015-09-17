<?php

use Base\User as BaseUser;

class User extends BaseUser {

    public function getName() {
        if (!is_null($this->getUserStudent())) {
            return $this->getUserStudent()->getLastname() . ' ' . $this->getUserStudent()->getFirstname();
        }

        if (!is_null($this->getUserPublisher())) {
            return $this->getUserPublisher()->getPublisher()->getName();
        }

        if (!is_null($this->getUserSecretariat())) {
            return $this->getUserSecretariat()->getLastname() . ' ' . $this->getUserSecretariat()->getFirstname();
        }

        throw new Exception();
    }

    public function getDeptId() {
        if (!is_null($this->getUserStudent())) {
            return $this->getUserStudent()->getDepartment()->getDeptId();
        }

        if (!is_null($this->getUserSecretariat())) {
            return $this->getUserSecretariat()->getDepartment()->getDeptId();
        }

        return -1;
    }

    public function getPublisherId() {
        if (!is_null($this->getUserPublisher())) {
            return $this->getUserPublisher()->getPublisher()->getPublisherId();
        }

        return -1;
    }

    public function getGroups() {
        $c = 0;

        if (!is_null($this->getUserStudent())) {
            $groups[$c] = $this->getUserStudent()->getGroup();
            $c++;
        }

        if (!is_null($this->getUserPublisher())) {
            $groups[$c] = $this->getUserPublisher()->getGroup();
            $c++;
        }

        if (!is_null($this->getUserSecretariat())) {
            $groups[$c] = $this->getUserSecretariat()->getGroup();
            $c++;
        }

        return $groups;
    }

}
