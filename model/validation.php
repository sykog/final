<?php

    function validSignUp($string) {
        $clean = str_replace(" ", "", $string);
        if($string != $clean || strlen($clean) > 40) {
            return false;
        }
        else {
            return true;
        }
    }



