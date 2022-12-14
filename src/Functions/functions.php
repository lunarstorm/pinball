<?php

if (! defined('PINBALL_STUB_DIR')) {
    define('PINBALL_STUB_DIR', __DIR__.'/../../stubs');
}

if (! defined('IO_DATE_SHORT')) {
    define('IO_DATE_SHORT', 'M j/Y');
}

if (! defined('IO_DATE_LONG')) {
    define('IO_DATE_LONG', 'F jS, Y');
}

if (! defined('IO_DATE_FULL')) {
    define('IO_DATE_FULL', 'l, F jS, Y');
}

if (! defined('IO_DATE_FULL2')) {
    define('IO_DATE_FULL2', 'D, M jS, Y');
}

if (! defined('IO_DATE_DAY_MONTH')) {
    define('IO_DATE_DAY_MONTH', 'l, F j');
}

if (! defined('IO_DATE_SHORTTIME')) {
    define('IO_DATE_SHORTTIME', 'M j/Y h:i A');
}

if (! defined('IO_DATE_LONGTIME')) {
    define('IO_DATE_LONGTIME', 'F jS, Y h:i A');
}

if (! defined('IO_DATE_TIMEONLY')) {
    define('IO_DATE_TIMEONLY', 'g:i A');
}

if (! defined('IO_DATE_PICKER')) {
    define('IO_DATE_PICKER', 'm/d/Y');
}

if (! defined('IO_DATE_MYSQL')) {
    define('IO_DATE_MYSQL', 'Y-m-d H:i:s');
}

if (! function_exists('print_pre')) {
    function print_pre($var, $return = null)
    {
        if (app()->runningInConsole()) {
            return print_r($var, $return);
        }

        $s = '<pre>';
        $s .= print_r($var, true);
        $s .= '</pre>';

        if ($return == true) {
            return $s;
        }

        echo $s;
    }
}

if (! function_exists('pre_dump')) {
    function pre_dump($var)
    {
        if (app()->runningInConsole()) {
            var_dump($var);

            return;
        }

        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}

if (! function_exists('pp')) {
    function pp(...$args)
    {
        foreach ($args as $arg) {
            print_pre($arg);
        }
    }
}

if (! function_exists('pd')) {
    function pd(...$args)
    {
        pp(...$args);
        exit();
    }
}

if (! function_exists('vd')) {
    function vd(...$args)
    {
        foreach ($args as $arg) {
            pre_dump($arg);
        }
        exit();
    }
}

if (! function_exists('render_php')) {
    function render_php($path, $params = [])
    {
        ob_start();
        extract($params, EXTR_SKIP);
        include $path;
        $ret = ob_get_contents();
        ob_end_clean();

        return $ret;
    }
}
