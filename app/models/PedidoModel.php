<?php
// app/models/PedidoModel.php

class PedidoModel extends Model {

    /**
     * Crea un nuevo pedido inicial en la base de datos.
     * @param int $id_mesa ID de la mesa asociada al pedido.
     * @param int $id_usuario_mesero ID del mesero que toma el pedido.
     * @param string $tipo_pedido Tipo de pedido (ej. 'Mesa', 'Para llevar', 'Delivery').
     * @param string|null $notas_pedido Notas generales del pedido.
     * @return int|false El ID del pedido recién creado o false en caso de error.
     */
    public function createPedido($id_mesa, $id_usuario_mesero, $tipo_pedido = 'Mesa', $notas_pedido = null) {
        $this->db->beginTransaction();

        try {
            // Usando marcadores de posición posicionales (?)
            $stmt = $this->db->prepare("INSERT INTO pedidos (id_mesa, id_usuario_mesero, tipo_pedido, notas_pedido) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_mesa, $id_usuario_mesero, $tipo_pedido, $notas_pedido]);

            $pedido_id = $this->db->lastInsertId();

            $this->db->commit();
            return $pedido_id;

        } catch (PDOException $e) {
            $this->db->rollBack();
            die("Error al crear pedido (DB): " . $e->getMessage() .
                "<br>Código: " . $e->getCode() .
                "<br>SQLSTATE: " . ($e->errorInfo[0] ?? 'N/A'));
        }
    }

    /**
     * Añade múltiples detalles de producto a un pedido existente.
     * @param int $pedido_id ID del pedido al que se añadirán los detalles.
     * @param array $productos_data Un array de arrays, cada uno representando un ítem del pedido.
     * @return bool True si la inserción fue exitosa, false en caso contrario.
     */
    public function addDetallesPedido($pedido_id, $productos_data) {
        $this->db->beginTransaction();
        try {
            $sql_insert_detalle = "INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal, notas_item) VALUES (?, ?, ?, ?, ?, ?)";

            foreach ($productos_data as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                
                $stmt = $this->db->prepare($sql_insert_detalle);
                $stmt->execute([
                    $pedido_id,
                    $item['id_producto'],
                    $item['cantidad'],
                    $item['precio_unitario'],
                    $subtotal,
                    $item['notas_item'] ?? null
                ]);
                $stmt->closeCursor(); 
            }

            $this->updatePedidoTotal($pedido_id);

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            die("Error al añadir detalles al pedido (DB): " . $e->getMessage() .
                "<br>Código: " . $e->getCode() .
                "<br>SQLSTATE: " . ($e->errorInfo[0] ?? 'N/A'));
        }
    }

    /**
     * Actualiza el total de un pedido sumando los subtotales de sus detalles.
     * @param int $pedido_id ID del pedido a actualizar.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updatePedidoTotal($pedido_id) {
        $stmt_select_total = $this->db->prepare("SELECT COALESCE(SUM(subtotal), 0) FROM detalles_pedido WHERE id_pedido = ?");
        $stmt_select_total->execute([$pedido_id]);
        $total_calculado = $stmt_select_total->fetchColumn();
        $stmt_select_total->closeCursor();

        $stmt_update = $this->db->prepare("UPDATE pedidos SET total_pedido = ? WHERE id_pedido = ?");
        $stmt_update->execute([$total_calculado, $pedido_id]);
        
        return true; // Si execute() no lanzó excepción, fue exitoso.
    }

    /**
     * Obtiene un pedido y todos sus detalles de productos.
     * @param int $pedido_id ID del pedido a buscar.
     * @return array|false Un array asociativo con los datos del pedido y sus detalles, o false si no se encuentra.
     */
    public function getPedidoWithDetails($pedido_id) {
        $pedido = $this->db->prepare("
            SELECT p.*, m.numero_mesa, u.nombre_usuario as mesero_nombre, m.estado as estado_mesa
            FROM pedidos p
            JOIN mesas m ON p.id_mesa = m.id_mesa
            JOIN usuarios u ON p.id_usuario_mesero = u.id_usuario
            WHERE p.id_pedido = ?
        ");
        $pedido->execute([$pedido_id]); // Pasar el valor directamente
        $result = $pedido->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $detalles = $this->db->prepare("
                SELECT dp.*, prod.nombre_producto
                FROM detalles_pedido dp
                JOIN productos prod ON dp.id_producto = prod.id_producto
                WHERE dp.id_pedido = ?
            ");
            $detalles->execute([$pedido_id]); // Pasar el valor directamente
            $result['detalles'] = $detalles->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    /**
     * Obtiene una lista de todos los pedidos registrados en el sistema.
     * @return array Un array de arrays asociativos con los datos de los pedidos.
     */
    public function getAllPedidos() {
        $stmt = $this->db->query("
            SELECT p.*, m.numero_mesa, u.nombre_usuario as mesero_nombre
            FROM pedidos p
            JOIN mesas m ON p.id_mesa = m.id_mesa
            JOIN usuarios u ON p.id_usuario_mesero = u.id_usuario
            ORDER BY p.fecha_hora_pedido DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza el estado de un pedido (ej. 'Pendiente', 'En preparación', 'Listo', 'Servido').
     * @param int $pedido_id ID del pedido a actualizar.
     * @param string $estado El nuevo estado del pedido.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updatePedidoEstado($pedido_id, $estado) {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado_pedido = ? WHERE id_pedido = ?");
        $stmt->execute([$estado, $pedido_id]);
        return true;
    }

    /**
     * Obtiene comandas pendientes para un tipo específico (Cocina o Bar).
     * @param string $type El tipo de comanda a obtener ('Cocina' o 'Bar').
     * @return array Un array de arrays asociativos con los ítems de las comandas pendientes.
     */
    public function getPendingComandas($type = 'Cocina') {
        $stmt = $this->db->prepare("
            SELECT dp.id_detalle_pedido, dp.cantidad, dp.notas_item, m.numero_mesa, prod.nombre_producto,
                   ped.id_pedido, dp.estado_item
            FROM detalles_pedido dp
            JOIN pedidos ped ON dp.id_pedido = ped.id_pedido
            JOIN mesas m ON ped.id_mesa = m.id_mesa
            JOIN productos prod ON dp.id_producto = prod.id_producto
            WHERE dp.estado_item IN ('Pendiente', 'En preparación')
            AND prod.tipo_producto = ?
            ORDER BY ped.fecha_hora_pedido ASC, m.numero_mesa ASC
        ");
        
        $tipo_producto_comanda = '';
        if ($type == 'Cocina') {
            $tipo_producto_comanda = 'Plato';
        } elseif ($type == 'Bar') {
            $tipo_producto_comanda = 'Bebida';
        } else {
            return [];
        }
        $stmt->execute([$tipo_producto_comanda]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marca el estado de un detalle de pedido (ítem individual) como 'Listo'.
     * @param int $id_detalle_pedido ID del detalle del pedido a actualizar.
     * @param string $estado El nuevo estado del ítem (ej. 'Listo').
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updateDetallePedidoEstado($id_detalle_pedido, $estado) {
        $stmt = $this->db->prepare("UPDATE detalles_pedido SET estado_item = ? WHERE id_detalle_pedido = ?");
        $stmt->execute([$estado, $id_detalle_pedido]);
        return true;
    }

    /**
     * Registra que una comanda fue impresa.
     * @param int $pedido_id ID del pedido al que pertenece la comanda.
     * @param string $tipo_comanda Tipo de comanda (ej. 'Cocina', 'Bar').
     * @param int $id_usuario_imprime ID del usuario que registra la impresión de la comanda.
     * @return bool True si el registro fue exitoso, false en caso contrario.
     */
    public function registerComandaPrinted($pedido_id, $tipo_comanda, $id_usuario_imprime) {
        $stmt = $this->db->prepare("INSERT INTO comandas (id_pedido, tipo_comanda, fecha_hora_impresion, id_usuario_imprime) VALUES (?, ?, NOW(), ?)");
        $stmt->execute([$pedido_id, $tipo_comanda, $id_usuario_imprime]);
        return true;
    }
}