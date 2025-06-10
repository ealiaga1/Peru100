<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo APP_NAME . (isset($title) ? ' - ' . $title : ''); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      display: flex;
      height: 100vh;
      margin: 0;
    }
    .sidebar {
      width: 250px;
      background-color: #343a40;
      color: white;
      padding-top: 1rem;
      position: fixed;
      height: 100vh;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 0.75rem 1.25rem;
    }
    .sidebar a:hover, .sidebar .active {
      background-color: #495057;
    }
    .sidebar .logo {
      font-size: 1.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 1rem;
    }
    .main-content {
      margin-left: 250px;
      padding: 2rem;
      flex-grow: 1;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="logo"><?php echo APP_NAME; ?></div>
    <a href="<?php echo BASE_URL; ?>dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo BASE_URL; ?>pedidos"><i class="bi bi-receipt-cutoff"></i> Pedidos</a>
    <a href="<?php echo BASE_URL; ?>caja"><i class="bi bi-cash-coin"></i> Caja</a>
    <a href="<?php echo BASE_URL; ?>inventario"><i class="bi bi-box-seam"></i> Inventario</a>
    <a href="<?php echo BASE_URL; ?>mesas"><i class="bi bi-grid-3x3-gap"></i> Mesas</a>
    <a href="<?php echo BASE_URL; ?>user"><i class="bi bi-people"></i> Usuarios</a>
    <a href="<?php echo BASE_URL; ?>configs"><i class="bi bi-gear"></i> Configuración</a>
    <a href="<?php echo BASE_URL; ?>auth/logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
  </div>
  <div class="main-content">
