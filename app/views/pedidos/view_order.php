<?php // app/views/pedidos/view_order.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($pedido)): ?>
        <div class="order-details-summary">
            <h3>Detalles del Pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?></h3>
            <p><strong>Mesa:</strong> <?php echo htmlspecialchars($pedido['numero_mesa']); ?></p>
            <p><strong>Mesero:</strong> <?php echo htmlspecialchars($pedido['mesero_nombre']); ?></p>
            <p><strong>Fecha/Hora:</strong> <?php echo htmlspecialchars($pedido['fecha_hora_pedido']); ?></p>
            <p><strong>Estado:</strong> <span class="order-status-badge status-<?php echo strtolower(str_replace(' ', '-', $pedido['estado_pedido'])); ?>"><?php echo htmlspecialchars($pedido['estado_pedido']); ?></span></p>
            <?php if (!empty($pedido['notas_pedido'])): ?>
                <p><strong>Notas del Pedido:</strong> <?php echo htmlspecialchars($pedido['notas_pedido']); ?></p>
            <?php endif; ?>
        </div>

        <h3>Productos del Pedido</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                    <th>Notas Ítem</th>
                    <th>Estado Ítem</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_pedido = 0; ?>
                <?php if (!empty($pedido['detalles'])): ?>
                    <?php foreach ($pedido['detalles'] as $detalle): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                            <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                            <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                            <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($detalle['subtotal'], 2); ?></td>
                            <td><?php echo htmlspecialchars($detalle['notas_item'] ?? 'N/A'); ?></td>
                            <td><span class="item-status-badge status-<?php echo strtolower(str_replace(' ', '-', $detalle['estado_item'])); ?>"><?php echo htmlspecialchars($detalle['estado_item']); ?></span></td>
                        </tr>
                        <?php $total_pedido += $detalle['subtotal']; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay productos en este pedido.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Pedido:</strong></td>
                    <td><strong><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($total_pedido, 2); ?></strong></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        <div class="order-actions">
            <a href="<?php echo BASE_URL; ?>pedidos/list" class="btn btn-secondary">Volver al Listado</a>
            
            <?php 
            // Ocultar botones de acción si el pedido ya está pagado o cancelado
            $can_change_status = !in_array($pedido['estado_pedido'], ['Pagado', 'Cancelado']);
            ?>

            <?php if ($can_change_status && ($pedido['estado_pedido'] == 'Pendiente' || $pedido['estado_pedido'] == 'En preparación')): ?>
                <a href="<?php echo BASE_URL; ?>pedidos/updateOrderStatus/<?php echo htmlspecialchars($pedido['id_pedido']); ?>/Listo" class="btn btn-warning" onclick="return confirm('¿Marcar este pedido como LISTO PARA SERVIR?');">Marcar como Listo</a>
            <?php endif; ?>

            <?php if ($can_change_status && ($pedido['estado_pedido'] == 'Listo')): ?>
                <a href="<?php echo BASE_URL; ?>pedidos/updateOrderStatus/<?php echo htmlspecialchars($pedido['id_pedido']); ?>/Servido" class="btn btn-primary" onclick="return confirm('¿Marcar este pedido como SERVIDO?');">Marcar como Servido</a>
            <?php endif; ?>

            <?php 
            // Botón "Enviar a Caja / Facturar" visible si el pedido está 'Servido' o 'Listo' y no está 'Pagado'/'Cancelado'
            if ($can_change_status && in_array($pedido['estado_pedido'], ['Servido', 'Listo'])): ?>
                <a href="<?php echo BASE_URL; ?>caja/registerSale/<?php echo htmlspecialchars($pedido['id_pedido']); ?>" class="btn btn-success">Enviar a Caja / Facturar</a>
            <?php endif; ?>

            <?php 
            // Asumiendo que $_SESSION['role'] está disponible (viene de Controller::__construct)
            if ($can_change_status && isset($_SESSION['role']) && in_array($_SESSION['role'], ['Super Admin', 'Cajero'])): ?>
                <a href="<?php echo BASE_URL; ?>pedidos/updateOrderStatus/<?php echo htmlspecialchars($pedido['id_pedido']); ?>/Cancelado" class="btn btn-delete" onclick="return confirm('¿Está seguro de ANULAR este pedido? Esta acción no se puede deshacer.');">Anular Pedido</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>El pedido solicitado no fue encontrado.</p>
    <?php endif; ?>
</div>

<style>
    .order-details-summary {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .order-details-summary p {
        margin: 5px 0;
        font-size: 1.1em;
    }
    .order-status-badge, .item-status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9em;
        font-weight: bold;
        color: white;
        text-transform: capitalize;
    }
    .status-pendiente { background-color: #ffc107; color: #333; } /* Amarillo */
    .status-en-preparacion { background-color: #007bff; } /* Azul */
    .status-listo { background-color: #28a745; } /* Verde */
    .status-servido { background-color: #6f42c1; } /* Morado */
    .status-pagado { background-color: #17a2b8; } /* Turquesa */
    .status-cancelado { background-color: #dc3545; } /* Rojo */
    
    .order-actions {
        margin-top: 30px;
        text-align: right;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
    .btn-warning { background-color: #ffc107; color: #333; }
    .btn-warning:hover { background-color: #e0a800; }
    .btn-success { background-color: #28a745; }
    .btn-success:hover { background-color: #218838; }
    .btn-delete { background-color: #dc3545; }
    .btn-delete:hover { background-color: #c82333; }

    @media print {
        body * {
            visibility: hidden;
        }
        .sale-details-summary, .sale-details-summary *, 
        .data-table, .data-table *,
        h2, h3 {
            visibility: visible;
        }
        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;