<?php

namespace Massfice\AuthenticatorServices;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class SidService implements ServiceObject {
    public $sid;

    public function url(array $data) : string {
        $sid = isset($this->sid) ? "/?sid=".$this->sid : "";
        return AuthenticatorUrl::get("sid").$sid;
    }

    public function prepare(&$curl, array $data) : array {
        return [];
    }   

    public function data(array $data) : ?ServiceData {
        return null;
    }

    public function callback(int $code, array $exec) {
        $this->sid = $exec["data"]["sid"];
    }
}

?>