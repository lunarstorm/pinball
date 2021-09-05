<?php

function print_pre($var, $return = null)
{
    if (app()->runningInConsole()) {
        return print_r($var, $return);
    }

    $s = "<pre>";
    $s .= print_r($var, true);
    $s .= "</pre>";

    if ($return == true) {
        return $s;
    }

    echo $s;
}

function pre_dump($var)
{
    if (app()->runningInConsole()) {
        var_dump($var);
        return;
    }

    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

if (!function_exists('pp')) {
    function pp(...$args)
    {
        foreach ($args as $arg) {
            print_pre($arg);
        }
    }
}

if (!function_exists('pd')) {
    function pd(...$args)
    {
        pp(...$args);
        die();
    }
}

if (!function_exists('vd')) {
    function vd(...$args)
    {
        foreach ($args as $arg) {
            pre_dump($arg);
        }
        die();
    }
}

if (!function_exists('render_php')) {
    function render_php($path, $params = [])
    {
        ob_start();
        extract($params, EXTR_SKIP);
        include($path);
        $ret = ob_get_contents();
        ob_end_clean();

        return $ret;
    }
}
