<?php
namespace app\validation\percent;

class StoreValidation
{
    public $data;
    public $message;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function check()
    {
        if ((int) $this->data['division'] +  (int) $this->data['age'] + (int) $this->data['timezone'] !== 100) {
            $this->message ='Total sum is not 100';
            return false;
        }

        return true;
    }
}