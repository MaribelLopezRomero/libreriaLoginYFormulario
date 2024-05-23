<?php

class Database_auth {
    private $pdo;

    public function __construct($dsn, $username, $password) {
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insertUser($username, $password, $email) {
        $stmt = $this->pdo->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
        $stmt->execute([
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':email' => $email
        ]);
    }

    public function recordLoginAttempt($username, $ipAddress) {
        $stmt = $this->pdo->prepare('INSERT INTO login_attempts (username, ip_address, attempt_time) VALUES (:username, :ip_address, NOW())');
        $stmt->execute([
            ':username' => $username,
            ':ip_address' => $ipAddress
        ]);
    }

    public function countRecentLoginAttempts($username, $ipAddress, $timeFrame = '15 MINUTE') {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM login_attempts WHERE (username = :username OR ip_address = :ip_address) AND attempt_time > (NOW() - INTERVAL ' . $timeFrame . ')'
        );
        $stmt->execute([
            ':username' => $username,
            ':ip_address' => $ipAddress
        ]);
        return $stmt->fetchColumn();
    }


    public function countRecentTotalLoginAttempts($timeFrame = '15 MINUTE') {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM login_attempts WHERE attempt_time > (NOW() - INTERVAL ' . $timeFrame . ')'
        );
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function clearLoginAttempts($username, $ipAddress) {
        $stmt = $this->pdo->prepare('DELETE FROM login_attempts WHERE username = :username OR ip_address = :ip_address');
        $stmt->execute([
            ':username' => $username,
            ':ip_address' => $ipAddress
        ]);
    }

}


?>