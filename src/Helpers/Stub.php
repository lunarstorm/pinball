<?php

namespace Vio\Pinball\Helpers;

class Stub
{
    public static function interpolate($stub, $values)
    {
        foreach ($values as $key => $value) {
            $searches = [
                "{{{$key}}}",
                "{{ {$key} }}",
            ];

            foreach ($searches as $search) {
                $stub = str_replace($search, $value, $stub);
            }
        }

        return $stub;
    }
}
