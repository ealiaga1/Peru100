<?php // app/views/auth/register.php ?>

<div class="auth-container">
    <h2><?php echo $title; ?></h2>
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>auth/store" method="POST">
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="form-group">
            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" required>
        </div>
        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono (Opcional):</label>
            <input type="text" id="telefono" name="telefono">
        </div>
        <div class="form-group">
            <label for="correo">Correo (Opcional):</label>
            <input type="email" id="correo" name="correo">
        </div>
        <div class="form-group">
            <label for="id_rol">Rol:</label>
            <select id="id_rol" name="id_rol" required>
                <option value="">Seleccione un rol</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo htmlspecialchars($role['id_rol']); ?>">
                        <?php echo htmlspecialchars($role['nombre_rol']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Registrar Usuario</button>
    </form>
    <p><a href="<?php echo BASE_URL; ?>auth/login">Volver al Login</a></p>
</div>