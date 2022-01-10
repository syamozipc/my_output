<?php
namespace App\Libraries;

class Validator {

    public function isfilled($param)
    {
        return ($param !== "" && !is_null($param));
    }

    public function isNumeric($param)
    {
        return is_numeric($param);
    }

    public function isValidCountryId($param)
    {
        return (Counrty['min_id'] <= $param && $param <= Counrty['max_id']);
    }

    public function isString($param)
    {
        return is_string($param);
    }

    public function isValidLength($param, $length, $isMb = false)
    {
        if ($isMb) {
            return mb_strlen($param) <= $length;
        } else {
            return strlen($param) <= $length;
        }
    }
}