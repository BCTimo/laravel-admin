<?php

function base65($str){
    $str = base64_encode($str);
    $str = str_replace("+", "*", $str);
    $str = str_replace("/", "+", $str);
    $str = str_replace("*", "/", $str);

    return $str;
}

