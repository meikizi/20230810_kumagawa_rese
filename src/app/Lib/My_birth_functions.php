<?php

namespace App\Lib;

class My_birth_functions
{
    public static function yearSelect($selectYear, $startYear, $endYear)
    {
        for ($startYear; $startYear < $endYear; $startYear++) {
            echo '<option value="' . $startYear . '"';

            if ($startYear == $selectYear) echo ' selected';

            echo '>' . $startYear . '</option>';
        }
    }

    public static function monthSelect($selectMonth)
    {
        for ($m = 1; $m <= 12; $m++) {
            $spr = sprintf('%02d', $m); // 一桁(1～9)は0埋めして2桁にする
            echo '<option value="' . $spr . '"';

            if ($spr == $selectMonth) echo ' selected';

            echo '>' . $m . '</option>';
        }
    }

    public static function daySelect($selectDay)
    {
        for ($d = 1; $d <= 31; $d++) {
            $spr = sprintf('%02d', $d); // 一桁(1～9)は0埋めして2桁にする
            echo '<option value="' . $spr . '"';

            if ($spr == $selectDay) echo ' selected';

            echo '>' . $d . '</option>';
        }
    }
}
