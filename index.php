<?php
require 'vendor/autoload.php';

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();

// Load container definitions
$definitions = require 'configDependencias.php';
$definitions($containerBuilder);

// Build the container
$container = $containerBuilder->build();

// Obtener una instancia de Auth a través del contenedor
// $auth = $container->get(Auth::class); 

$userController = $container->get(UserController::class); 


// PRUEBAS
$username = 'maribel';
$password = 'maribel';
$ipAddress = $_SERVER['REMOTE_ADDR'];  // Dirección IP del usuario

// $message = $auth->login($username, $password, $ipAddress);

$message = $userController->login($username, $password, $ipAddress);
echo $message;


//como hubiese sido sin gestor de dependcias

// $dsn = 'mysql:host=localhost;dbname=pruebasautenticacion';
// $dbUser = 'root';
// $dbPassword = '';

// $db = Database::getInstance($dsn, $dbUser, $dbPassword )->getConnection();

// $userModel = new UserModel($db);
// $autModel = new AuthModel($db);

// $auth = new Auth($userModel, $autModel);

// $userC = new UserController($userModel, $auth);

// $message = $userC->login($username, $password, $ipAddress);
// echo $message;


?>