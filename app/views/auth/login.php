<?php // app/views/auth/login.php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .login-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Logo" class="login-logo">
        <h4 class="text-center mb-3"><?php echo $title; ?></h4>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>auth/authenticate" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </div>
        </form>
    </div>
</body>
</html>