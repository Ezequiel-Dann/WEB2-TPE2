<?php
require_once "./libs/Router.php";
require_once "./app/controlador/APIControladorArtista.php";
require_once "./app/controlador/APIControladorAlbum.php";

define("BASE_URL", 'http://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].dirname($_SERVER["PHP_SELF"]).'/');


$router = new Router ();

$router -> addRoute('album/:ID' ,'DELETE', 'APIControladorAlbum', 'eliminarAlbum');
$router -> addRoute('album/:albunes','GET', 'APIControladorAlbum','albunesArtistas');




$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);
?>