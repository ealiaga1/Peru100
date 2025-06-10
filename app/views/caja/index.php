<?php // app/views/caja/index.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <?php if (!$openCaja): // Si no hay caja abierta ?>
        <div class="card">
            <h3>Abrir Caja</h3>
            <form action="<?php echo BASE_URL; ?>caja/open" method="POST">
                <div class="form-group">
                    <label for="monto_inicial">Monto Inicial (S/):</label>
                    <input type="number" id="monto_inicial" name="monto_inicial" step="0.01" min="0" required>
                </div>
                <button type="submit" class="btn btn-primary">Abrir Caja</button>
            </form>
        </div>
    <?php else: // Si hay caja abierta ?>
        <div class="card card-open-caja">
            <h3>Caja Abierta: ID #<?php echo htmlspecialchars($openCaja['id_caja']); ?></h3>
            <p><strong>Apertura:</strong> <?php echo htmlspecialchars($openCaja['fecha_apertura']); ?> por <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Monto Inicial:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($openCaja['monto_inicial'], 2); ?></p>
            <p><strong>Total Ingresos:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($total_ingresos, 2); ?></p>
            <p><strong>Total Egresos:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($total_egresos, 2); ?></p>
            <p class="saldo-actual"><strong>Saldo Actual Estimado:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($saldo_actual, 2); ?></p>

            <div class="caja-actions">
                <a href="<?php echo BASE_URL; ?>caja/registerSale" class="btn btn-success">Registrar Venta Directa / POS</a>
                <a href="<?php echo BASE_URL; ?>pedidos/list" class="btn btn-info">Facturar Pedido Existente</a>
                <a href="<?php echo BASE_URL; ?>caja/registerMovement" class="btn btn-warning">Registrar Ingreso/Egreso</a>

                <h4 style="margin-top: 30px;">Cerrar Caja</h4>
                <form action="<?php echo BASE_URL; ?>caja/close" method="POST">
                    <input type="hidden" name="id_caja" value="<?php echo htmlspecialchars($openCaja['id_caja']); ?>">
                    <div class="form-group">
                        <label for="monto_final">Monto Final Físico (S/):</label>
                        <input type="number" id="monto_final" name="monto_final" step="0.01" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-delete" onclick="return confirm('¿Está seguro de CERRAR esta caja? Esta acción no se puede deshacer fácilmente.');">Cerrar Caja</button>
                </form>
            </div>

            <h4 style="margin-top: 30px;">Movimientos de Caja</h4>
            <table class="data-table">
                <thead>
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
                            <tr class="<?php echo ($mov['tipo_movimiento_nombre'] == 'Ingreso') ? 'mov-ingreso' : 'mov-egreso'; ?>">
                                <td><?php echo htmlspecialchars($mov['nombre_tipo']); ?></td>
                                <td><?php echo htmlspecialchars($mov['descripcion']); ?></td>
                                <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($mov['monto'], 2); ?></td>
                                <td><?php echo htmlspecialchars($mov['fecha_hora_movimiento']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No hay movimientos registrados en esta caja.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
    .card {
        background-color: #fdfdfd;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    .card h3 {
        text-align: center;
        margin-top: 0;
        color: #333;
        border-bottom: 1px dashed #eee;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .card p {
        font-size: 1.1em;
        margin-bottom: 10px;
    }
    .saldo-actual {
        font-size: 1.5em;
        color: #007bff;
        font-weight: bold;
        text-align: center;
        margin-top: 20px;
        padding: 15px;
        background-color: #e9f5ff;
        border-radius: 5px;
    }
    .caja-actions {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
        text-align: center;
    }
    .caja-actions .btn {
        margin: 10px;
        min-width: 200px;
    }
    .mov-ingreso { color: #28a745; font-weight: bold; } /* Verde para ingresos */
    .mov-egreso { color: #dc3545; font-weight: bold; } /* Rojo para egresos */
    .btn-info { background-color: #17a2b8; }
    .btn-info:hover { background-color: #138496; }
    .btn-warning { background-color: #ffc107; color: #333; }
    .btn-warning:hover { background-color: #e0a800; }
</style>