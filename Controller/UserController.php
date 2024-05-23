<?php

// require_once('../Auth');
//require_once('../Interfaces/UserModelInterface');

class UserController{


    private $userModel;
    private $auth;


    public function __construct(UserModelInterface $userModel, Auth $auth) {
        $this->userModel = $userModel;
        $this->auth = $auth;
    }

    public function register($username, $password, $email) {
        $this->userModel->insertUser($username, $password, $email);
        // Redirigir o mostrar mensaje de éxito
    }


    public function login($username, $password, $ipAddress) {
       return  $this->auth->login($username, $password, $ipAddress);
        // Redirigir o mostrar mensaje de éxito
    }

    // Otros métodos para manejar solicitudes relacionadas con User
}

?>
