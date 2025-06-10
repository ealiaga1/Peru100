<?php // app/views/caja/register_sale.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>caja/storeSale" method="POST">
        <?php if ($pedido): // Si se está facturando un pedido existente ?>
            <input type="hidden" name="id_pedido" value="<?php echo htmlspecialchars($pedido['id_pedido']); ?>">
            <div class="info-block">
                <h3>Pedido Referenciado: #<?php echo htmlspecialchars($pedido['id_pedido']); ?></h3>
                <p>Mesa: <?php echo htmlspecialchars($pedido['numero_mesa']); ?></p>
                <p>Mesero: <?php echo htmlspecialchars($pedido['mesero_nombre']); ?></p>
                <p>Total del Pedido: <span id="pedido-total-display"><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($total_pedido, 2); ?></span></p>
                <p>Estado: <?php echo htmlspecialchars($pedido['estado_pedido']); ?></p>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="total_venta">Total a Vender (S/):</label>
            <input type="number" id="total_venta" name="total_venta" step="0.01" min="0" value="<?php echo htmlspecialchars($total_pedido); ?>" required <?php echo ($pedido ? 'readonly' : ''); ?>>
        </div>
        <div class="form-group">
            <label for="monto_recibido">Monto Recibido (S/):</label>
            <input type="number" id="monto_recibido" name="monto_recibido" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label for="cambio">Cambio (S/):</label>
            <input type="text" id="cambio" name="cambio" readonly value="0.00">
        </div>
        <div class="form-group">
            <label for="id_metodo_pago">Método de Pago:</label>
            <select id="id_metodo_pago" name="id_metodo_pago" required>
                <option value="">Seleccione un método</option>
                <?php foreach ($metodos_pago as $metodo): ?>
                    <option value="<?php echo htmlspecialchars($metodo['id_metodo_pago']); ?>">
                        <?php echo htmlspecialchars($metodo['nombre_metodo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo_documento">Tipo de Documento:</label>
            <select id="tipo_documento" name="tipo_documento" required>
                <option value="">Seleccione un tipo</option>
                <option value="Ticket">Ticket</option>
                <option value="Boleta">Boleta</option>
                <option value="Factura">Factura</option>
            </select>
        </div>
        <div class="form-group" id="numero_documento_group" style="display: none;">
            <label for="numero_documento">Número de Documento (interno):</label>
            <input type="text" id="numero_documento" name="numero_documento" placeholder="Ej: B001-000123">
        </div>

        <div id="cliente_info_section" style="display: none;">
            <h3>Datos del Cliente (para Boleta/Factura)</h3>
            <div class="form-group">
                <label for="cliente_tipo_doc">Tipo Doc. Cliente:</label>
                <select id="cliente_tipo_doc" name="cliente_tipo_doc">
                    <option value="">Seleccione</option>
                    <option value="DNI">DNI</option>
                    <option value="RUC">RUC</option>
                    <option value="CE">Carnet Extranjería</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cliente_num_doc">Número Doc. Cliente:</label>
                <input type="text" id="cliente_num_doc" name="cliente_num_doc">
            </div>
            <div class="form-group">
                <label for="cliente_razon_social">Nombre/Razón Social:</label>
                <input type="text" id="cliente_razon_social" name="cliente_razon_social">
            </div>
            <div class="form-group">
                <label for="cliente_direccion">Dirección (Opcional):</label>
                <input type="text" id="cliente_direccion" name="cliente_direccion">
            </div>
            <div class="form-group">
                <label for="cliente_telefono">Teléfono (Opcional):</label>
                <input type="text" id="cliente_telefono" name="cliente_telefono">
            </div>
            <div class="form-group">
                <label for="cliente_correo">Correo (Opcional):</label>
                <input type="email" id="cliente_correo" name="cliente_correo">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Venta</button>
        <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalVentaInput = document.getElementById('total_venta');
        const montoRecibidoInput = document.getElementById('monto_recibido');
        const cambioInput = document.getElementById('cambio');
        const tipoDocumentoSelect = document.getElementById('tipo_documento');
        const numeroDocumentoGroup = document.getElementById('numero_documento_group');
        const clienteInfoSection = document.getElementById('cliente_info_section');

        function calculateCambio() {
            let total = parseFloat(totalVentaInput.value) || 0;
            let recibido = parseFloat(montoRecibidoInput.value) || 0;
            let cambio = recibido - total;
            cambioInput.value = cambio.toFixed(2);
            if (cambio < 0) {
                cambioInput.style.color = 'red';
            } else {
                cambioInput.style.color = 'green';
            }
        }

        totalVentaInput.addEventListener('input', calculateCambio);
        montoRecibidoInput.addEventListener('input', calculateCambio);

        tipoDocumentoSelect.addEventListener('change', function() {
            const selectedDocType = this.value;
            if (selectedDocType === 'Boleta' || selectedDocType === 'Factura') {
                clienteInfoSection.style.display = 'block';
                numeroDocumentoGroup.style.display = 'block'; // Mostrar si se necesita número de documento interno
            } else {
                clienteInfoSection.style.display = 'none';
                numeroDocumentoGroup.style.display = 'none';
                // Limpiar campos de cliente si no son necesarios
                document.getElementById('cliente_tipo_doc').value = '';
                document.getElementById('cliente_num_doc').value = '';
                document.getElementById('cliente_razon_social').value = '';
                document.getElementById('cliente_direccion').value = '';
                document.getElementById('cliente_telefono').value = '';
                document.getElementById('cliente_correo').value = '';
                document.getElementById('numero_documento').value = ''; // Limpiar número de documento interno
            }
        });

        // Llama una vez al cargar para establecer el estado inicial
        calculateCambio();
        tipoDocumentoSelect.dispatchEvent(new Event('change')); // Dispara el evento para ocultar/mostrar secciones al cargar
    });
</script>
<style>
    /* Reutiliza los estilos del formulario de create_salon.php (o crea un archivo de estilos para formularios) */
    .form-container { /* Sobrescribe si es necesario para esta vista específica */ }
    .info-block {
        background-color: #e9f5ff;
        border: 1px solid #cce5ff;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .info-block h3 {
        margin-top: 0;
        color: #007bff;
        border-bottom: 1px dashed #a3d0ed;
        padding-bottom: 10px;
    }
</style>