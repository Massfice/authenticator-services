<?php

namespace Massfice\AuthenticatorServices;

use Massfice\Service\ServiceObject;
use Massfice\Service\ServiceData;
use Massfice\Service\ServiceExecutor;

class LoginService implements ServiceObject {
    private $loginDetails;
    public $login;
    public $code;

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
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        return [];
    }

    public function data(array $data) : ?ServiceData {
        return new class($this->loginDetails,$data) implements ServiceData {
            public function __construct(LoginDetailsService $loginDetails, array $data) {
                $username_field = $loginDetails->username_field;
                $password_field = $loginDetails->password_field;
                $sid_field = $loginDetails->sid_field;
                $redirect_field = $loginDetails->redirect_field;

                $this->$username_field = $data["username"];
                $this->$password_field = $data["password"];
                $this->$sid_field = $data["sid"];
                $this->$redirect_field = "false";
            }
        };
    }

    public function callback(int $code, array $exec) {
        // $api = $exec["data"]["api"];
        // $schema = $exec["data"]["schema"];

        // $this->method = $exec["data"]["Method"];
        // $this->username_field = $schema["username"]["field_name"];
        // $this->password_field = $schema["password"]["field_name"];
        // $this->redirect_field = $schema["redirect"]["field_name"];
        // $this->sid_field = $schema["sid"]["field_name"];
        // $this->endpoint = $api["Endpoint"];
        // $this->success_code = $api["ExpectedStatusCode-Success"];
        // $this->failure_code = $api["ExpectedStatusCode-Failure"];
        $this->login = $exec;
        $this->code = $code;
    }
}

?>