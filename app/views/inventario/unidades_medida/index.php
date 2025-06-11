<?php // app/views/inventario/unidades_medida/index.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>inventario/createUnidadMedida" class="btn btn-primary">Crear Nueva Unidad de Medida</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Abreviatura</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($unidades)): ?>
                <?php foreach ($unidades as $unidad): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($unidad['id_unidad']); ?></td>
                        <td><?php echo htmlspecialchars($unidad['nombre_unidad']); ?></td>
                        <td><?php echo htmlspecialchars($unidad['abreviatura']); ?></td>
                        <td><?php echo $unidad['activo'] ? 'Activa' : 'Inactiva'; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>inventario/editUnidadMedida/<?php echo htmlspecialchars($unidad['id_unidad']); ?>" class="btn btn-edit">Editar</a>
                            <?php if ($unidad['activo']): ?>
                                <a href="<?php echo BASE_URL; ?>inventario/deleteUnidadMedida/<?php echo htmlspecialchars($unidad['id_unidad']); ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de desactivar esta unidad de medida?');">Desactivar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay unidades de medida registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>