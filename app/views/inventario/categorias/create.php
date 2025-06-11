<?php // app/views/inventario/categorias/create.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>inventario/storeCategoria" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre de Categoría:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción (Opcional):</label>
            <textarea id="descripcion" name="descripcion" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Categoría</button>
        <a href="<?php echo BASE_URL; ?>inventario/categorias" class="btn btn-secondary">Cancelar</a>
    </form>
</div>