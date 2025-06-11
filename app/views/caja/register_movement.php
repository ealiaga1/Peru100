<?php ob_start(); ?>
<h2 class="mb-4"><?php echo $title; ?></h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
<?php endif; ?>

<?php if (!$openCaja): ?>
    <div class="alert alert-danger">No hay una caja abierta para registrar movimientos.</div>
    <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Volver a Gestión de Caja</a>
<?php else: ?>
    <p><strong>Caja Actual:</strong> ID #<?php echo htmlspecialchars($openCaja['id_caja']); ?> (Abierta desde: <?php echo htmlspecialchars($openCaja['fecha_apertura']); ?>)</p>
    <form action="<?php echo BASE_URL; ?>caja/storeMovement" method="POST">
        <div class="mb-3">
            <label for="id_tipo_movimiento" class="form-label">Tipo de Movimiento:</label>
            <select id="id_tipo_movimiento" name="id_tipo_movimiento" class="form-select" required>
                <option value="">Seleccione un tipo</option>
                <?php foreach ($tipos_movimiento as $tipo): ?>
                    <option value="<?php echo htmlspecialchars($tipo['id_tipo_movimiento']); ?>">
                        <?php echo htmlspecialchars($tipo['nombre_tipo']); ?> (<?php echo htmlspecialchars($tipo['tipo']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="monto" class="form-label">Monto (S/):</label>
            <input type="number" id="monto" name="monto" class="form-control" step="0.01" min="0.01" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="referencia_externa" class="form-label">Referencia Externa (Opcional):</label>
            <input type="text" id="referencia_externa" name="referencia_externa" class="form-control" placeholder="Ej: N° recibo, motivo">
        </div>
        <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
        <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Cancelar</a>
    </form>
<?php endif; ?>
<?php
$content = ob_get_clean();
$title = 'Registrar Ingreso/Egreso';
include __DIR__ . '/../layout.php';