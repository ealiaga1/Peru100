<?php // app/views/mesas/create_salon.php ?>

<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?php echo $title; ?></h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>mesas/storeSalon" method="POST">
                <div class="mb-3">
                    <label for="nombre_salon" class="form-label">Nombre del Salón</label>
                    <input type="text" class="form-control" id="nombre_salon" name="nombre_salon" required placeholder="Ej. Salón Principal">
                </div>

                <div class="mb-3">
                    <label for="capacidad_maxima" class="form-label">Capacidad Máxima</label>
                    <input type="number" class="form-control" id="capacidad_maxima" name="capacidad_maxima" min="1" placeholder="Ej. 50">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
