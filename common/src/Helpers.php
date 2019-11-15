<?php
namespace App;


class Helpers
{
    public static function estimate2dhmFormat($estimate) : string
    {
        $days  = intval($estimate / Globals::WORKING_DAY_IN_SECONDS);
        $hours = intval(($estimate - ($days * Globals::WORKING_DAY_IN_SECONDS)) / 3600);
        $min   = intval(($estimate - ($days * Globals::WORKING_DAY_IN_SECONDS) - ($hours * 3600))/60);

        $result = [];
        if ($days != 0) {
            $result[] = $days . 'д';
        }
        if ($hours != 0) {
            $result[] = $hours . 'ч';
        }
        if ($min != 0) {
            $result[] = $min . 'мин';
        }
        return implode(':', $result);
    }
}