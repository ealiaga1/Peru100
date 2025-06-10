<?php
// app/models/ConfigModel.php

class ConfigModel extends Model {

    // Obtener la única fila de configuración de la empresa
    public function getCompanyConfig() {
        // Asumimos que siempre habrá un registro con id_config = 1
        $stmt = $this->db->query("SELECT * FROM configuracion_empresa WHERE id_config = 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar la configuración de la empresa
    public function updateCompanyConfig($data) {
        $sql = "UPDATE configuracion_empresa SET 
                    nombre_empresa = ?, 
                    razon_social = ?, 
                    ruc = ?, 
                    direccion = ?, 
                    telefono = ?, 
                    correo = ?, 
                    sitio_web = ?, 
                    logo_url = ?, 
                    moneda_simbolo = ?, 
                    mensaje_factura = ?, 
                    igv_porcentaje = ? 
                WHERE id_config = 1";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre_empresa'],
            $data['razon_social'],
            $data['ruc'],
            $data['direccion'],
            $data['telefono'],
            $data['correo'],
            $data['sitio_web'],
            $data['logo_url'],
            $data['moneda_simbolo'],
            $data['mensaje_factura'],
            $data['igv_porcentaje']
        ]);
    }

    // Método para insertar la configuración inicial si no existe (llamado una sola vez)
    public function createInitialConfig($data) {
        // Solo inserta si no hay un registro existente (con id_config = 1)
        if (!$this->getCompanyConfig()) {
            $sql = "INSERT INTO configuracion_empresa (id_config, nombre_empresa, razon_social, ruc, direccion, telefono, correo, sitio_web, logo_url, moneda_simbolo, mensaje_factura, igv_porcentaje) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                1, // Fija el ID en 1
                $data['nombre_empresa'],
                $data['razon_social'],
                $data['ruc'],
                $data['direccion'],
                $data['telefono'],
                $data['correo'],
                $data['sitio_web'],
                $data['logo_url'],
                $data['moneda_simbolo'],
                $data['mensaje_factura'],
                $data['igv_porcentaje']
            ]);
        }
        return false; // Ya existe configuración
    }
}