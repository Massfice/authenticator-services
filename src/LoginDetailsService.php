<?php

namespace Massfice\AuthenticatorServices;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;

class LoginDetailsService implements ServiceObject {
    public $username_field;
    public $password_field;
    public $redirect_field;
    public $sid_field;
    public $endpoint;
    public $success_code;
    public $failure_code;
    public $method;

    public function url(array $data) : string {
        return AuthenticatorUrl::get()."/login/json";
    }

    public function prepare(&$curl, array $data) : array {
        return [];
    }

    public function data(array $data) : ?ServiceData {
        return null;
    }

    public function callback(int $code, array $exec) {
        $api = $exec["data"]["api"];
        $schema = $exec["data"]["schema"];

        $this->method = $exec["data"]["Method"];
        $this->username_field = $schema["username"]["field_name"];
        $this->password_field = $schema["password"]["field_name"];
        $this->redirect_field = $schema["redirect"]["field_name"];
        $this->sid_field = $schema["sid"]["field_name"];
        $this->endpoint = $api["Endpoint"];
        $this->success_code = $api["ExpectedStatusCode-Success"];
        $this->failure_code = $api["ExpectedStatusCode-Failure"];
    }
}

?>