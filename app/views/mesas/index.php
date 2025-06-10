<?php // app/views/mesas/index.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <h3>Salones</h3>
    <a href="<?php echo BASE_URL; ?>mesas/createSalon" class="btn btn-primary">Crear Nuevo Salón</a>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Capacidad Máxima</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($salones)): ?>
                <?php foreach ($salones as $salon): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($salon['id_salon']); ?></td>
                        <td><?php echo htmlspecialchars($salon['nombre_salon']); ?></td>
                        <td><?php echo htmlspecialchars($salon['capacidad_maxima'] ?? 'N/A'); ?></td>
                        <td><?php echo $salon['activo'] ? 'Activo' : 'Inactivo'; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>mesas/editSalon/<?php echo htmlspecialchars($salon['id_salon']); ?>" class="btn btn-edit">Editar</a>
                            <a href="<?php echo BASE_URL; ?>mesas/deleteSalon/<?php echo htmlspecialchars($salon['id_salon']); ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de desactivar este salón?');">Desactivar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay salones registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Mesas</h3>
    <a href="<?php echo BASE_URL; ?>mesas/createMesa" class="btn btn-primary">Crear Nueva Mesa</a>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Número</th>
                <th>Capacidad</th>
                <th>Salón</th>
                <th>Estado Actual</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($mesas)): ?>
                <?php foreach ($mesas as $mesa): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mesa['id_mesa']); ?></td>
                        <td><?php echo htmlspecialchars($mesa['numero_mesa']); ?></td>
                        <td><?php echo htmlspecialchars($mesa['capacidad']); ?></td>
                        <td><?php echo htmlspecialchars($mesa['nombre_salon']); ?></td>
                        <td><?php echo htmlspecialchars($mesa['estado']); ?></td>
                        <td><?php echo $mesa['activo'] ? 'Sí' : 'No'; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>mesas/editMesa/<?php echo htmlspecialchars($mesa['id_mesa']); ?>" class="btn btn-edit">Editar</a>
                            <a href="<?php echo BASE_URL; ?>mesas/deleteMesa/<?php echo htmlspecialchars($mesa['id_mesa']); ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de desactivar esta mesa?');">Desactivar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay mesas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>