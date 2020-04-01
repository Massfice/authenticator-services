<?php

namespace Massfice\AuthenticatorServices;

class SidDeleteService extends AbstractDeleteService {
    protected function getAction() : string {
        return "sid";
    }
}

?>