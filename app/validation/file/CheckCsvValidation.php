<?php
namespace app\validation\file;


class CheckCsvValidation
{
    public $data;
    public $message;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function check()
    {
        if (empty($this->data->files) || !isset($this->data->files['document'])) {
            $this->message = 'File is required!';
            return false;
        } else {
            if ($this->data->files['document']['type'] !== 'text/csv') {
                $this->message = 'File should be CSV type!';
                return false;
            }
        }

        return true;
    }
}