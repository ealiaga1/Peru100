
<?php // app/views/mesas/edit_salon.php ?>
<div class="container mt-4">
    <h2 class="mb-4"><?php echo $title; ?></h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>mesas/updateSalon" method="POST" class="card p-4 shadow-sm">
        <input type="hidden" name="id_salon" value="<?php echo $salon['id_salon']; ?>">

        <div class="mb-3">
            <label for="nombre_salon" class="form-label">Nombre del Salón</label>
            <input type="text" id="nombre_salon" name="nombre_salon" class="form-control" required value="<?php echo htmlspecialchars($salon['nombre_salon']); ?>">
        </div>

        <div class="mb-3">
            <label for="capacidad_maxima" class="form-label">Capacidad Máxima</label>
            <input type="number" id="capacidad_maxima" name="capacidad_maxima" class="form-control" min="1" value="<?php echo htmlspecialchars($salon['capacidad_maxima']); ?>">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" id="activo" name="activo" class="form-check-input" <?php if ($salon['activo']) echo 'checked'; ?>>
            <label class="form-check-label" for="activo">Activo</label>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Salón</button>
        </div>
    </form>
</div>
