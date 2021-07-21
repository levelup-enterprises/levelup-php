<?php

namespace  Utilities;

class Browser
{

    public static function isIE()
    {
        preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
        if (count($matches) < 2) {
            preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
        }

        if (count($matches) > 1) {
            //Then we're using IE
            return true;
        } else {
            return false;
        }
    }
}
