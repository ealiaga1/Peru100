<?php // app/views/mesas/create_mesa.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error-message"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>mesas/storeMesa" method="POST">
        <div class="form-group">
            <label for="numero_mesa">Número de Mesa:</label>
            <input type="text" id="numero_mesa" name="numero_mesa" required>
        </div>
        <div class="form-group">
            <label for="capacidad">Capacidad:</label>
            <input type="number" id="capacidad" name="capacidad" min="1" required>
        </div>
        <div class="form-group">
            <label for="id_salon">Salón:</label>
            <select id="id_salon" name="id_salon" required>
                <option value="">Seleccione un salón</option>
                <?php foreach ($salones as $salon): ?>
                    <option value="<?php echo htmlspecialchars($salon['id_salon']); ?>">
                        <?php echo htmlspecialchars($salon['nombre_salon']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Mesa</button>
        <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-secondary">Cancelar</a>
    </form>
</div>