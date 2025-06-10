
<?php // app/views/users/edit.php ?>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?php echo $title; ?></h4>
        </div>
        <div class="card-body">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>user/update" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id_usuario']); ?>">

                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" value="<?php echo htmlspecialchars($user['nombre_usuario']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="nombres" class="form-label">Nombres</label>
                    <input type="text" id="nombres" name="nombres" class="form-control" value="<?php echo htmlspecialchars($user['nombres']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-control" value="<?php echo htmlspecialchars($user['apellidos']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono (Opcional)</label>
                    <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo (Opcional)</label>
                    <input type="email" id="correo" name="correo" class="form-control" value="<?php echo htmlspecialchars($user['correo'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label for="id_rol" class="form-label">Rol</label>
                    <select id="id_rol" name="id_rol" class="form-select" required>
                        <option value="">Seleccione un rol</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo htmlspecialchars($role['id_rol']); ?>" <?php echo ($role['id_rol'] == $user['id_rol']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role['nombre_rol']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="activo" name="activo" <?php echo $user['activo'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Actualizar Usuario</button>
                    <a href="<?php echo BASE_URL; ?>user" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>