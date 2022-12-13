<?php

// load config and startup file
require 'config.php';
require SYSTEM . 'Startup.php';

// using
use System\Router\Router;

// create object of request and response class
$request = new System\Http\Request();
$response = new System\Http\Response();

// set default header
$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
$response->setHeader('Content-Type: application/json; charset=UTF-8');

// set request url and method

$router = new System\Router\Router('/' . $request->getUrl(), $request->getMethod());

// import router file
require 'Router/Router.php';

// Router Run Request
$router->run();

// Response Render Content
$response->render();
