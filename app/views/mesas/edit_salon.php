<?php // app/views/mesas/edit_salon.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error-message"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>mesas/updateSalon" method="POST">
        <input type="hidden" name="id_salon" value="<?php echo htmlspecialchars($salon['id_salon']); ?>">

        <div class="form-group">
            <label for="nombre_salon">Nombre del Salón:</label>
            <input type="text" id="nombre_salon" name="nombre_salon" value="<?php echo htmlspecialchars($salon['nombre_salon']); ?>" required>
        </div>
        <div class="form-group">
            <label for="capacidad_maxima">Capacidad Máxima (Opcional):</label>
            <input type="number" id="capacidad_maxima" name="capacidad_maxima" value="<?php echo htmlspecialchars($salon['capacidad_maxima'] ?? ''); ?>" min="0">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="activo" <?php echo $salon['activo'] ? 'checked' : ''; ?>>
                Activo
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Salón</button>
        <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-secondary">Cancelar</a>
    </form>
</div>