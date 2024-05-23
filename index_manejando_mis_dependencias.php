<?php
require 'Database.php';
require 'Auth.php';
require 'models/UserModel.php';
require 'models/AuthModel.php';


// Configuración de la base de datos
$dsn = 'mysql:host=localhost;dbname=pruebasautenticacion';
$dbUser = 'root';
$dbPassword = '';

//Establecemos la conexion. Con un singleton
$db = Database::getInstance($dsn, $dbUser, $dbPassword )->getConnection();

//dependecia de User model y Auth model (inyeccion de dependencias)

$userModel = new UserModel($db);
$authModel = new AuthModel($db);

//Instanciamos aut para autenticar al usuario (le pasamos el PDO de la base de datos) y las dependencias que necesita la clase
$auth = new Auth($db, $userModel, $authModel);

//PRUEBAS
$username = 'Maribel';
$password = 'lopez';
$ipAddress = $_SERVER['REMOTE_ADDR'];  // Dirección IP del usuario

$message = $auth->login($username, $password, $ipAddress);
echo $message;


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $username = $_POST['username'];
//     $password = $_POST['password'];
//     $captchaResponse = $_POST['g-recaptcha-response'];  // Respuesta del CAPTCHA
//     $ipAddress = $_SERVER['REMOTE_ADDR'];  // Dirección IP del usuario

//     $message = $auth->login($username, $password, $ipAddress, $captchaResponse);
//     echo $message;
// }
