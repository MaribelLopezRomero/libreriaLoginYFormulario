<?php

class Database {
    private static $instance = null;
    private $pdo;

    // Hacemos el constructor privado para evitar la creación directa de instancias
    private function __construct($dsn, $username, $password) {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Manejo de errores de conexión
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    // Método estático para obtener la instancia única
    public static function getInstance($dsn, $username, $password) {
        if (self::$instance === null) {
            self::$instance = new self($dsn, $username, $password);
        }
        return self::$instance;
    }

    // Método para obtener la conexión PDO
    public function getConnection() {
        return $this->pdo;
    }

    // Evitar la clonación del objeto
    private function __clone() {}

    // Evitar la deserialización del objeto
    private function __wakeup() {}
}

?>
