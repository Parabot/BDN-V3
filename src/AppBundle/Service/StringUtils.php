<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

class StringUtils {
    public static function generateRandomString($length = 15, $specials = true) {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . ($specials === true ? '!@#$%^&*()?<>,.;:' : '');
        $charactersLength = strlen($characters);
        $randomString     = '';
        for($i = 0; $i < $length; $i++) {
            $randomString .= $characters[ rand(0, $charactersLength - 1) ];
        }

        return $randomString;
    }
}