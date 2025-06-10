<?php ob_start(); ?>
<h2 class="mb-4"><?php echo $title; ?></h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
<?php endif; ?>

<h3>Salones</h3>
<a href="<?php echo BASE_URL; ?>mesas/createSalon" class="btn btn-primary mb-2">Crear Nuevo Salón</a>
<div class="table-responsive mb-4">
    <table class="table table-striped align-middle">
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
                            <a href="<?php echo BASE_URL; ?>mesas/editSalon/<?php echo htmlspecialchars($salon['id_salon']); ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?php echo BASE_URL; ?>mesas/deleteSalon/<?php echo htmlspecialchars($salon['id_salon']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este salón?');">Eliminar</a>
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
</div>

<h3>Mesas</h3>
<a href="<?php echo BASE_URL; ?>mesas/createMesa" class="btn btn-primary mb-2">Crear Nueva Mesa</a>
<div class="table-responsive">
    <table class="table table-striped align-middle">
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
                            <a href="<?php echo BASE_URL; ?>mesas/editMesa/<?php echo htmlspecialchars($mesa['id_mesa']); ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?php echo BASE_URL; ?>mesas/deleteMesa/<?php echo htmlspecialchars($mesa['id_mesa']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta mesa?');">Eliminar</a>
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
<?php
$content = ob_get_clean();
$title = "Gestión de Mesas y Salones";
include __DIR__ . '/../layout.php';
