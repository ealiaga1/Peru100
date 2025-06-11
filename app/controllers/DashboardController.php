<?php
// app/controllers/DashboardController.php

class DashboardController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
    }

    public function index() {
        $data['title'] = 'Dashboard Principal';
        $data['welcome_message'] = 'Bienvenido al panel de administración de tu restaurante.';

        // Datos simulados (puedes reemplazarlos más adelante con datos reales desde los modelos)
        $data['ventas_hoy'] = 345.50; // S/. de ventas
        $data['pedidos_activos'] = 8;
        $data['inventario_total'] = 124;

        $data['ventas_por_dia'] = [
            'Lun' => 120,
            'Mar' => 90,
            'Mié' => 140,
            'Jue' => 75,
            'Vie' => 200,
            'Sáb' => 130,
            'Dom' => 180,
        ];

        $this->view('dashboard/index', $data);
    }

    public function reportesDiarios() {
        $this->requireAuth(['Super Admin', 'Cajero']);
        $data['title'] = 'Reportes Diarios';
        $data['message'] = 'Contenido de los reportes diarios.';
        $this->view('dashboard/reportes_diarios', $data);
    }
}
