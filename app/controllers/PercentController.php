<?php

use app\models\Percent;
use app\validation\percent\StoreValidation;
use System\MVC\Controller;

class PercentController extends Controller
{
    public function store(): void
    {
        try {
            $validation = new StoreValidation($_POST);
            if ($validation->check()) {
                $percent = new Percent;
                $percent->create($_POST);

                echo json_encode([
                    "error" => false,
                    "message" => 'Success!',
                ]);
            } else {
                throw new Exception($validation->message);
            }
        } catch (Exception $e) {
            echo json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }

    public static function getEmployersScore($emp_one, $emp_two): int
    {
        $divisionScore = self::getDivisionScore($emp_one['Division'], $emp_two['Division']);
        $ageScore = self::getAgeScore($emp_one['Age'], $emp_two['Age']);
        $timezoneScore = self::getTimezoneScore($emp_one['Timezone'], $emp_two['Timezone']);

        return $divisionScore + $ageScore + $timezoneScore;
    }

    public static function getDivisionScore($divisionEmployeeOne, $divisionEmployeeTwo): int
    {
        $percent = new Percent;
        $divisionPercent = $percent->get('division');

        return $divisionEmployeeOne === $divisionEmployeeTwo ? (int) $divisionPercent : 0;
    }

    public static function getAgeScore($ageEmployeeOne, $ageEmployeeTwo): int
    {
        $percent = new Percent;
        $agePercent = $percent->get('age');

        return abs($ageEmployeeOne - $ageEmployeeTwo) <= 5 ? (int) $agePercent : 0;
    }

    public static function getTimezoneScore($timezoneEmployeeOne, $timezoneEmployeeTwo): int
    {
        $percent = new Percent;
        $timezonePercent = $percent->get('timezone');

        return $timezoneEmployeeOne === $timezoneEmployeeTwo ? (int) $timezonePercent : 0;
    }
}