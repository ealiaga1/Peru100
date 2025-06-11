<?php // app/views/caja/index.php, ahora usando layout base ?>

<?php ob_start(); ?>
<h2 class="mb-4"><?php echo $title; ?></h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
<?php endif; ?>

<?php if (!$openCaja): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Abrir Caja</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>caja/open" method="POST">
                <div class="mb-3">
                    <label for="monto_inicial" class="form-label">Monto Inicial (S/):</label>
                    <input type="number" id="monto_inicial" name="monto_inicial" class="form-control" step="0.01" min="0" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Abrir Caja</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Caja Abierta: ID #<?php echo htmlspecialchars($openCaja['id_caja']); ?></h5>
        </div>
        <div class="card-body">
            <p><strong>Apertura:</strong> <?php echo htmlspecialchars($openCaja['fecha_apertura']); ?> por <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Monto Inicial:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($openCaja['monto_inicial'], 2); ?></p>
            <p><strong>Total Ingresos:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($total_ingresos, 2); ?></p>
            <p><strong>Total Egresos:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($total_egresos, 2); ?></p>
            <div class="alert alert-info text-center fs-4">
                <strong>Saldo Actual Estimado: <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($saldo_actual, 2); ?></strong>
            </div>

            <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                <a href="<?php echo BASE_URL; ?>caja/registerSale" class="btn btn-primary">
                    <i class="bi bi-cart-plus"></i> Registrar Venta Directa / POS
                </a>
                <a href="<?php echo BASE_URL; ?>pedidos/list" class="btn btn-info">
                    <i class="bi bi-receipt"></i> Facturar Pedido Existente
                </a>
                <a href="<?php echo BASE_URL; ?>caja/registerMovement" class="btn btn-warning">
                    <i class="bi bi-cash-coin"></i> Registrar Ingreso/Egreso
                </a>
            </div>

            <div class="border-top pt-3">
                <h6>Cerrar Caja</h6>
                <form action="<?php echo BASE_URL; ?>caja/close" method="POST" class="row g-2 align-items-end">
                    <input type="hidden" name="id_caja" value="<?php echo htmlspecialchars($openCaja['id_caja']); ?>">
                    <div class="col-md-6">
                        <label for="monto_final" class="form-label">Monto Final Físico (S/):</label>
                        <input type="number" id="monto_final" name="monto_final" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirm('¿Está seguro de CERRAR esta caja? Esta acción no se puede deshacer fácilmente.');">
                            <i class="bi bi-lock-fill"></i> Cerrar Caja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Movimientos de Caja</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Monto</th>
                            <th>Fecha/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($movimientos)): ?>
                            <?php foreach ($movimientos as $mov): ?>
                                <tr class="<?php echo ($mov['tipo_movimiento_nombre'] == 'Ingreso') ? 'table-success' : 'table-danger'; ?>">
                                    <td><?php echo htmlspecialchars($mov['nombre_tipo']); ?></td>
                                    <td><?php echo htmlspecialchars($mov['descripcion']); ?></td>
                                    <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($mov['monto'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($mov['fecha_hora_movimiento']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No hay movimientos registrados en esta caja.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
// El contenido generado se guarda en $content
$content = ob_get_clean();
// El título de la página (puedes modificarlo según tu lógica)
$title = 'Gestión de Caja';
// Llama al layout base y pásale $content y $title
include __DIR__ . '/../layout.php';