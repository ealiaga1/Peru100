<?php // app/views/mesas/edit_mesa.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error-message"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>mesas/updateMesa" method="POST">
        <input type="hidden" name="id_mesa" value="<?php echo $mesa['id_mesa']; ?>">

        <div class="form-group">
            <label for="numero_mesa">Número de Mesa:</label>
            <input type="text" id="numero_mesa" name="numero_mesa" value="<?php echo htmlspecialchars($mesa['numero_mesa']); ?>" required>
        </div>

        <div class="form-group">
            <label for="capacidad">Capacidad:</label>
            <input type="number" id="capacidad" name="capacidad" value="<?php echo $mesa['capacidad']; ?>" min="1" required>
        </div>

        <div class="form-group">
            <label for="id_salon">Salón:</label>
            <select name="id_salon" id="id_salon" required>
                <?php foreach ($salones as $salon): ?>
                    <option value="<?php echo $salon['id_salon']; ?>" <?php echo $mesa['id_salon'] == $salon['id_salon'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($salon['nombre_salon']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado">
                <option value="Libre" <?php echo $mesa['estado'] == 'Libre' ? 'selected' : ''; ?>>Libre</option>
                <option value="Ocupada" <?php echo $mesa['estado'] == 'Ocupada' ? 'selected' : ''; ?>>Ocupada</option>
                <option value="Reservada" <?php echo $mesa['estado'] == 'Reservada' ? 'selected' : ''; ?>>Reservada</option>
            </select>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="activo" <?php echo $mesa['activo'] ? 'checked' : ''; ?>>
                Activo
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
