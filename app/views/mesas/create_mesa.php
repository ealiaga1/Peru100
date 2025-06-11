<?php // app/views/mesas/create_mesa.php ?>
<div class="container mt-4">
    <h2 class="mb-4"><?php echo $title; ?></h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>mesas/storeMesa" method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="numero_mesa" class="form-label">Número de Mesa</label>
            <input type="text" id="numero_mesa" name="numero_mesa" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad</label>
            <input type="number" id="capacidad" name="capacidad" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label for="id_salon" class="form-label">Salón</label>
            <select id="id_salon" name="id_salon" class="form-select" required>
                <option value="">Seleccione un salón</option>
                <?php foreach ($salones as $salon): ?>
                    <option value="<?php echo $salon['id_salon']; ?>">
                        <?php echo htmlspecialchars($salon['nombre_salon']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="d-flex justify-content-between">
            <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Mesa</button>
        </div>
    </form>
</div>
