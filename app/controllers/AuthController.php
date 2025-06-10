

    // Procesa el intento de login
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $_SESSION['error_message'] = 'Por favor, ingrese usuario y contraseña.';
                $this->redirect('auth/login');
            }

            $user = $this->userModel->findByUsername($username);

            if ($user && $this->userModel->verifyPassword($password, $user['contrasena_hash'])) {
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['username'] = $user['nombre_usuario'];
                $_SESSION['role'] = $user['nombre_rol'];
                $_SESSION['nombres_completos'] = $user['nombres'] . ' ' . $user['apellidos'];

                $this->db->prepare("UPDATE usuarios SET ultima_sesion = NOW() WHERE id_usuario = ?")->execute([$user['id_usuario']]);

                unset($_SESSION['error_message']);
                $this->redirect('dashboard');
            } else {
                $_SESSION['error_message'] = 'Usuario o contraseña incorrectos.';
                $this->redirect('auth/login');
            }
        } else {
            $this->redirect('auth/login');
        }
    }

    // Cierra la sesión del usuario
    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('auth/login');
    }
}
