<?php

namespace System\MVC;


class Model
{
    public function __construct()
    {
        try {
            $this->pdo = new \PDO("mysql:host=" . DATABASE['Host'] . ";port=" . DATABASE['Port'] . ";dbname=" . DATABASE['Name'], DATABASE['User'], DATABASE['Pass']);
        } catch (\PDOException $e) {
            trigger_error('Error: Could not make a database link ( ' . $e->getMessage() . '). Error Code : ' . $e->getCode() . ' <br />');
            exit();
        }
    }

    public function getLastId()
    {
        return $this->pdo->lastInsertId();
    }
}
