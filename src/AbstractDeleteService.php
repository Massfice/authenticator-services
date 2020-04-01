<?php

namespace Massfice\AuthenticatorServices;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

abstract class AbstractDeleteService implements ServiceObject {
    public $isSuccess;
    public $code;

    public function url(array $data) : string {
        $action = $this->getAction();
        return AuthenticatorUrl::get($action);
    }

    public function prepare(&$curl, array $data) : array {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        return [];
    }

    public function data(array $data) : ?ServiceData {
        return new class($data["sid"]->sid) implements ServiceData {
            public $sid;

            public function __construct(string $sid) {
                $this->sid = $sid;
            }
        };
    }

    public function callback(int $code, array $exec) {
        $this->isSuccess = isset($exec["data"]["Status"]) && $exec["data"]["Status"] == "Success";
        $this->code = $code;
    }

    abstract protected function getAction() : string;
}

?>
