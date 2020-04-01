<?php

namespace Massfice\AuthenticatorServices;

class LogoutService extends AbstractDeleteService {
    protected function getAction() : string {
        return "logout";
    }
}

?>