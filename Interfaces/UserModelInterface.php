<?php

interface UserModelInterface
{

    public function insertUser($username, $password, $email);
    public function getUser($username, $password);
    public function updateUserEmail($username, $email);
    public function deleteUser($username);
    public function blockAccount($username);
    public function getblockAccount($username);
}
