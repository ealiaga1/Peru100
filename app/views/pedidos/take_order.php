<?php // app/views/pedidos/take_order.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>
    <h3>Mesa: <?php echo htmlspecialchars($mesa['numero_mesa']); ?> (Capacidad: <?php echo htmlspecialchars($mesa['capacidad']); ?>)</h3>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <div class="order-interface">
        <div class="products-selection">
            <h4>Seleccionar Productos</h4>
            <div class="product-filters">
                <label for="category-filter">Categoría:</label>
                <select id="category-filter">
                    <option value="all">Todas las Categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>">
                            <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" id="product-search" placeholder="Buscar producto...">
            </div>
            <div class="product-list-grid" id="product-list-grid">
                <?php foreach ($productos as $producto): ?>
                    <div class="product-item" data-id="<?php echo htmlspecialchars($producto['id_producto']); ?>"
                         data-name="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                         data-price="<?php echo htmlspecialchars($producto['precio_venta']); ?>"
                         data-category="<?php echo htmlspecialchars($producto['id_categoria']); ?>">
                        <img src="<?php echo BASE_URL; ?>public/img/default_product.png" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                        <p class="product-name"><?php echo htmlspecialchars($producto['nombre_producto']); ?></p>
                        <p class="product-price"><?php echo APP_CURRENCY_SYMBOL; ?><?php echo number_format($producto['precio_venta'], 2); ?></p>
                        <button class="add-to-order-btn" data-id="<?php echo htmlspecialchars($producto['id_producto']); ?>">Añadir</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="order-summary">
            <h4>Detalle del Pedido</h4>
            <ul id="order-items-list">
                <li class="empty-list-message">No hay productos en el pedido.</li>
            </ul>
            <div class="order-total">
                <p>Total: <span id="order-total-amount"><?php echo APP_CURRENCY_SYMBOL; ?>0.00</span></p>
            </div>
            <div class="order-notes">
                <label for="notes">Notas para el pedido:</label>
                <textarea id="notes" rows="3" placeholder="Ej: Sin cebolla, bien cocido..."></textarea>
            </div>
            <button id="send-order-btn" class="btn btn-primary">Enviar Pedido</button>
            <a href="<?php echo BASE_URL; ?>pedidos" class="btn btn-secondary">Cancelar Pedido</a>
        </div>
    </div>
</div>

<script>
    // Declarar BASE_URL y APP_CURRENCY_SYMBOL disponibles para JavaScript
    const BASE_URL_JS = '<?php echo BASE_URL; ?>';
    const APP_CURRENCY_SYMBOL_JS = '<?php echo APP_CURRENCY_SYMBOL; ?>';
    const MESA_ID = <?php echo htmlspecialchars($mesa['id_mesa']); ?>;

    // Datos de productos disponibles (para filtrado y búsqueda en el cliente)
    const ALL_PRODUCTS = <?php echo json_encode($productos); ?>;
    const ALL_CATEGORIES = <?php echo json_encode($categorias); ?>; // Útil si quieres filtrar por nombre de categoría también

    // Inclusión del script principal del módulo de pedidos
    // Esto es temporal, se moverá a public/js/pedidos.js
    // No te preocupes por el contenido de este script por ahora, lo haremos en el siguiente paso.
</script>
<script src="<?php echo BASE_URL; ?>public/js/pedidos.js"></script>

<style>
    .order-interface {
        display: flex;
        gap: 25px;
        flex-wrap: wrap; /* Permite que los elementos se envuelvan en pantallas pequeñas */
    }
    .products-selection, .order-summary {
        flex: 1; /* Ocupan espacio equitativamente */
        min-width: 300px; /* Ancho mínimo antes de envolver */
        background-color: #fdfdfd;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .products-selection {
        max-height: 700px; /* Limita la altura para scroll */
        overflow-y: auto; /* Habilita scroll si es necesario */
        display: flex;
        flex-direction: column;
    }
    .product-filters {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .product-filters select, .product-filters input {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .product-list-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px;
        padding-top: 10px;
        flex-grow: 1; /* Permite que el grid crezca */
    }
    .product-item {
        border: 1px solid #eee;
        border-radius: 6px;
        text-align: center;
        padding: 10px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-item img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .product-item .product-name {
        font-weight: bold;
        margin: 0 0 5px 0;
        font-size: 0.9em;
        line-height: 1.2;
    }
    .product-item .product-price {
        color: #28a745;
        font-size: 1.1em;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .product-item .add-to-order-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        width: 100%;
    }
    .product-item .add-to-order-btn:hover {
        background-color: #0056b3;
    }

    .order-summary {
        position: sticky;
        top: 20px; /* Se queda fijo al hacer scroll */
    }
    #order-items-list {
        list-style: none;
        padding: 0;
        margin: 0;
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
        max-height: 400px; /* Limita la altura de la lista de ítems */
        overflow-y: auto;
    }
    #order-items-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-top: 1px dashed #eee;
    }
    #order-items-list li:first-child {
        border-top: none;
    }
    #order-items-list .item-info {
        flex-grow: 1;
    }
    #order-items-list .item-info .item-name {
        font-weight: bold;
    }
    #order-items-list .item-actions {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    #order-items-list .item-actions input {
        width: 40px;
        text-align: center;
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    #order-items-list .remove-item-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 5px 8px;
        cursor: pointer;
        font-size: 0.8em;
    }
    .order-total {
        text-align: right;
        font-size: 1.4em;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    .order-notes textarea {
        width: calc(100% - 22px);
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: vertical;
        min-height: 60px;
        margin-top: 5px;
    }
    .empty-list-message {
        text-align: center;
        color: #888;
        padding: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .order-interface {
            flex-direction: column;
        }
        .products-selection, .order-summary {
            min-width: 100%;
            max-height: none;
            overflow-y: visible;
        }
        .order-summary {
            position: static; /* No sticky en pantallas pequeñas */
        }
    }
    @media (max-width: 600px) {
        .product-list-grid {
            grid-template-columns: 1fr; /* Una columna en móviles */
        }
    }
</style>