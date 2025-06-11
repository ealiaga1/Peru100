<?php
// app/models/MesasModel.php

class MesasModel extends Model {

    // --- Métodos para Salones ---

    // Obtener todos los salones activos
    public function getAllSalones() {
        $stmt = $this->db->query("SELECT * FROM salones WHERE activo = 1 ORDER BY nombre_salon");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un salón por su ID
    public function getSalonById($id) {
        $stmt = $this->db->prepare("SELECT * FROM salones WHERE id_salon = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo salón
    public function createSalon($nombre_salon, $capacidad_maxima = null) {
        $stmt = $this->db->prepare("INSERT INTO salones (nombre_salon, capacidad_maxima) VALUES (:nombre_salon, :capacidad_maxima)");
        $stmt->bindParam(':nombre_salon', $nombre_salon, PDO::PARAM_STR);
        $stmt->bindParam(':capacidad_maxima', $capacidad_maxima, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Actualizar un salón existente
    public function updateSalon($id, $nombre_salon, $capacidad_maxima = null, $activo = 1) {
        $stmt = $this->db->prepare("UPDATE salones SET nombre_salon = :nombre_salon, capacidad_maxima = :capacidad_maxima, activo = :activo WHERE id_salon = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_salon', $nombre_salon, PDO::PARAM_STR);
        $stmt->bindParam(':capacidad_maxima', $capacidad_maxima, PDO::PARAM_INT);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Desactivar (eliminar lógicamente) un salón
    public function deleteSalon($id) {
        // En lugar de DELETE, actualizamos 'activo' a 0
        $stmt = $this->db->prepare("UPDATE salones SET activo = 0 WHERE id_salon = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // --- Métodos para Mesas ---

    // Obtener todas las mesas con su salón asociado
    public function getAllMesas() {
        $stmt = $this->db->query("
            SELECT m.*, s.nombre_salon
            FROM mesas m
            JOIN salones s ON m.id_salon = s.id_salon
            WHERE m.activo = 1
            ORDER BY s.nombre_salon, m.numero_mesa
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener mesas por ID de salón
    public function getMesasBySalonId($id_salon) {
        $stmt = $this->db->prepare("SELECT * FROM mesas WHERE id_salon = :id_salon AND activo = 1 ORDER BY numero_mesa");
        $stmt->bindParam(':id_salon', $id_salon, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una mesa por su ID
    public function getMesaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM mesas WHERE id_mesa = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva mesa
    public function createMesa($numero_mesa, $capacidad, $id_salon, $estado = 'Libre') {
        $stmt = $this->db->prepare("INSERT INTO mesas (numero_mesa, capacidad, id_salon, estado) VALUES (:numero_mesa, :capacidad, :id_salon, :estado)");
        $stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_STR);
        $stmt->bindParam(':capacidad', $capacidad, PDO::PARAM_INT);
        $stmt->bindParam(':id_salon', $id_salon, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Actualizar una mesa existente
    public function updateMesa($id, $numero_mesa, $capacidad, $id_salon, $estado, $activo = 1) {
        $stmt = $this->db->prepare("UPDATE mesas SET numero_mesa = :numero_mesa, capacidad = :capacidad, id_salon = :id_salon, estado = :estado, activo = :activo WHERE id_mesa = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_STR);
        $stmt->bindParam(':capacidad', $capacidad, PDO::PARAM_INT);
        $stmt->bindParam(':id_salon', $id_salon, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Actualizar solo el estado de una mesa
    public function updateMesaEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE mesas SET estado = :estado WHERE id_mesa = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Desactivar (eliminar lógicamente) una mesa
    public function deleteMesa($id) {
        // En lugar de DELETE, actualizamos 'activo' a 0
        $stmt = $this->db->prepare("UPDATE mesas SET activo = 0 WHERE id_mesa = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Verificar si el nombre del salón ya existe
    public function salonExists($nombre_salon, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM salones WHERE nombre_salon = :nombre_salon AND activo = 1";
        if ($excludeId) {
            $sql .= " AND id_salon != :exclude_id";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre_salon', $nombre_salon, PDO::PARAM_STR);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Verificar si el número de mesa ya existe en un salón específico
    public function mesaExistsInSalon($numero_mesa, $id_salon, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM mesas WHERE numero_mesa = :numero_mesa AND id_salon = :id_salon AND activo = 1";
        if ($excludeId) {
            $sql .= " AND id_mesa != :exclude_id";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_STR);
        $stmt->bindParam(':id_salon', $id_salon, PDO::PARAM_INT);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}