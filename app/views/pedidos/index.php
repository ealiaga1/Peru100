<?php // app/views/pedidos/index.php ?>

<div class="container">
    <h2><?php echo $title; ?></h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <div class="salones-grid">
        <?php if (!empty($mesas_por_salon)): ?>
            <?php foreach ($mesas_por_salon as $nombre_salon => $mesas_en_salon): ?>
                <div class="salon-card">
                    <h3><?php echo htmlspecialchars($nombre_salon); ?></h3>
                    <div class="mesas-grid">
                        <?php foreach ($mesas_en_salon as $mesa): ?>
                            <a href="<?php echo BASE_URL; ?>pedidos/takeOrder/<?php echo htmlspecialchars($mesa['id_mesa']); ?>"
                               class="mesa-card estado-<?php echo strtolower(str_replace(' ', '-', $mesa['estado'])); ?>">
                                <h4>Mesa <?php echo htmlspecialchars($mesa['numero_mesa']); ?></h4>
                                <p>Capacidad: <?php echo htmlspecialchars($mesa['capacidad']); ?></p>
                                <p>Estado: <?php echo htmlspecialchars($mesa['estado']); ?></p>
                                </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay mesas configuradas. Por favor, configure mesas en el módulo de Mesas.</p>
        <?php endif; ?>
    </div>
</div>

<style>
    .salones-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .salon-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        width: 100%;
        max-width: 450px;
        box-sizing: border-box;
    }
    .salon-card h3 {
        text-align: center;
        margin-top: 0;
        color: #555;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .mesas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }
    .mesa-card {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 15px;
        text-align: center;
        text-decoration: none;
        color: #333;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .mesa-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .mesa-card h4 {
        margin: 0 0 8px 0;
        font-size: 1.1em;
        color: #343a40;
    }
    .mesa-card p {
        margin: 0;
        font-size: 0.9em;
        color: #6c757d;
    }

    /* Colores de estado de mesa */
    .estado-libre { background-color: #d4edda; border-color: #28a745; } /* Verde claro */
    .estado-libre:hover { background-color: #c3e6cb; }
    .estado-ocupada { background-color: #ffe6e6; border-color: #dc3545; } /* Rojo claro */
    .estado-ocupada:hover { background-color: #f5c6cb; }
    .estado-reservada { background-color: #fff3cd; border-color: #ffc107; } /* Amarillo claro */
    .estado-reservada:hover { background-color: #ffe8a1; }
    .estado-en-limpieza { background-color: #e2e6ea; border-color: #6c757d; } /* Gris claro */
    .estado-en-limpieza:hover { background-color: #d6d8db; }
    /* Añade más estados si los usas */
</style>