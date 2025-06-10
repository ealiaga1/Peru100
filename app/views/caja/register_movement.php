<?php // app/views/caja/register_movement.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <?php if (!$openCaja): ?>
        <p class="error-message">No hay una caja abierta para registrar movimientos.</p>
        <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Volver a Gestión de Caja</a>
    <?php else: ?>
        <p><strong>Caja Actual:</strong> ID #<?php echo htmlspecialchars($openCaja['id_caja']); ?> (Abierta desde: <?php echo htmlspecialchars($openCaja['fecha_apertura']); ?>)</p>

        <form action="<?php echo BASE_URL; ?>caja/storeMovement" method="POST">
            <div class="form-group">
                <label for="id_tipo_movimiento">Tipo de Movimiento:</label>
                <select id="id_tipo_movimiento" name="id_tipo_movimiento" required>
                    <option value="">Seleccione un tipo</option>
                    <?php foreach ($tipos_movimiento as $tipo): ?>
                        <option value="<?php echo htmlspecialchars($tipo['id_tipo_movimiento']); ?>">
                            <?php echo htmlspecialchars($tipo['nombre_tipo']); ?> (<?php echo htmlspecialchars($tipo['tipo']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="monto">Monto (S/):</label>
                <input type="number" id="monto" name="monto" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="referencia_externa">Referencia Externa (Opcional):</label>
                <input type="text" id="referencia_externa" name="referencia_externa" placeholder="Ej: N° recibo, motivo">
            </div>
            <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
            <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Cancelar</a>
        </form>
    <?php endif; ?>
</div>
<style>
    /* Reutiliza los estilos del formulario de create_salon.php */
    .form-container { /* Sobrescribe si es necesario para esta vista específica */ }
</style>