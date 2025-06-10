<?php // app/views/inventario/productos/index.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>inventario/createProducto" class="btn btn-primary">Crear Nuevo Producto</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Tipo</th>
                <th>Precio Venta</th>
                <th>Stock</th>
                <th>Unidad</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['id_producto']); ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($producto['tipo_producto']); ?></td>
                        <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($producto['precio_venta'], 2); ?></td>
                        <td><?php echo htmlspecialchars($producto['stock_actual']); ?></td>
                        <td><?php echo htmlspecialchars($producto['unidad_venta_nombre']); ?></td>
                        <td><?php echo $producto['activo'] ? 'Sí' : 'No'; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>inventario/editProducto/<?php echo htmlspecialchars($producto['id_producto']); ?>" class="btn btn-edit">Editar</a>
                            <?php if ($producto['activo']): ?>
                                <a href="<?php echo BASE_URL; ?>inventario/deleteProducto/<?php echo htmlspecialchars($producto['id_producto']); ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de desactivar este producto?');">Desactivar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No hay productos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>