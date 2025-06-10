<?php // app/views/users/index.php ?>
<div class="container mt-5" style="margin-left: 250px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?php echo $title; ?></h2>
        <a href="<?php echo BASE_URL; ?>user/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
        </a>
    </div>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Rol</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($user['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($user['nombres']); ?></td>
                            <td><?php echo htmlspecialchars($user['apellidos']); ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($user['nombre_rol']); ?></span></td>
                            <td><?php echo htmlspecialchars($user['telefono'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($user['correo'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if ($user['activo']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo BASE_URL; ?>user/edit/<?php echo htmlspecialchars($user['id_usuario']); ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>user/resetPassword/<?php echo htmlspecialchars($user['id_usuario']); ?>" class="btn btn-sm btn-outline-warning me-1" title="Reiniciar Contraseña">
                                    <i class="bi bi-key"></i>
                                </a>
                                <?php if ($user['activo']): ?>
                                    <a href="<?php echo BASE_URL; ?>user/deactivate/<?php echo htmlspecialchars($user['id_usuario']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Está seguro de desactivar este usuario?');" title="Desactivar">
                                        <i class="bi bi-person-x"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL; ?>user/activate/<?php echo htmlspecialchars($user['id_usuario']); ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('¿Está seguro de activar este usuario?');" title="Activar">
                                        <i class="bi bi-person-check"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>