<?php
// Get All Books And Authors
$router->get('/', 'HomeController@index');
$router->post('/checkCsv', 'FileController@checkCsv');
$router->post('/percent', 'PercentController@store');
