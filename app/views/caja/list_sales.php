<?php ob_start(); ?>
<h2 class="mb-4"><?php echo $title; ?></h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
<?php endif; ?>

<p>Aquí se mostrará el listado de ventas. Aún no se ha implementado el método para obtener todas las ventas en el modelo.</p>
<a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Volver a Caja</a>
<?php
$content = ob_get_clean();
$title = 'Listado de Ventas';
include __DIR__ . '/../layout.php';