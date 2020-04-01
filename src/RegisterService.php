<?php

namespace Massfice\AuthenticatorServices;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;
use Massfice\Service\ServiceExecutor;

class RegisterService implements ServiceObject {
    private $registerDetails;
    public $isSuccess;
    public $code;
    public $errors;

    public function __construct() {
        $registerDetailsService = new RegisterDetailsService();
        $registerDetailsService = ServiceExecutor::execute($registerDetailsService);

        $this->registerDetails = $registerDetailsService;
    }

    public function url(array $data) : string {
        return $this->registerDetails->endpoint;
    }

    public function prepare(&$curl, array $data) : array {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->registerDetails->method);
        curl_setopt($curl, CURLOPT_AUTOREFERER, false);
        return [];
    }

    public function data(array $data) : ?ServiceData {
        return new class($this->registerDetails,$data) implements ServiceData {
            public function __construct(RegisterDetailsService $registerDetails, array $data) {
                $username_field = $registerDetails->username_field;
                $password_field = $registerDetails->password_field;
                $repassword_field = $registerDetails->repassword_field;
                $firstName_field = $registerDetails->firstName_field;
                $lastName_field = $registerDetails->lastName_field;

                $this->$username_field = $data["username"];
                $this->$password_field = $data["password"];
                $this->$repassword_field = $data["repassword"];
                $this->$firstName_field = $data["firstName"];
                $this->$lastName_field = $data["lastName"];
            }
        };
    }

    public function callback(int $code, array $exec) {
        $this->isSuccess = isset($exec["data"]["Status"]) && $exec["data"]["Status"] == "Success" && $code == $this->registerDetails->success_code && $code != $this->registerDetails->failure_code;
        $this->code = $code;
        $this->errors = isset($exec["errors"]) ? $exec["errors"] : [];
    }
}

?>