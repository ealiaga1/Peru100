<?php // app/views/config/index.php ?>
<div class="container mt-4">
  <h2 class="mb-4"><?php echo $title; ?></h2>

  <?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
  <?php endif; ?>
  <?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
  <?php endif; ?>

  <form action="<?php echo BASE_URL; ?>config/update" method="POST" class="row g-3">
    <div class="col-md-6 form-floating">
      <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="<?php echo htmlspecialchars($config['nombre_empresa'] ?? ''); ?>" required>
      <label for="nombre_empresa">Nombre de la Empresa</label>
    </div>

    <div class="col-md-6 form-floating">
      <input type="text" class="form-control" id="razon_social" name="razon_social" value="<?php echo htmlspecialchars($config['razon_social'] ?? ''); ?>">
      <label for="razon_social">Razón Social (Opcional)</label>
    </div>

    <div class="col-md-6 form-floating">
      <input type="text" class="form-control" id="ruc" name="ruc" value="<?php echo htmlspecialchars($config['ruc'] ?? ''); ?>">
      <label for="ruc">RUC (Opcional)</label>
    </div>

    <div class="col-md-6 form-floating">
      <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($config['direccion'] ?? ''); ?>" required>
      <label for="direccion">Dirección</label>
    </div>

    <div class="col-md-6 form-floating">
      <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($config['telefono'] ?? ''); ?>" required>
      <label for="telefono">Teléfono</label>
    </div>

    <div class="col-md-6 form-floating">
      <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($config['correo'] ?? ''); ?>">
      <label for="correo">Correo Electrónico (Opcional)</label>
    </div>

    <div class="col-md-6 form-floating">
      <input type="url" class="form-control" id="sitio_web" name="sitio_web" value="<?php echo htmlspecialchars($config['sitio_web'] ?? ''); ?>">
      <label for="sitio_web">Sitio Web (Opcional)</label>
    </div>

    <div class="col-md-6 form-floating">
      <input type="text" class="form-control" id="logo_url" name="logo_url" value="<?php echo htmlspecialchars($config['logo_url'] ?? ''); ?>" placeholder="Ej: public/img/tu_logo.png">
      <label for="logo_url">URL del Logo (Opcional)</label>
    </div>

    <?php if (!empty($config['logo_url'])): ?>
      <div class="col-12">
        <img src="<?php echo BASE_URL . htmlspecialchars($config['logo_url']); ?>" alt="Logo actual" style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
      </div>
    <?php endif; ?>

    <div class="col-md-3 form-floating">
      <input type="text" class="form-control" id="moneda_simbolo" name="moneda_simbolo" value="<?php echo htmlspecialchars($config['moneda_simbolo'] ?? 'S/'); ?>" required maxlength="5">
      <label for="moneda_simbolo">Símbolo de Moneda</label>
    </div>

    <div class="col-md-3 form-floating">
      <input type="number" class="form-control" id="igv_porcentaje" name="igv_porcentaje" step="0.01" min="0" max="100" value="<?php echo htmlspecialchars($config['igv_porcentaje'] ?? '18.00'); ?>" required>
      <label for="igv_porcentaje">IGV(%)</label>
    </div>

    <div class="col-12 form-floating">
      <textarea class="form-control" placeholder="Mensaje de factura" id="mensaje_factura" name="mensaje_factura" style="height: 100px"><?php echo htmlspecialchars($config['mensaje_factura'] ?? ''); ?></textarea>
      <label for="mensaje_factura">Mensaje de Factura (Opcional)</label>
    </div>

    <div class="col-12 text-end">
      <button type="submit" class="btn btn-primary px-4">Guardar Configuración</button>
    </div>
  </form>
</div>