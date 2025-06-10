<?php // app/views/inventario/index.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <div class="inventory-dashboard-grid">
        <a href="<?php echo BASE_URL; ?>inventario/productos" class="dashboard-card">
            <h3>Gestión de Productos</h3>
            <p>Administra los ítems de tu menú.</p>
        </a>
        <a href="<?php echo BASE_URL; ?>inventario/ingredientes" class="dashboard-card">
            <h3>Gestión de Ingredientes</h3>
            <p>Controla tus materias primas y existencias.</p>
        </a>
        <a href="<?php echo BASE_URL; ?>inventario/categorias" class="dashboard-card">
            <h3>Gestión de Categorías</h3>
            <p>Organiza tus productos por tipo.</p>
        </a>
        <a href="<?php echo BASE_URL; ?>inventario/unidadesMedida" class="dashboard-card">
            <h3>Gestión de Unidades de Medida</h3>
            <p>Define las unidades para productos e ingredientes.</p>
        </a>
        </div>
</div>

<style>
    .inventory-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }
    .dashboard-card {
        background-color: #fefefe;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 25px;
        text-align: center;
        text-decoration: none;
        color: #333;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .dashboard-card h3 {
        color: #007bff;
        margin-top: 0;
        font-size: 1.5em;
    }
    .dashboard-card p {
        font-size: 0.95em;
        color: #666;
    }
</style>