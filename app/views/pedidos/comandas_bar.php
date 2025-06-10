<?php // app/views/pedidos/comandas_bar.php ?>

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
                                        <a href="<?php echo BASE_URL; ?>pedidos/markItemReady/<?php echo htmlspecialchars($item['id_detalle_pedido']); ?>/bar" class="btn btn-success btn-small" onclick="return confirm('¿Marcar este ítem como LISTO?');">Listo</a>
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
        <p>No hay comandas de bar pendientes en este momento.</p>
    <?php endif; ?>
</div>
<style>
    /* Reutiliza los estilos de comanda de cocina.php */
    <?php include ROOT_PATH . 'app/views/pedidos/comandas_cocina.php'; ?>
    .comanda-card { /* Sobrescribe si es necesario para esta vista específica */ }
</style>