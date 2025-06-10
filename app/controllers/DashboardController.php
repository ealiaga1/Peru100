<?php
// app/controllers/DashboardController.php

class DashboardController extends Controller {

    public function __construct() {
        parent::__construct(); // Llamar al constructor del padre para inicializar $db y las variables de sesión
        // No necesitamos cargar un modelo específico aquí si solo vamos a verificar la sesión.
    }

    public function index() {
        // Llama a requireAuth() para asegurar que el usuario esté logueado.
        // Como el array está vacío, solo verifica que user_id exista.
        $this->requireAuth();

        $data['title'] = 'Dashboard Principal';
        $data['welcome_message'] = 'Bienvenido al panel de administración de tu restaurante.';
        
        $this->view('dashboard/index', $data);
    }

    // Puedes agregar otros métodos para el dashboard aquí, protegiéndolos si es necesario
    public function reportesDiarios() {
        // Este método podría requerir un rol específico, por ejemplo, 'Super Admin' o 'Cajero'
        $this->requireAuth(['Super Admin', 'Cajero']);
        $data['title'] = 'Reportes Diarios';
        $data['message'] = 'Contenido de los reportes diarios.';
        $this->view('dashboard/reportes_diarios', $data);
    }
}