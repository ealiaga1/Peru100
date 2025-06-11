<?php
// app/models/UserModel.php

class UserModel extends Model {

    // Método para encontrar un usuario por su nombre de usuario
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre_rol FROM usuarios u JOIN roles r ON u.id_rol = r.id_rol WHERE u.nombre_usuario = ? AND u.activo = 1");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para verificar la contraseña de un usuario
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }

    // --- Métodos de Gestión de Usuarios (CRUD) ---

    // Obtener todos los usuarios con su rol (activos o inactivos)
    public function getAllUsers($onlyActive = false) {
        $sql = "SELECT u.*, r.nombre_rol FROM usuarios u JOIN roles r ON u.id_rol = r.id_rol";
        if ($onlyActive) {
            $sql .= " WHERE u.activo = 1";
        }
        $sql .= " ORDER BY u.apellidos, u.nombres";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por su ID
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre_rol FROM usuarios u JOIN roles r ON u.id_rol = r.id_rol WHERE u.id_usuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo usuario
    public function createUser($nombre_usuario, $contrasena_hash, $nombres, $apellidos, $id_rol, $telefono = null, $correo = null) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre_usuario, contrasena_hash, nombres, apellidos, telefono, correo, id_rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre_usuario, $contrasena_hash, $nombres, $apellidos, $telefono, $correo, $id_rol]);
    }

    // Actualizar un usuario existente (sin cambiar contraseña)
    public function updateUser($id, $nombre_usuario, $nombres, $apellidos, $id_rol, $activo, $telefono = null, $correo = null) {
        $stmt = $this->db->prepare("UPDATE usuarios SET nombre_usuario = ?, nombres = ?, apellidos = ?, telefono = ?, correo = ?, id_rol = ?, activo = ? WHERE id_usuario = ?");
        return $stmt->execute([$nombre_usuario, $nombres, $apellidos, $telefono, $correo, $id_rol, $activo, $id]);
    }

    // Actualizar solo la contraseña de un usuario
    public function updatePassword($id, $new_contrasena_hash) {
        $stmt = $this->db->prepare("UPDATE usuarios SET contrasena_hash = ? WHERE id_usuario = ?");
        return $stmt->execute([$new_contrasena_hash, $id]);
    }

    // Desactivar (eliminar lógicamente) un usuario
    public function deactivateUser($id) {
        $stmt = $this->db->prepare("UPDATE usuarios SET activo = 0 WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }

    // Activar un usuario
    public function activateUser($id) {
        $stmt = $this->db->prepare("UPDATE usuarios SET activo = 1 WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }

    // Verificar si el nombre de usuario o correo ya existen
    public function userExists($nombre_usuario, $correo = null, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = ?";
        $params = [$nombre_usuario];
        
        if ($correo && !empty($correo)) {
            $sql .= " OR correo = ?";
            $params[] = $correo;
        }

        if ($excludeId) {
            $sql .= " AND id_usuario != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // --- Métodos de Roles ---

    // Obtener todos los roles disponibles (activos o inactivos)
    public function getAllRoles($onlyActive = true) {
        $sql = "SELECT id_rol, nombre_rol FROM roles";
        if ($onlyActive) {
            $sql .= " WHERE activo = 1";
        }
        $sql .= " ORDER BY nombre_rol";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}