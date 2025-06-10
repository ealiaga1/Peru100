<?php // app/views/caja/list_sales.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <p>Aquí se mostrará el listado de ventas. Aún no se ha implementado el método para obtener todas las ventas en el modelo.</p>
    <a href="<?php echo BASE_URL; ?>caja" class="btn btn-secondary">Volver a Caja</a>
</div>