<?php

namespace melkov\tools\helpers;

class PhoneHelper
{
    /**
     * @param $number
     *
     * @return mixed|null|string
     */
    public static function normalNumber($number)
    {
        $number = preg_replace('/[^0-9]/x', '', trim($number));

        if (strlen($number) == 10) {
            $number = '7' . $number;
        }

        if (strpos($number, '7') !== 0) {
            $number = '7' . substr($number, 1);
        }

        if (preg_match('/^[0-9]{11}$/', $number)/* && substr($number, 1, 1) == '9'*/) {
            return $number;
        }

        return null;
    }

    /**
	 * @deprecated 
     * @param string $number
     *
     * @return string
     */
    public static function asString($number)
    {
        return self::format($number);
    }
	
	/**
     * @param string $number
     * @param string $format
     * @param bool $international
     * @return string
     */
	public static function format($number, $format = "+x (xxx) xxx-xx-xx", $international = true)
    {
        if ($string = self::normalNumber($number)) {
            if (!$international) {
                $string{0} = "8";
            }
            $cnt = substr_count($format, "x");
            $len = $cnt < 11 ? $cnt : 11;
            $string = strrev($string);

            for ($i  = $len; $i > 0; $i--) {
                $format{strpos($format, "x")} = $string{$i - 1};
            }
            return $format;
        } else {
            return $number;
        }
    }
}