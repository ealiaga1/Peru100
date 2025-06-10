<?php
// core/Controller.php

class Controller {
    protected $db; // Variable para almacenar la conexión PDO
    protected $user_id;
    protected $username;
    protected $role; // Nombre del rol del usuario logueado

    public function __construct() {
        // Asegurarse de que la sesión esté iniciada para poder acceder a $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Obtiene la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();

        // Carga los datos del usuario logueado a las propiedades del controlador
        $this->user_id = $_SESSION['user_id'] ?? null;
        $this->username = $_SESSION['username'] ?? null;
        $this->role = $_SESSION['role'] ?? null;
    }

    /**
     * Carga una vista y le pasa datos.
     * @param string $viewName La ruta del archivo de la vista (ej. 'dashboard/index')
     * @param array $data Un array asociativo de datos a pasar a la vista
     */
    protected function view($viewName, $data = []) {
        extract($data); // Convierte las claves del array $data en variables

        $viewPath = ROOT_PATH . 'app/views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            require_once ROOT_PATH . 'app/views/layouts/header.php'; // Incluir cabecera
            require_once $viewPath; // Incluir la vista específica
            require_once ROOT_PATH . 'app/views/layouts/footer.php'; // Incluir pie de página
        } else {
            http_response_code(404);
            echo "<h1>Error 404</h1><p>Vista no encontrada: " . htmlspecialchars($viewName) . "</p>";
            exit();
        }
    }

    /**
     * Redirige al navegador a una URL diferente.
     * @param string $path La ruta relativa dentro de tu BASE_URL (ej. 'dashboard', 'auth/login')
     */
    protected function redirect($path) {
        header('Location: ' . BASE_URL . $path);
        exit();
    }

    /**
     * Verifica si el usuario está logueado y si tiene uno de los roles requeridos.
     * Si no cumple los requisitos, redirige al login o a una página de acceso denegado.
     * @param array $allowedRoles Array de nombres de roles permitidos (ej. ['Super Admin', 'Cajero'])
     * Si es vacío, solo verifica que esté logueado.
     */
    protected function requireAuth($allowedRoles = []) {
        // 1. Verificar si el usuario está logueado
        if (!$this->user_id) {
            $_SESSION['error_message'] = 'Necesitas iniciar sesión para acceder a esta sección.';
            $this->redirect('auth/login');
        }

        // 2. Si se especifican roles, verificar si el rol del usuario está permitido
        if (!empty($allowedRoles)) {
            if (!in_array($this->role, $allowedRoles)) {
                // El rol del usuario no está en la lista de roles permitidos
                $_SESSION['error_message'] = 'No tienes permisos para acceder a esta sección.';
                // Podrías redirigir a una página de "Acceso Denegado" específica
                // o al dashboard con un mensaje.
                $this->redirect('dashboard'); // Redirigir al dashboard por simplicidad
            }
        }
        // Si llega aquí, el usuario está logueado y tiene el rol permitido (si se especificó alguno)
        return true;
    }
}