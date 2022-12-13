<?php
use System\MVC\Controller;


class HomeController extends Controller
{
    public function index()
    {
        require_once VIEW_ROOT . '/home.php';
    }
}