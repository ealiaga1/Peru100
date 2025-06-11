<?php ob_start(); ?>
<h2 class="mb-4"><?php echo $title; ?></h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
<?php endif; ?>

<?php if (!empty($venta)): ?>
    <div class="print-header d-none d-print-block text-center mb-3">
        <?php if (!empty($company_config['logo_url'])): ?>
            <img src="<?php echo BASE_URL . htmlspecialchars($company_config['logo_url']); ?>" alt="Logo Empresa" style="max-width:80px;">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($company_config['nombre_empresa'] ?? 'Tu Restaurante'); ?></h3>
        <p><?php echo htmlspecialchars($company_config['razon_social'] ?? ''); ?></p>
        <p>RUC: <?php echo htmlspecialchars($company_config['ruc'] ?? ''); ?></p>
        <p><?php echo htmlspecialchars($company_config['direccion'] ?? ''); ?></p>
        <p>Tel: <?php echo htmlspecialchars($company_config['telefono'] ?? ''); ?></p>
        <p>Correo: <?php echo htmlspecialchars($company_config['correo'] ?? ''); ?></p>
        <hr>
        <p><strong>Comprobante: <?php echo htmlspecialchars($venta['tipo_documento'] ?? 'Ticket'); ?> - <?php echo htmlspecialchars($venta['numero_documento'] ?? 'N/A'); ?></strong></p>
        <p>Fecha: <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($venta['fecha_hora_venta']))); ?></p>
        <hr>
    </div>
    <div class="sale-details-summary card p-4 mb-4">
        <h3>Detalles de la Venta #<?php echo htmlspecialchars($venta['id_venta']); ?></h3>
        <p><strong>Tipo Documento:</strong> <?php echo htmlspecialchars($venta['tipo_documento']); ?></p>
        <?php if (!empty($venta['numero_documento'])): ?>
            <p><strong>Número Documento:</strong> <?php echo htmlspecialchars($venta['numero_documento']); ?></p>
        <?php endif; ?>
        <p><strong>Fecha/Hora Venta:</strong> <?php echo htmlspecialchars($venta['fecha_hora_venta']); ?></p>
        <p><strong>Total Venta:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($venta['total_venta'], 2); ?></p>
        <p><strong>Monto Recibido:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($venta['monto_recibido'], 2); ?></p>
        <p><strong>Cambio:</strong> <?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($venta['cambio'], 2); ?></p>
        <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($venta['nombre_metodo']); ?></p>
        <p><strong>Cajero:</strong> <?php echo htmlspecialchars($venta['cajero_nombre']); ?></p>
        <?php if ($venta['id_pedido']): ?>
            <p><strong>Referencia Pedido:</strong> <a href="<?php echo BASE_URL; ?>pedidos/viewOrder/<?php echo htmlspecialchars($venta['id_pedido']); ?>">#<?php echo htmlspecialchars($venta['id_pedido']); ?></a></p>
        <?php endif; ?>
        <?php if ($venta['id_cliente']): ?>
            <h4>Datos del Cliente</h4>
            <p><strong>Nombre/Razón Social:</strong> <?php echo htmlspecialchars($venta['cliente_nombre'] ?? 'N/A'); ?></p>
            <p><strong>Tipo Doc:</strong> <?php echo htmlspecialchars($venta['cliente_tipo_documento'] ?? 'N/A'); ?></p>
            <p><strong>Número Doc:</strong> <?php echo htmlspecialchars($venta['cliente_numero_documento'] ?? 'N/A'); ?></p>
        <?php endif; ?>
    </div>
    <?php if (!empty($pedido_details) && !empty($pedido_details['detalles'])): ?>
        <h3>Productos Vendidos</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedido_details['detalles'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                            <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                            <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($item['precio_unitario'], 2); ?></td>
                            <td><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total Venta:</strong></td>
                        <td><strong><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($venta['total_venta'], 2); ?></strong></td>
                    </tr>
                    <?php if (isset($company_config['igv_porcentaje']) && $company_config['igv_porcentaje'] > 0): ?>
                        <tr>
                            <td colspan="3" style="text-align: right;">IGV (<?php echo htmlspecialchars($company_config['igv_porcentaje']); ?>%):</td>
                            <td>
                                <?php
                                    $igv_monto = $venta['total_venta'] * ($company_config['igv_porcentaje'] / 100);
                                    echo APP_CURRENCY_SYMBOL . number_format($igv_monto, 2);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right;">Base Imponible:</td>
                            <td>
                                <?php
                                    $base_imponible = $venta['total_venta'] / (1 + ($company_config['igv_porcentaje'] / 100));
                                    echo APP_CURRENCY_SYMBOL . number_format($base_imponible, 2);
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>

    <div class="sale-actions mt-4">
        <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Volver a Caja</a>
        <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir Comprobante</button>
    </div>

    <div class="print-footer d-none d-print-block text-center mt-3">
        <p><?php echo htmlspecialchars($company_config['mensaje_factura'] ?? '¡Gracias por su compra!'); ?></p>
        <p>Visítanos en: <?php echo htmlspecialchars($company_config['sitio_web'] ?? BASE_URL); ?></p>
    </div>
<?php else: ?>
    <div class="alert alert-warning">La venta solicitada no fue encontrada.</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
$title = 'Detalle de Venta';
include __DIR__ . '/../layout.php';