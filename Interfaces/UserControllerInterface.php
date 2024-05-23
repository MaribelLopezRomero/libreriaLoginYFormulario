<?php



interface UserControllerInterface
{

    public function register($username, $password, $email);

    public function login($username, $password, $ipAddress);
}
