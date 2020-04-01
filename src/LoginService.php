<?php

namespace Massfice\AuthenticatorServices;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;
use Massfice\Service\ServiceExecutor;

class LoginService implements ServiceObject {
    private $loginDetails;
    public $isSuccess;
    public $code;
    public $errors;

    public function __construct() {
        $loginDetailsService = new LoginDetailsService();
        $loginDetailsService = ServiceExecutor::execute($loginDetailsService);

        $this->loginDetails = $loginDetailsService;
    }

    public function url(array $data) : string {
        return $this->loginDetails->endpoint;
    }

    public function prepare(&$curl, array $data) : array {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->loginDetails->method);
        curl_setopt($curl, CURLOPT_AUTOREFERER, false);
        return [];
    }

    public function data(array $data) : ?ServiceData {
        return new class($this->loginDetails,$data) implements ServiceData {
            public function __construct(LoginDetailsService $loginDetails, array $data) {
                $username_field = $loginDetails->username_field;
                $password_field = $loginDetails->password_field;
                $sid_field = $loginDetails->sid_field;

                $this->$username_field = $data["username"];
                $this->$password_field = $data["password"];
                $this->$sid_field = $data["sid"];
            }
        };
    }

    public function callback(int $code, array $exec) {
        $this->isSuccess = isset($exec["data"]["Status"]) && $exec["data"]["Status"] == "Success" && $code == $this->loginDetails->success_code && $code != $this->loginDetails->failure_code;
        $this->code = $code;
        $this->errors = isset($exec["errors"]) ? $exec["errors"] : [];
    }
}

?>