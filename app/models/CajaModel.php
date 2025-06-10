<?php
// app/models/CajaModel.php

class CajaModel extends Model {

    // --- Métodos para Apertura y Cierre de Caja ---

    // Obtener la última caja abierta (si existe)
    public function getOpenCaja() {
        $stmt = $this->db->query("SELECT * FROM cajas WHERE estado_caja = 'Abierta' ORDER BY fecha_apertura DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Abrir una nueva caja
    public function openCaja($id_usuario_apertura, $monto_inicial) {
        $stmt = $this->db->prepare("INSERT INTO cajas (id_usuario_apertura, monto_inicial, fecha_apertura, estado_caja) VALUES (?, ?, NOW(), 'Abierta')");
        $stmt->execute([$id_usuario_apertura, $monto_inicial]);
        return $this->db->lastInsertId();
    }

    // Cerrar una caja existente
    public function closeCaja($id_caja, $id_usuario_cierre, $monto_final) {
        // Calcular la diferencia (monto_final - monto_inicial_registrado + ingresos_esperados - egresos_esperados)
        // Por simplicidad, aquí solo calculamos monto_final - monto_inicial (sin considerar ingresos/egresos)
        // La lógica real de diferencia es más compleja y se haría sumando movimientos.
        $caja_info = $this->getOpenCaja(); // Asume que solo hay una caja abierta
        if (!$caja_info || $caja_info['id_caja'] != $id_caja) {
            return false; // La caja no está abierta o no coincide
        }
        $diferencia = $monto_final - $caja_info['monto_inicial']; // Esto necesita ser más sofisticado

        // Para un cierre real, deberías sumar todos los movimientos de la caja:
        $total_ingresos = $this->getMovimientosSum($id_caja, 'Ingreso');
        $total_egresos = $this->getMovimientosSum($id_caja, 'Egreso');
        $diferencia_real = $monto_final - ($caja_info['monto_inicial'] + $total_ingresos - $total_egresos);


        $stmt = $this->db->prepare("UPDATE cajas SET id_usuario_cierre = ?, fecha_cierre = NOW(), monto_final = ?, diferencia = ?, estado_caja = 'Cerrada' WHERE id_caja = ?");
        $stmt->execute([$id_usuario_cierre, $monto_final, $diferencia_real, $id_caja]);
        return $stmt->rowCount() > 0;
    }

    // --- Métodos para Ventas (Boletas/Facturas) ---

    // Obtener métodos de pago activos
    public function getMetodosPago() {
        $stmt = $this->db->query("SELECT * FROM metodos_pago WHERE activo = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un cliente por su número de documento
    public function getClienteByDocumento($numero_documento) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE numero_documento = ?");
        $stmt->execute([$numero_documento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo cliente (si no existe)
    public function createCliente($nombre_razon_social, $tipo_documento, $numero_documento, $direccion = null, $telefono = null, $correo = null) {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre_razon_social, tipo_documento, numero_documento, direccion, telefono, correo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre_razon_social, $tipo_documento, $numero_documento, $direccion, $telefono, $correo]);
        return $this->db->lastInsertId();
    }

    // Registrar una venta (desde un pedido existente o venta directa POS)
    public function registerVenta($id_pedido, $id_caja, $id_usuario_cajero, $total_venta, $monto_recibido, $cambio, $id_metodo_pago, $tipo_documento, $numero_documento = null, $id_cliente = null) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("INSERT INTO ventas (id_pedido, id_caja, id_usuario_cajero, fecha_hora_venta, total_venta, monto_recibido, cambio, id_metodo_pago, tipo_documento, numero_documento, id_cliente) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_pedido, $id_caja, $id_usuario_cajero, $total_venta, $monto_recibido, $cambio, $id_metodo_pago, $tipo_documento, $numero_documento, $id_cliente]);
            $venta_id = $this->db->lastInsertId();

            // Si la venta proviene de un pedido, actualizar el estado del pedido a 'Pagado'
            if ($id_pedido) {
                $stmt_pedido = $this->db->prepare("UPDATE pedidos SET estado_pedido = 'Pagado' WHERE id_pedido = ?");
                $stmt_pedido->execute([$id_pedido]);
            }
            
            // Registrar el ingreso por venta en movimientos_caja
            $tipo_movimiento_ingreso = $this->getTipoMovimientoId('Ingreso por Venta');
            if ($tipo_movimiento_ingreso) {
                $this->registerMovimientoCaja($id_caja, $tipo_movimiento_ingreso, $total_venta, 'Venta #' . $venta_id, $id_usuario_cajero, 'Venta');
            }

            $this->db->commit();
            return $venta_id;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error al registrar venta: " . $e->getMessage());
            die("Error al registrar venta (DB): " . $e->getMessage() .
                "<br>Código: " . $e->getCode() .
                "<br>SQLSTATE: " . ($e->errorInfo[0] ?? 'N/A'));
        }
    }

     // Obtener una venta por ID
    public function getVentaById($id_venta) {
        $stmt = $this->db->prepare("
            SELECT 
                v.*, 
                mp.nombre_metodo, 
                u.nombre_usuario as cajero_nombre, 
                c.nombre_razon_social as cliente_nombre,
                c.tipo_documento as cliente_tipo_documento,    /* <-- ¡AÑADIDO! */
                c.numero_documento as cliente_numero_documento /* <-- ¡AÑADIDO! */
            FROM ventas v
            JOIN metodos_pago mp ON v.id_metodo_pago = mp.id_metodo_pago
            JOIN usuarios u ON v.id_usuario_cajero = u.id_usuario
            LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
            WHERE v.id_venta = ?
        ");
        $stmt->execute([$id_venta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- Métodos para Movimientos de Caja (Ingresos/Egresos) ---

    // Obtener todos los tipos de movimiento
    public function getTiposMovimiento() {
        $stmt = $this->db->query("SELECT * FROM tipos_movimiento_caja WHERE activo = 1 ORDER BY nombre_tipo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener el ID de un tipo de movimiento por su nombre
    public function getTipoMovimientoId($nombre_tipo) {
        $stmt = $this->db->prepare("SELECT id_tipo_movimiento FROM tipos_movimiento_caja WHERE nombre_tipo = ? AND activo = 1");
        $stmt->execute([$nombre_tipo]);
        return $stmt->fetchColumn();
    }

    // Registrar un movimiento de caja (ingreso o egreso)
    public function registerMovimientoCaja($id_caja, $id_tipo_movimiento, $monto, $descripcion, $id_usuario_registra, $referencia_externa = null) {
        $stmt = $this->db->prepare("INSERT INTO movimientos_caja (id_caja, id_tipo_movimiento, monto, descripcion, fecha_hora_movimiento, id_usuario_registra, referencia_externa) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
        $stmt->execute([$id_caja, $id_tipo_movimiento, $monto, $descripcion, $id_usuario_registra, $referencia_externa]);
        return $this->db->lastInsertId();
    }

    // Obtener movimientos de una caja
    public function getMovimientosCaja($id_caja) {
        $stmt = $this->db->prepare("
            SELECT mc.*, tmc.nombre_tipo, tmc.tipo as tipo_movimiento_nombre
            FROM movimientos_caja mc
            JOIN tipos_movimiento_caja tmc ON mc.id_tipo_movimiento = tmc.id_tipo_movimiento
            WHERE mc.id_caja = ?
            ORDER BY mc.fecha_hora_movimiento ASC
        ");
        $stmt->execute([$id_caja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sumar ingresos o egresos de una caja
    public function getMovimientosSum($id_caja, $tipo = 'Ingreso') {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(mc.monto), 0)
            FROM movimientos_caja mc
            JOIN tipos_movimiento_caja tmc ON mc.id_tipo_movimiento = tmc.id_tipo_movimiento
            WHERE mc.id_caja = ? AND tmc.tipo = ?
        ");
        $stmt->execute([$id_caja, $tipo]);
        return $stmt->fetchColumn();
    }
}