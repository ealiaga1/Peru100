<?php
// core/Model.php

class Model {
    // Propiedad protegida para almacenar el objeto PDO de la conexión a la base de datos
    protected $db;

    // El constructor de la clase Model obtiene la conexión PDO al ser instanciado
    public function __construct() {
        // Obtiene la instancia única de Database y luego su conexión PDO
        $this->db = Database::getInstance()->getConnection();
    }

    // Puedes añadir métodos comunes aquí que tus modelos específicos usarán.
    // Por ejemplo, un método genérico para ejecutar consultas preparadas.
    /*
    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Ejemplo de método para obtener todos los registros de una tabla
    protected function getAll($tableName) {
        $stmt = $this->db->query("SELECT * FROM " . $tableName);
        return $stmt->fetchAll();
    }
    */
}