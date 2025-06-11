<?php ob_start(); ?>
<h2 class="mb-4"><?php echo $title; ?></h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>caja/storeSale" method="POST">
    <?php if ($pedido): ?>
        <input type="hidden" name="id_pedido" value="<?php echo htmlspecialchars($pedido['id_pedido']); ?>">
        <div class="alert alert-info mb-3">
            <h5>Pedido Referenciado: #<?php echo htmlspecialchars($pedido['id_pedido']); ?></h5>
            <p>Mesa: <?php echo htmlspecialchars($pedido['numero_mesa']); ?></p>
            <p>Mesero: <?php echo htmlspecialchars($pedido['mesero_nombre']); ?></p>
            <p>Total del Pedido: <span id="pedido-total-display"><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($total_pedido, 2); ?></span></p>
            <p>Estado: <?php echo htmlspecialchars($pedido['estado_pedido']); ?></p>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <label for="total_venta" class="form-label">Total a Vender (S/):</label>
        <input type="number" id="total_venta" name="total_venta" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($total_pedido); ?>" required <?php echo ($pedido ? 'readonly' : ''); ?>>
    </div>
    <div class="mb-3">
        <label for="monto_recibido" class="form-label">Monto Recibido (S/):</label>
        <input type="number" id="monto_recibido" name="monto_recibido" class="form-control" step="0.01" min="0" required>
    </div>
    <div class="mb-3">
        <label for="cambio" class="form-label">Cambio (S/):</label>
        <input type="text" id="cambio" name="cambio" class="form-control" readonly value="0.00">
    </div>
    <div class="mb-3">
        <label for="id_metodo_pago" class="form-label">Método de Pago:</label>
        <select id="id_metodo_pago" name="id_metodo_pago" class="form-select" required>
            <option value="">Seleccione un método</option>
            <?php foreach ($metodos_pago as $metodo): ?>
                <option value="<?php echo htmlspecialchars($metodo['id_metodo_pago']); ?>">
                    <?php echo htmlspecialchars($metodo['nombre_metodo']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
        <select id="tipo_documento" name="tipo_documento" class="form-select" required>
            <option value="">Seleccione un tipo</option>
            <option value="Ticket">Ticket</option>
            <option value="Boleta">Boleta</option>
            <option value="Factura">Factura</option>
        </select>
    </div>
    <div class="mb-3" id="numero_documento_group" style="display: none;">
        <label for="numero_documento" class="form-label">Número de Documento (interno):</label>
        <input type="text" id="numero_documento" name="numero_documento" class="form-control" placeholder="Ej: B001-000123">
    </div>

    <div id="cliente_info_section" style="display: none;">
        <h5>Datos del Cliente (para Boleta/Factura)</h5>
        <div class="mb-3">
            <label for="cliente_tipo_doc" class="form-label">Tipo Doc. Cliente:</label>
            <select id="cliente_tipo_doc" name="cliente_tipo_doc" class="form-select">
                <option value="">Seleccione</option>
                <option value="DNI">DNI</option>
                <option value="RUC">RUC</option>
                <option value="CE">Carnet Extranjería</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="cliente_num_doc" class="form-label">Número Doc. Cliente:</label>
            <input type="text" id="cliente_num_doc" name="cliente_num_doc" class="form-control">
        </div>
        <div class="mb-3">
            <label for="cliente_razon_social" class="form-label">Nombre/Razón Social:</label>
            <input type="text" id="cliente_razon_social" name="cliente_razon_social" class="form-control">
        </div>
        <div class="mb-3">
            <label for="cliente_direccion" class="form-label">Dirección (Opcional):</label>
            <input type="text" id="cliente_direccion" name="cliente_direccion" class="form-control">
        </div>
        <div class="mb-3">
            <label for="cliente_telefono" class="form-label">Teléfono (Opcional):</label>
            <input type="text" id="cliente_telefono" name="cliente_telefono" class="form-control">
        </div>
        <div class="mb-3">
            <label for="cliente_correo" class="form-label">Correo (Opcional):</label>
            <input type="email" id="cliente_correo" name="cliente_correo" class="form-control">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Registrar Venta</button>
    <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Cancelar</a>
</form>
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
        cambioInput.style.color = (cambio < 0) ? 'red' : 'green';
    }

    totalVentaInput.addEventListener('input', calculateCambio);
    montoRecibidoInput.addEventListener('input', calculateCambio);

    tipoDocumentoSelect.addEventListener('change', function() {
        const selectedDocType = this.value;
        if (selectedDocType === 'Boleta' || selectedDocType === 'Factura') {
            clienteInfoSection.style.display = 'block';
            numeroDocumentoGroup.style.display = 'block';
        } else {
            clienteInfoSection.style.display = 'none';
            numeroDocumentoGroup.style.display = 'none';
            document.getElementById('cliente_tipo_doc').value = '';
            document.getElementById('cliente_num_doc').value = '';
            document.getElementById('cliente_razon_social').value = '';
            document.getElementById('cliente_direccion').value = '';
            document.getElementById('cliente_telefono').value = '';
            document.getElementById('cliente_correo').value = '';
            document.getElementById('numero_documento').value = '';
        }
    });
    calculateCambio();
    tipoDocumentoSelect.dispatchEvent(new Event('change'));
});
</script>
<?php
$content = ob_get_clean();
$title = 'Registrar Venta';
include __DIR__ . '/../layout.php';