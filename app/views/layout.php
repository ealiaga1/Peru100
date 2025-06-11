<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'Sistema de Gestión'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
        .footer {
            background: #1b1f23;
            color: #fff;
            padding: 25px 0;
            text-align: center;
            margin-top: 40px;
        }
        .container-content {
            min-height: 75vh;
            padding-top: 30px;
            padding-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">Peru100</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>caja">Caja</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pedidos/list">Pedidos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>productos">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>config">Configuración</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>logout"><i class="bi bi-box-arrow-right"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container container-content">
        <?php echo $content; ?>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <small>&copy; <?php echo date('Y'); ?> Peru100 | Sistema desarrollado por ealiaga1</small>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>