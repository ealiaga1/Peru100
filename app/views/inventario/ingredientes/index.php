<?php // app/views/inventario/ingredientes/index.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>inventario/createIngrediente" class="btn btn-primary">Crear Nuevo Ingrediente</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Costo Unitario</th>
                <th>Stock</th>
                <th>Unidad</th>
                <th>Stock Mínimo</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ingredientes)): ?>
                <?php foreach ($ingredientes as $ingrediente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ingrediente['id_ingrediente']); ?></td>
                        <td><?php echo htmlspecialchars($ingrediente['nombre_ingrediente']); ?></td>
                        <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($ingrediente['costo_unitario'], 2); ?></td>
                        <td><?php echo htmlspecialchars($ingrediente['stock_actual']); ?></td>
                        <td><?php echo htmlspecialchars($ingrediente['unidad_medida_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($ingrediente['stock_minimo']); ?></td>
                        <td><?php echo $ingrediente['activo'] ? 'Sí' : 'No'; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>inventario/editIngrediente/<?php echo htmlspecialchars($ingrediente['id_ingrediente']); ?>" class="btn btn-edit">Editar</a>
                            <?php if ($ingrediente['activo']): ?>
                                <a href="<?php echo BASE_URL; ?>inventario/deleteIngrediente/<?php echo htmlspecialchars($ingrediente['id_ingrediente']); ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de desactivar este ingrediente?');">Desactivar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No hay ingredientes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>