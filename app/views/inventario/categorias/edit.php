<?php // app/views/inventario/categorias/edit.php ?>

<div class="form-container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>inventario/updateCategoria" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>">
        
        <div class="form-group">
            <label for="nombre">Nombre de Categoría:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción (Opcional):</label>
            <textarea id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($categoria['descripcion'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="activo" <?php echo $categoria['activo'] ? 'checked' : ''; ?>>
                Activa
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
        <a href="<?php echo BASE_URL; ?>inventario/categorias" class="btn btn-secondary">Cancelar</a>
    </form>
</div>