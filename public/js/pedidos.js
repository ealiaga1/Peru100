// public/js/pedidos.js

document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('category-filter');
    const productSearch = document.getElementById('product-search');
    const productListGrid = document.getElementById('product-list-grid');
    const orderItemsList = document.getElementById('order-items-list');
    const orderTotalAmount = document.getElementById('order-total-amount');
    const emptyListMessage = document.querySelector('.empty-list-message');
    const sendOrderBtn = document.getElementById('send-order-btn');
    const orderNotes = document.getElementById('notes');

    let currentOrderItems = []; // Array para almacenar los ítems del pedido

    // Función para mostrar los productos en el grid
    function displayProducts(productsToDisplay) {
        productListGrid.innerHTML = ''; // Limpiar la lista actual

        if (productsToDisplay.length === 0) {
            productListGrid.innerHTML = '<p style="text-align: center; color: #888;">No se encontraron productos.</p>';
            return;
        }

        productsToDisplay.forEach(product => {
            const productItem = document.createElement('div');
            productItem.className = 'product-item';
            productItem.dataset.id = product.id_producto;
            productItem.dataset.name = product.nombre_producto;
            productItem.dataset.price = product.precio_venta;
            productItem.dataset.category = product.id_categoria;

            productItem.innerHTML = `
                <img src="${BASE_URL_JS}public/img/default_product.png" alt="${product.nombre_producto}">
                <p class="product-name">${product.nombre_producto}</p>
                <p class="product-price">${APP_CURRENCY_SYMBOL_JS}${parseFloat(product.precio_venta).toFixed(2)}</p>
                <button class="add-to-order-btn" data-id="${product.id_producto}">Añadir</button>
            `;
            productListGrid.appendChild(productItem);
        });
    }

    // Filtra y busca productos
    function filterAndSearchProducts() {
        const selectedCategoryId = categoryFilter.value;
        const searchTerm = productSearch.value.toLowerCase();

        const filteredProducts = ALL_PRODUCTS.filter(product => {
            const matchesCategory = selectedCategoryId === 'all' || product.id_categoria == selectedCategoryId;
            const matchesSearch = product.nombre_producto.toLowerCase().includes(searchTerm);
            return matchesCategory && matchesSearch;
        });
        displayProducts(filteredProducts);
    }

    // Calcula y actualiza el total del pedido
    function updateTotal() {
        let total = 0;
        currentOrderItems.forEach(item => {
            total += item.quantity * item.price;
        });
        orderTotalAmount.textContent = `${APP_CURRENCY_SYMBOL_JS}${total.toFixed(2)}`;

        // Mostrar/ocultar mensaje de lista vacía
        if (currentOrderItems.length === 0) {
            emptyListMessage.style.display = 'block';
        } else {
            emptyListMessage.style.display = 'none';
        }
    }

    // Añade un producto al pedido
    function addProductToOrder(productId, productName, productPrice) {
        const existingItemIndex = currentOrderItems.findIndex(item => item.id === productId);

        if (existingItemIndex > -1) {
            currentOrderItems[existingItemIndex].quantity++;
        } else {
            currentOrderItems.push({
                id: productId,
                name: productName,
                price: parseFloat(productPrice),
                quantity: 1,
                notes: '' // Se puede agregar notas por ítem aquí
            });
        }
        renderOrderItems();
        updateTotal();
    }

    // Elimina un producto del pedido
    function removeProductFromOrder(productId) {
        currentOrderItems = currentOrderItems.filter(item => item.id !== productId);
        renderOrderItems();
        updateTotal();
    }

    // Renderiza los ítems del pedido en la lista
    function renderOrderItems() {
        orderItemsList.innerHTML = ''; // Limpiar la lista actual
        
        // Vuelve a añadir el mensaje de lista vacía si es necesario
        if (currentOrderItems.length === 0) {
            orderItemsList.appendChild(emptyListMessage);
            emptyListMessage.style.display = 'block';
            return;
        } else {
            emptyListMessage.style.display = 'none';
        }

        currentOrderItems.forEach(item => {
            const listItem = document.createElement('li');
            listItem.innerHTML = `
                <div class="item-info">
                    <span class="item-name">${item.name}</span><br>
                    <small>${APP_CURRENCY_SYMBOL_JS}${item.price.toFixed(2)} c/u</small>
                </div>
                <div class="item-actions">
                    <input type="number" class="item-quantity" data-id="${item.id}" value="${item.quantity}" min="1">
                    <button class="remove-item-btn" data-id="${item.id}">X</button>
                </div>
            `;
            orderItemsList.appendChild(listItem);
        });
    }

    // --- Event Listeners ---

    // Filtros de productos y búsqueda
    categoryFilter.addEventListener('change', filterAndSearchProducts);
    productSearch.addEventListener('input', filterAndSearchProducts);

    // Añadir producto al hacer clic en el botón "Añadir"
    productListGrid.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-to-order-btn')) {
            const productId = parseInt(event.target.dataset.id);
            const productItemDiv = event.target.closest('.product-item');
            const productName = productItemDiv.dataset.name;
            const productPrice = productItemDiv.dataset.price;
            addProductToOrder(productId, productName, productPrice);
        }
    });

    // Cambiar cantidad o eliminar ítem en la lista de pedido
    orderItemsList.addEventListener('input', function(event) {
        if (event.target.classList.contains('item-quantity')) {
            const productId = parseInt(event.target.dataset.id);
            const newQuantity = parseInt(event.target.value);
            const itemIndex = currentOrderItems.findIndex(item => item.id === productId);
            if (itemIndex > -1 && newQuantity >= 1) {
                currentOrderItems[itemIndex].quantity = newQuantity;
                updateTotal();
            } else if (newQuantity < 1) {
                // Si la cantidad baja de 1, eliminar el ítem
                removeProductFromOrder(productId);
            }
        }
    });

    orderItemsList.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-item-btn')) {
            const productId = parseInt(event.target.dataset.id);
            removeProductFromOrder(productId);
        }
    });

    // Enviar Pedido al Servidor
    sendOrderBtn.addEventListener('click', function() {
        if (currentOrderItems.length === 0) {
            alert('Por favor, añada al menos un producto al pedido.');
            return;
        }

        const confirmSend = confirm('¿Está seguro de enviar este pedido?');
        if (!confirmSend) {
            return;
        }

        // Crear un formulario oculto para enviar los datos por POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = BASE_URL_JS + 'pedidos/storeOrder';

        // Campo para el ID de la mesa
        const mesaIdInput = document.createElement('input');
        mesaIdInput.type = 'hidden';
        mesaIdInput.name = 'id_mesa';
        mesaIdInput.value = MESA_ID;
        form.appendChild(mesaIdInput);

        // Campo para los ítems del pedido (convertidos a JSON)
        const itemsInput = document.createElement('input');
        itemsInput.type = 'hidden';
        itemsInput.name = 'items';
        itemsInput.value = JSON.stringify(currentOrderItems.map(item => ({
            id_producto: item.id,
            cantidad: item.quantity,
            notas_item: item.notes // Si implementas notas por ítem
        })));
        form.appendChild(itemsInput);

        // Campo para las notas del pedido
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'notas_pedido';
        notesInput.value = orderNotes.value;
        form.appendChild(notesInput);

        document.body.appendChild(form); // Añadir el formulario al DOM
        form.submit(); // Enviar el formulario
    });

    // Inicializar la visualización de productos
    displayProducts(ALL_PRODUCTS);
    updateTotal();
});