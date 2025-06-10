<?php // app/views/inventario/categorias/index.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>inventario/createCategoria" class="btn btn-primary">Crear Nueva Categoría</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categoria['id_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($categoria['descripcion'] ?? 'N/A'); ?></td>
                        <td><?php echo $categoria['activo'] ? 'Activa' : 'Inactiva'; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>inventario/editCategoria/<?php echo htmlspecialchars($categoria['id_categoria']); ?>" class="btn btn-edit">Editar</a>
                            <?php if ($categoria['activo']): ?>
                                <a href="<?php echo BASE_URL; ?>inventario/deleteCategoria/<?php echo htmlspecialchars($categoria['id_categoria']); ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de desactivar esta categoría? Esto podría afectar a productos existentes.');">Desactivar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay categorías registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>