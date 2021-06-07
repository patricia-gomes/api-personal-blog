<?php
session_start();
define("BASE_URL", "http://localhost/api-personal-blog/");
//Configurando a permissão de acesso de outras aplicações ao webservice
header("Access-Control-Allow-Origin: *");
//Permissão para todos os métodos inclusive o PUT
header("Access-Control-Allow-Methods: *");

require 'vendor/autoload.php';//Puxa o autoload do Composer com PSR-4

$core = new Api\Core\Core();
$core->run();