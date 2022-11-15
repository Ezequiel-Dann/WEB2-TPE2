<?php

require_once "./libs/Router.php";
require_once "./app/Controlador/APIControladorEquipo.php";
require_once "./app/controlador/APIControladorGrupo.php";
define("BASE_URL", 'http://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].dirname($_SERVER["PHP_SELF"]).'/');


$router = new Router();

$router->addRoute('equipos/', 'GET', 'APIControladorEquipo', 'obtenerEquipo');
$router->addRoute('equipos/:ID', 'GET', 'APIControladorEquipo', 'obtenerEquipo');
$router->addRoute('equipos/', 'POST', 'APIControladorEquipo', 'nuevoEquipo');
$router->addRoute('equipos/:ID', 'PUT', 'APIControladorEquipo', 'modificarEquipo');
$router->addRoute('equipos/:ID', 'DELETE', 'APIControladorEquipo', 'borrarEquipo');



$router->addRoute('grupos/', 'GET', 'APIControladorGrupo', 'obtenerGrupo');
$router->addRoute('grupos/:ID', 'GET', 'APIControladorGrupo', 'obtenerGrupo');

// rutea
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);