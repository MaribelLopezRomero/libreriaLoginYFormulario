<?php

require_once 'path/to/htmlpurifier/library/HTMLPurifier.auto.php';
session_start();

class Validator
{

    public static function validateUsername($username)
    {
        return preg_match('/^[a-zA-Z0-9_]{3,32}$/', $username);
    }

    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePassword($password)
    {
        return strlen($password) >= 8; // Mínimo 8 caracteres
    }

    public static function validateStrongPassword($password)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
    }
    public static function validatePostalCode($postalCode)
    {
        return preg_match('/^[0-9]{5}$/', $postalCode);
    }

    public static function validateURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function validatePhone($phone)
    {
        return preg_match('/^\+?[0-9]{10,15}$/', $phone);
    }

    public static function validateStringLength($string, $maxLength = 255)
    {
        return strlen($string) <= $maxLength;
    }

    //Para asegurar que el nombre completo solo contenga letras y espacios:


    public static function validateFullName($fullName)
    {
        return preg_match('/^[a-zA-Z\s]+$/', $fullName) && self::validateStringLength($fullName, 255);
    }

    public static function validateAddress($address)
    {
        return preg_match('/^[a-zA-Z0-9\s,.\-\/]+$/', $address) && self::validateStringLength($address, 255);
    }

    //asegurar que una cadena solo contenga caracteres alfanuméricos y algunos símbolos básicos
    public static function validateAlphaNumeric($string)
    {
        return preg_match('/^[a-zA-Z0-9\s]+$/', $string);
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    //inyecciones XSS y proteger los datos que se muestran en la interfaz de usuario:
    public static function sanitizeInput($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    //Para evitar inyección de HTML (XSS), además del escapado, podrías usar una librería como HTMLPurifier
    // public static function purifyHTML($htmlInput) {
    //     $config = HTMLPurifier_Config::createDefault();
    //     $purifier = new HTMLPurifier($config);
    //     return $purifier->purify($htmlInput);
    // }

    public static function validateJSON($jsonString)
    {
        json_decode($jsonString);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function validateIP($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    public static function validateInteger($value, $min = PHP_INT_MIN, $max = PHP_INT_MAX)
    {
        return filter_var($value, FILTER_VALIDATE_INT, array(
            'options' => array(
                'min_range' => $min,
                'max_range' => $max
            )
        )) !== false;
    }


    //Para protegerte contra ataques CSRF, asegúrate de validar tokens:

    public static function generateCsrfToken()
    {
        return bin2hex(random_bytes(32));
    }

    public static function validateCsrfToken($token)
    {
        return hash_equals($_SESSION['csrf_token'], $token);
    }


    
    //combinar varias funciones de validación
    public static function validateUserData($username, $password, $email, $phone)
    {
        return self::validateAlphaNumeric($username) &&
            self::validateStringLength($username) &&
            self::validatePassword($password) &&
            self::validateEmail($email) &&
            self::validatePhone($phone);
    }
}
