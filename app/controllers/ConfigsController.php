<?php
// app/controllers/ConfigsController.php

class ConfigsController extends Controller { // Cambiado a ConfigsController
    private $configModel;

    public function __construct() {
        parent::__construct();
        require_once ROOT_PATH . 'app/models/ConfigsModel.php'; // Cargando ConfigsModel
        $this->configModel = new ConfigsModel(); // Instanciando ConfigsModel
    }

    // Muestra el formulario para editar la configuración de la empresa
    public function index() {
        $this->requireAuth(['Super Admin']); // Solo Super Admin puede configurar la empresa

        $data['title'] = 'Configuración de la Empresa';
        $config = $this->configModel->getCompanyConfig();

        // Si no hay configuración inicial, crear una por defecto
        if (!$config) {
            $initial_data = [
                'nombre_empresa'    => 'Nombre de tu Restaurante S.A.C.',
                'razon_social'      => 'Razon Social S.A.C.',
                'ruc'               => '20XXXXXXXXX', // RUC de ejemplo
                'direccion'         => 'Av. Principal #123, Ciudad',
                'telefono'          => '987654321',
                'correo'            => 'info@tudominio.com',
                'sitio_web'         => 'www.tudominio.com',
                'logo_url'          => 'public/img/logo.png', // Ruta por defecto para el logo
                'moneda_simbolo'    => 'S/', // Símbolo de soles peruanos
                'mensaje_factura'   => '¡Gracias por su preferencia!',
                'igv_porcentaje'    => 18.00 // IGV en Perú
            ];
            $this->configModel->createInitialConfig($initial_data);
            $config = $this->configModel->getCompanyConfig(); // Vuelve a obtenerla
        }

        $data['config'] = $config;
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('config/index', $data);
    }

    // Procesa el envío del formulario para actualizar la configuración
    public function update() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre_empresa'    => trim($_POST['nombre_empresa'] ?? ''),
                'razon_social'      => trim($_POST['razon_social'] ?? ''),
                'ruc'               => trim($_POST['ruc'] ?? ''),
                'direccion'         => trim($_POST['direccion'] ?? ''),
                'telefono'          => trim($_POST['telefono'] ?? ''),
                'correo'            => trim($_POST['correo'] ?? ''),
                'sitio_web'         => trim($_POST['sitio_web'] ?? ''),
                'logo_url'          => trim($_POST['logo_url'] ?? ''),
                'moneda_simbolo'    => trim($_POST['moneda_simbolo'] ?? 'S/'),
                'mensaje_factura'   => trim($_POST['mensaje_factura'] ?? ''),
                'igv_porcentaje'    => filter_input(INPUT_POST, 'igv_porcentaje', FILTER_VALIDATE_FLOAT)
            ];

            // Validación básica
            if (empty($data['nombre_empresa']) || empty($data['direccion']) || empty($data['telefono']) || $data['igv_porcentaje'] === false || $data['igv_porcentaje'] < 0) {
                $_SESSION['error_message'] = 'Nombre de empresa, dirección, teléfono e IGV son obligatorios.';
                $this->redirect('config');
            }

            if ($this->configModel->updateCompanyConfig($data)) {
                $_SESSION['success_message'] = 'Configuración actualizada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar la configuración.';
            }
            $this->redirect('config');
        } else {
            $this->redirect('config');
        }
    }
}