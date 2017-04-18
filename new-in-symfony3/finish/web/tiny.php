<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../app/autoload.php';
require __DIR__.'/../app/LittleKernel.php';

$kernel = new LittleKernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
