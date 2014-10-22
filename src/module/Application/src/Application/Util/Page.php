<?php
namespace Application\Util;

class Page
{
    public static function offset(Param $params, $default = 0)
    {
        $start = self::getFromParam('start', $params, $default);

        return self::getFromParam('offset', $params, $start);
    }

    public static function limit(Param $params, $default = 10)
    {
        $length = self::getFromParam('length', $params, $default);

        return self::getFromParam('limit', $params, $length);
    }

    protected static function getFromParam($key, Param $params, $defaultValue = 0)
    {
        $value = $params->get($key, $defaultValue);

        return intval(!is_numeric($value) ? $defaultValue : $value);
    }
}
