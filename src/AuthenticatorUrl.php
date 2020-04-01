<?php

namespace Massfice\AuthenticatorServices;

class AuthenticatorUrl {
    public static function get(string $action) : string {
        return "https://meet-your-elf-auth.herokuapp.com/public/".$action."/json";
    }
}

?>