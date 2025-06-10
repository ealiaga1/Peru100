<?php
// core/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Construye el DSN (Data Source Name) para la conexión MySQL
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        
        // Opciones de PDO para una conexión robusta y segura
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Lanza excepciones en caso de error
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Devuelve filas como arrays asociativos
            PDO::ATTR_EMULATE_PREPARES   => false,                    // Deshabilita la emulación de prepared statements para mayor seguridad
        ];

        try {
            // Intenta crear una nueva instancia de PDO
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // --- ¡IMPORTANTE! ESTO ES PARA DEPURACIÓN SOLAMENTE ---
            // Muestra el error detallado de la base de datos directamente en pantalla.
            // En un entorno de producción, DEBES cambiar esto para registrar el error (error_log())
            // y mostrar un mensaje de error genérico al usuario sin detalles internos.
            die('Error de conexión a la base de datos: ' . $e->getMessage() . 
                '<br>Código de Error: ' . $e->getCode() . 
                '<br>SQLSTATE: ' . $e->getSQLSTATE());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}