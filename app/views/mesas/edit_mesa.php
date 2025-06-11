<?php // app/views/mesas/edit_mesa.php ?>
<div class="container mt-4">
    <h2 class="mb-4"><?php echo $title; ?></h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>mesas/updateMesa" method="POST" class="card p-4 shadow-sm">
        <input type="hidden" name="id_mesa" value="<?php echo $mesa['id_mesa']; ?>">

        <div class="mb-3">
            <label for="numero_mesa" class="form-label">Número de Mesa</label>
            <input type="text" id="numero_mesa" name="numero_mesa" class="form-control" required value="<?php echo htmlspecialchars($mesa['numero_mesa']); ?>">
        </div>

        <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad</label>
            <input type="number" id="capacidad" name="capacidad" class="form-control" min="1" required value="<?php echo htmlspecialchars($mesa['capacidad']); ?>">
        </div>

        <div class="mb-3">
            <label for="id_salon" class="form-label">Salón</label>
            <select id="id_salon" name="id_salon" class="form-select" required>
                <?php foreach ($salones as $salon): ?>
                    <option value="<?php echo $salon['id_salon']; ?>" <?php if ($salon['id_salon'] == $mesa['id_salon']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($salon['nombre_salon']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" name="estado" class="form-select">
                <option value="Libre" <?php if ($mesa['estado'] === 'Libre') echo 'selected'; ?>>Libre</option>
                <option value="Ocupada" <?php if ($mesa['estado'] === 'Ocupada') echo 'selected'; ?>>Ocupada</option>
            </select>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" id="activo" name="activo" class="form-check-input" <?php if ($mesa['activo']) echo 'checked'; ?>>
            <label class="form-check-label" for="activo">Activa</label>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Mesa</button>
        </div>
    </form>
</div>
