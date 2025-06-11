<?php
// app/controllers/UserController.php

class UserController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        require_once ROOT_PATH . 'app/models/UserModel.php';
        $this->userModel = new UserModel();
    }

    // Muestra la lista de usuarios
    public function index() {
        $this->requireAuth(['Super Admin']); // Solo Super Admin puede gestionar usuarios

        $data['title'] = 'Gestión de Usuarios';
        $data['users'] = $this->userModel->getAllUsers(false); // Obtener todos, incluyendo inactivos

        // Mensajes de sesión
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('users/index', $data);
    }

    // Muestra el formulario para crear un nuevo usuario
    public function create() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Crear Nuevo Usuario';
        $data['roles'] = $this->userModel->getAllRoles(true); // Obtener roles activos
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('users/create', $data);
    }

    // Procesa el envío del formulario para crear un usuario
    public function store() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $nombres = trim($_POST['nombres'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $id_rol = (int)$_POST['id_rol'] ?? 0;

            // Validación
            if (empty($nombre_usuario) || empty($password) || empty($confirm_password) || empty($nombres) || empty($apellidos) || $id_rol <= 0) {
                $_SESSION['error_message'] = 'Todos los campos obligatorios deben ser llenados.';
                $this->redirect('user/create');
            }
            if ($password !== $confirm_password) {
                $_SESSION['error_message'] = 'Las contraseñas no coinciden.';
                $this->redirect('user/create');
            }
            if ($this->userModel->userExists($nombre_usuario, $correo)) {
                $_SESSION['error_message'] = 'El nombre de usuario o correo ya existen.';
                $this->redirect('user/create');
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if ($this->userModel->createUser($nombre_usuario, $hashedPassword, $nombres, $apellidos, $id_rol, $telefono, $correo)) {
                $_SESSION['success_message'] = 'Usuario creado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al crear el usuario.';
            }
            $this->redirect('user'); // Redirige al listado de usuarios
        } else {
            $this->redirect('user');
        }
    }

    // Muestra el formulario para editar un usuario
    public function edit($id) {
        $this->requireAuth(['Super Admin']);
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            $_SESSION['error_message'] = 'Usuario no encontrado.';
            $this->redirect('user');
        }
        $data['title'] = 'Editar Usuario: ' . htmlspecialchars($user['nombre_usuario']);
        $data['user'] = $user;
        $data['roles'] = $this->userModel->getAllRoles(true);
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('users/edit', $data);
    }

    // Procesa el envío del formulario para actualizar un usuario
    public function update() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'] ?? 0;
            $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $nombres = trim($_POST['nombres'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $id_rol = (int)$_POST['id_rol'] ?? 0;
            $activo = isset($_POST['activo']) ? 1 : 0;

            if ($id <= 0 || empty($nombre_usuario) || empty($nombres) || empty($apellidos) || $id_rol <= 0) {
                $_SESSION['error_message'] = 'Datos inválidos para actualizar usuario.';
                $this->redirect('user/edit/' . $id);
            }
            if ($this->userModel->userExists($nombre_usuario, $correo, $id)) {
                $_SESSION['error_message'] = 'El nombre de usuario o correo ya existen para otro usuario.';
                $this->redirect('user/edit/' . $id);
            }

            if ($this->userModel->updateUser($id, $nombre_usuario, $nombres, $apellidos, $id_rol, $activo, $telefono, $correo)) {
                $_SESSION['success_message'] = 'Usuario actualizado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar el usuario.';
            }
            $this->redirect('user');
        } else {
            $this->redirect('user');
        }
    }

    // Procesa el restablecimiento de contraseña
    public function resetPassword($id) {
        $this->requireAuth(['Super Admin']);
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            $_SESSION['error_message'] = 'Usuario no encontrado.';
            $this->redirect('user');
        }
        $data['title'] = 'Restablecer Contraseña para ' . htmlspecialchars($user['nombre_usuario']);
        $data['user_id'] = $id;
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('users/reset_password', $data);
    }

    public function updateNewPassword() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'] ?? 0;
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if ($id <= 0 || empty($new_password) || empty($confirm_password)) {
                $_SESSION['error_message'] = 'Contraseña y confirmación son obligatorias.';
                $this->redirect('user/resetPassword/' . $id);
            }
            if ($new_password !== $confirm_password) {
                $_SESSION['error_message'] = 'Las contraseñas no coinciden.';
                $this->redirect('user/resetPassword/' . $id);
            }

            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            if ($this->userModel->updatePassword($id, $hashedPassword)) {
                $_SESSION['success_message'] = 'Contraseña actualizada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar la contraseña.';
            }
            $this->redirect('user');
        } else {
            $this->redirect('user');
        }
    }

    // Desactiva un usuario
    public function deactivate($id) {
        $this->requireAuth(['Super Admin']);
        if ($this->user_id == $id) { // Evita que un admin se desactive a sí mismo
            $_SESSION['error_message'] = 'No puedes desactivar tu propia cuenta.';
            $this->redirect('user');
        }
        if ($this->userModel->deactivateUser($id)) {
            $_SESSION['success_message'] = 'Usuario desactivado exitosamente.';
        } else {
            $_SESSION['error_message'] = 'Error al desactivar el usuario.';
        }
        $this->redirect('user');
    }

    // Activa un usuario
    public function activate($id) {
        $this->requireAuth(['Super Admin']);
        if ($this->userModel->activateUser($id)) {
            $_SESSION['success_message'] = 'Usuario activado exitosamente.';
        } else {
            $_SESSION['error_message'] = 'Error al activar el usuario.';
        }
        $this->redirect('user');
    }
}