<?php // app/views/pedidos/comandas_cocina.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($comandas)): ?>
        <div class="comandas-grid">
            <?php 
            $pedidos_agrupados = [];
            foreach ($comandas as $item) {
                // Agrupar por pedido_id, luego por numero_mesa (si quisieras)
                $pedidos_agrupados[$item['id_pedido']]['numero_mesa'] = $item['numero_mesa'];
                $pedidos_agrupados[$item['id_pedido']]['items'][] = $item;
            }
            ?>

            <?php foreach ($pedidos_agrupados as $id_pedido => $data_pedido): ?>
                <div class="comanda-card">
                    <h3>Mesa #<?php echo htmlspecialchars($data_pedido['numero_mesa']); ?> - Pedido #<?php echo htmlspecialchars($id_pedido); ?></h3>
                    <ul class="comanda-items-list">
                        <?php foreach ($data_pedido['items'] as $item): ?>
                            <li class="comanda-item status-<?php echo strtolower(str_replace(' ', '-', $item['estado_item'])); ?>">
                                <div class="item-details">
                                    <span class="comanda-qty"><?php echo htmlspecialchars($item['cantidad']); ?>x</span>
                                    <span class="comanda-product"><?php echo htmlspecialchars($item['nombre_producto']); ?></span>
                                    <?php if (!empty($item['notas_item'])): ?>
                                        <span class="comanda-notes">(<?php echo htmlspecialchars($item['notas_item']); ?>)</span>
                                    <?php endif; ?>
                                </div>
                                <div class="item-actions">
                                    <?php if ($item['estado_item'] !== 'Listo'): ?>
                                        <a href="<?php echo BASE_URL; ?>pedidos/markItemReady/<?php echo htmlspecialchars($item['id_detalle_pedido']); ?>/cocina" class="btn btn-success btn-small" onclick="return confirm('¿Marcar este ítem como LISTO?');">Listo</a>
                                    <?php else: ?>
                                        <span class="status-label">Listo</span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay comandas de cocina pendientes en este momento.</p>
    <?php endif; ?>
</div>

<style>
    /* ... (tus estilos existentes de comandas_cocina.php) ... */
    .comanda-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .comanda-item:last-child {
        border-bottom: none;
    }
    .item-details {
        flex-grow: 1;
    }
    .item-actions {
        margin-left: 15px; /* Espacio entre detalles y botón */
    }
    .status-label {
        background-color: #28a745; /* Verde */
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85em;
        font-weight: bold;
        text-transform: capitalize;
    }
</style>