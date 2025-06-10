<?php // app/views/mesas/edit_mesa.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error-message"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>mesas/updateMesa" method="POST">
        <input type="hidden" name="id_mesa" value="<?php echo htmlspecialchars($mesa['id_mesa']); ?>">

        <div class="form-group">
            <label for="numero_mesa">Número de Mesa:</label>
            <input type="text" id="numero_mesa" name="numero_mesa" value="<?php echo htmlspecialchars($mesa['numero_mesa']); ?>" required>
        </div>
        <div class="form-group">
            <label for="capacidad">Capacidad:</label>
            <input type="number" id="capacidad" name="capacidad" value="<?php echo htmlspecialchars($mesa['capacidad']); ?>" min="1" required>
        </div>
        <div class="form-group">
            <label for="id_salon">Salón:</label>
            <select id="id_salon" name="id_salon" required>
                <option value="">Seleccione un salón</option>
                <?php foreach ($salones as $salon): ?>
                    <option value="<?php echo htmlspecialchars($salon['id_salon']); ?>" <?php echo ($salon['id_salon'] == $mesa['id_salon']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($salon['nombre_salon']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="estado">Estado Actual:</label>
            <select id="estado" name="estado" required>
                <?php 
                $estados_mesa = ['Libre', 'Ocupada', 'Reservada', 'En Limpieza'];
                foreach ($estados_mesa as $estado_opcion):
                ?>
                    <option value="<?php echo htmlspecialchars($estado_opcion); ?>" <?php echo ($estado_opcion == $mesa['estado']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($estado_opcion); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="activo" <?php echo $mesa['activo'] ? 'checked' : ''; ?>>
                Activo
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Mesa</button>
        <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-secondary">Cancelar</a>
    </form>
</div>