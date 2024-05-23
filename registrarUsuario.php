<?php


require 'Database.php';
require_once 'models/UserModel.php';

// Configuración de la base de datos
$dsn = 'mysql:host=localhost;dbname=pruebasautenticacion';
$dbUser = 'root';
$dbPassword = '';

//Establecemos la conexion
$db = Database::getInstance($dsn, $dbUser, $dbPassword )->getConnection();

//Instanciamos aut para autenticar al usuario (le pasamos el PDO de la base de datos)
$user = new UserModel($db);


//PRUEBAS
$username = 'Maribel';
$password = 'lopez';
$email = 'maribel.lopez@cop.es';
$ipAddress = $_SERVER['REMOTE_ADDR'];  // Dirección IP del usuario

$message = $user->insertUser($username, $password, $email);
echo $message;


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $username = $_POST['username'];
//     $password = $_POST['password'];
//     $captchaResponse = $_POST['g-recaptcha-response'];  // Respuesta del CAPTCHA
//     $ipAddress = $_SERVER['REMOTE_ADDR'];  // Dirección IP del usuario

//     $message = $auth->login($username, $password, $ipAddress, $captchaResponse);
//     echo $message;
// }

?>
