<?php
function pre($var, $next = FALSE)
{
    echo '<pre>';
    if (is_array($var)) {
        print_r($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';
    (!$next) ? die() : '';
}

function arrayMd5Hash($data)
{

    $x = "";

    foreach ($data as $c) {
        $x .= $c;
    }

    $hash = md5($x);

    return $hash;
}

function getEndValue($val)
{
    $splitColumnName = explode("_", $val);
    return end($splitColumnName);
}