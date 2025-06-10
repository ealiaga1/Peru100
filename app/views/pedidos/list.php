<?php // app/views/pedidos/list.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Mesa</th>
                <th>Mesero</th>
                <th>Fecha/Hora</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pedidos)): ?>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['numero_mesa']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['mesero_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['fecha_hora_pedido']); ?></td>
                        <td><span class="order-status-badge status-<?php echo strtolower(str_replace(' ', '-', $pedido['estado_pedido'])); ?>"><?php echo htmlspecialchars($pedido['estado_pedido']); ?></span></td>
                        <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($pedido['total_pedido'], 2); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>pedidos/viewOrder/<?php echo htmlspecialchars($pedido['id_pedido']); ?>" class="btn btn-primary btn-small">Ver Detalle</a>
                            </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay pedidos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    .order-status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9em;
        font-weight: bold;
        color: white;
        text-transform: capitalize;
    }
    .status-pendiente { background-color: #ffc107; color: #333; }
    .status-en-preparacion { background-color: #007bff; }
    .status-listo { background-color: #28a745; }
    .status-servido { background-color: #6f42c1; }
    .status-pagado { background-color: #17a2b8; }
    .status-cancelado { background-color: #dc3545; }
    .btn-small { padding: 5px 10px; font-size: 0.85em; }
</style>