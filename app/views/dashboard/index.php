<?php // app/views/dashboard/index.php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .dashboard-header {
            margin-bottom: 2rem;
        }
        .card-title i {
            font-size: 1.5rem;
        }
        .card-text.display-6 {
            font-weight: bold;
        }
        canvas {
            max-height: 300px;
        }
    </style>
</head>
<body>
    <div class="container mt-5" style="margin-left: 250px;">
        <div class="dashboard-header text-center">
            <h2><?php echo $welcome_message ?? 'Bienvenido al Sistema'; ?></h2>
            <p class="text-muted">Resumen general</p>
        </div>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Accesos rápidos -->
        <div class="mb-4 text-center">
            <a href="<?php echo BASE_URL; ?>pedidos" class="btn btn-outline-primary me-2">
                <i class="bi bi-receipt"></i> Ver Pedidos
            </a>
            <a href="<?php echo BASE_URL; ?>mesas" class="btn btn-outline-secondary me-2">
                <i class="bi bi-table"></i> Ver Mesas
            </a>
            <a href="<?php echo BASE_URL; ?>productos" class="btn btn-outline-success">
                <i class="bi bi-box-seam"></i> Inventario
            </a>
        </div>

        <!-- Resumen principal -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-cash-coin me-2"></i>Ventas Hoy</h5>
                        <p class="card-text display-6">S/. <?php echo number_format($ventasHoy ?? 0, 2); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-receipt-cutoff me-2"></i>Pedidos Activos</h5>
                        <p class="card-text display-6"><?php echo $pedidosActivos ?? 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-warning">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-box me-2"></i>Inventario Total</h5>
                        <p class="card-text display-6"><?php echo $stockTotal ?? 0; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda fila de tarjetas -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card text-bg-info">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-person me-2"></i>Clientes Registrados</h5>
                        <p class="card-text display-6"><?php echo $totalClientes ?? 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-dark">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-building me-2"></i>Salones Activos</h5>
                        <p class="card-text display-6"><?php echo $salonesActivos ?? 0; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de ventas -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-bar-chart-line me-2"></i>Gráfico de Ventas Semanales</h5>
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Gráfico de pedidos por categoría -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-pie-chart-fill me-2"></i>Pedidos por Categoría</h5>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Ventas (S/.)',
                    data: [120, 90, 140, 75, 200, 130, 180],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const ctx2 = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Comidas', 'Bebidas', 'Postres'],
                datasets: [{
                    data: [120, 90, 60],
                    backgroundColor: ['#198754', '#0d6efd', '#ffc107']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
