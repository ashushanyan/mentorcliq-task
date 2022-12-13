<?php

namespace app\models;

use Exception;
use System\MVC\Model;

class Percent extends Model
{
    public $table = 'percents';

    public function create($data): void
    {
        $createPercent = $this->pdo->prepare("INSERT INTO `$this->table` (division,age,timezone) 
                                                        VALUES(:division,:age,:timezone);");
        $createPercent->execute(array(
            ':division' => $data['division'] ?? 0,
            ':age' => $data['age'] ?? 0,
            ':timezone' => $data['timezone'] ?? 0,
        ));

        if (empty($this->getLastId())) {
            throw new Exception('Something went wrong!');
        }
    }

    public function get($column): string
    {
        $query = $this->pdo->prepare("SELECT `$column` FROM `$this->table` ORDER BY created_at DESC ");
        $query->execute();
        $res = $query->fetch(\PDO::FETCH_ASSOC);

        return  $res[$column];
    }
}