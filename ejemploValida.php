<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'];
    $username = Validator::sanitizeInput($_POST['username']);
    $password = Validator::sanitizeInput($_POST['password']);
    $email = Validator::sanitizeInput($_POST['email']);
    $phone = Validator::sanitizeInput($_POST['phone']);
    $postalCode = Validator::sanitizeInput($_POST['postal_code']);
    $fullName = Validator::sanitizeInput($_POST['full_name']);
    $address = Validator::sanitizeInput($_POST['address']);
    $jsonInput = $_POST['json_input'];

    if (Validator::validateCsrfToken($csrfToken) &&
        Validator::validateUserData($username, $password, $email, $phone) &&
        Validator::validatePostalCode($postalCode) &&
        Validator::validateFullName($fullName) &&
        Validator::validateAddress($address) &&
        Validator::validateJSON($jsonInput)) {

        $db = new Database($dsn, $dbUser, $dbPassword);
        $db->insertUser($username, $password, $email);
        echo "Usuario registrado exitosamente.";
    } else {
        echo "Error en los datos ingresados.";
    }
}


?>