<?php // app/views/caja/view_sale.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($venta)): ?>
        <div class="print-header">
            <?php if (!empty($company_config['logo_url'])): ?>
                <img src="<?php echo BASE_URL . htmlspecialchars($company_config['logo_url']); ?>" alt="Logo Empresa">
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
        <div class="sale-details-summary">
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
            <table class="data-table">
                <thead>
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
                            <td><?php 
                                $igv_monto = $venta['total_venta'] * ($company_config['igv_porcentaje'] / 100);
                                echo APP_CURRENCY_SYMBOL . number_format($igv_monto, 2); 
                            ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right;">Base Imponible:</td>
                            <td><?php 
                                $base_imponible = $venta['total_venta'] / (1 + ($company_config['igv_porcentaje'] / 100));
                                echo APP_CURRENCY_SYMBOL . number_format($base_imponible, 2); 
                            ?></td>
                        </tr>
                    <?php endif; ?>
                </tfoot>
            </table>
        <?php endif; ?>

        <div class="sale-actions">
            <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Volver a Caja</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir Comprobante</button>
        </div>

        <div class="print-footer">
            <p><?php echo htmlspecialchars($company_config['mensaje_factura'] ?? '¡Gracias por su compra!'); ?></p>
            <p>Visítanos en: <?php echo htmlspecialchars($company_config['sitio_web'] ?? BASE_URL); ?></p>
        </div>
        <?php else: ?>
        <p>La venta solicitada no fue encontrada.</p>
    <?php endif; ?>
</div>

<style>
    /* Estilos para la visualización en pantalla */
    .sale-details-summary {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .sale-details-summary p {
        margin: 5px 0;
        font-size: 1.1em;
    }
    .sale-actions {
        margin-top: 30px;
        text-align: right;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    /* Ocultar elementos de impresión en pantalla */
    .print-header, .print-footer {
        display: none;
    }

    /* --- Estilos para la impresión (Media Query) --- */
    @media print {
        body * {
            visibility: hidden; /* Oculta todo por defecto */
        }
        /* Hacer visibles solo los elementos que queremos imprimir */
        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
            background-color: #fff; /* Fondo blanco para impresión */
        }
        .container *, 
        .sale-details-summary, .sale-details-summary *, 
        .data-table, .data-table *,
        h2, h3, p, span, strong, div, table, thead, tbody, tfoot, tr, th, td {
            visibility: visible;
            color: #000; /* Asegura texto negro para impresión */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Consistencia de fuente */
        }
        
        /* Ajustes específicos para impresión */
        .sale-actions {
            display: none; /* Ocultar botones de acción en la impresión */
        }
        .header-content, footer, nav { /* Asegura que la cabecera y pie de página de la web no se impriman */
            display: none;
        }

        /* Hacer visibles y estilizar los elementos de impresión */
        .print-header, .print-footer {
            display: block; /* Hacerlos visibles */
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #aaa; /* Línea separadora */
        }
        .print-header img {
            max-width: 80px; /* Tamaño del logo en impresión */
            height: auto;
            margin-bottom: 5px;
        }
        .print-header h3 {
            margin: 5px 0;
            font-size: 1.2em;
        }
        .print-header p {
            margin: 1px 0;
            font-size: 0.8em;
        }
        .print-footer {
            margin-top: 15px;
            padding-top: 5px;
            border-top: 1px dashed #aaa;
            font-size: 0.8em;
            color: #333;
        }
        
        /* Ajustes de tabla para impresión */
        .data-table {
            border: 1px solid #000;
            width: 100%;
            font-size: 0.9em;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 5px;
        }
        .data-table tfoot strong {
            font-size: 1em;
        }
    }
</style>