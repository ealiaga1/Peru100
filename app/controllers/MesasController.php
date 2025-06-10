<?php
// app/controllers/MesasController.php

class MesasController extends Controller {
    private $mesasModel;

    public function __construct() {
        parent::__construct(); // Llama al constructor del padre
        require_once ROOT_PATH . 'app/models/MesasModel.php'; // Carga el modelo
        $this->mesasModel = new MesasModel(); // Instancia el modelo
    }

    // Muestra la lista de salones y mesas (Índice principal del módulo)
    public function index() {
        $this->requireAuth(['Super Admin']); // Solo Super Admin puede gestionar mesas

        $data['title'] = 'Gestión de Mesas y Salones';
        $data['salones'] = $this->mesasModel->getAllSalones();
        $data['mesas'] = $this->mesasModel->getAllMesas();

        // Obtener y limpiar mensajes de error/éxito si existen
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('mesas/index', $data);
    }

    // --- Acciones para Salones ---

    // Muestra el formulario para crear un nuevo salón
    public function createSalon() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Crear Nuevo Salón';
        $this->view('mesas/create_salon', $data);
    }

    // Procesa el envío del formulario para crear un salón
    public function storeSalon() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_salon = trim($_POST['nombre_salon'] ?? '');
            $capacidad_maxima = !empty($_POST['capacidad_maxima']) ? (int)$_POST['capacidad_maxima'] : null;

            if (empty($nombre_salon)) {
                $_SESSION['error_message'] = 'El nombre del salón no puede estar vacío.';
                $this->redirect('mesas/createSalon');
            }

            if ($this->mesasModel->salonExists($nombre_salon)) {
                $_SESSION['error_message'] = 'Ya existe un salón con ese nombre.';
                $this->redirect('mesas/createSalon');
            }

            if ($this->mesasModel->createSalon($nombre_salon, $capacidad_maxima)) {
                $_SESSION['success_message'] = 'Salón creado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al crear el salón.';
            }
            $this->redirect('mesas');
        } else {
            $this->redirect('mesas');
        }
    }

    // Muestra el formulario para editar un salón
    public function editSalon($id_salon) {
        $this->requireAuth(['Super Admin']);
        $salon = $this->mesasModel->getSalonById($id_salon);

        if (!$salon) {
            $_SESSION['error_message'] = 'Salón no encontrado.';
            $this->redirect('mesas');
        }

        $data['title'] = 'Editar Salón: ' . htmlspecialchars($salon['nombre_salon']);
        $data['salon'] = $salon;
        $this->view('mesas/edit_salon', $data);
    }

    // Procesa el envío del formulario para actualizar un salón
    public function updateSalon() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_salon = (int)$_POST['id_salon'] ?? 0;
            $nombre_salon = trim($_POST['nombre_salon'] ?? '');
            $capacidad_maxima = !empty($_POST['capacidad_maxima']) ? (int)$_POST['capacidad_maxima'] : null;
            $activo = isset($_POST['activo']) ? 1 : 0; // Checkbox

            if (empty($nombre_salon) || $id_salon <= 0) {
                $_SESSION['error_message'] = 'Datos inválidos para actualizar el salón.';
                $this->redirect('mesas');
            }

            if ($this->mesasModel->salonExists($nombre_salon, $id_salon)) {
                $_SESSION['error_message'] = 'Ya existe otro salón con ese nombre.';
                $this->redirect('mesas/editSalon/' . $id_salon);
            }

            if ($this->mesasModel->updateSalon($id_salon, $nombre_salon, $capacidad_maxima, $activo)) {
                $_SESSION['success_message'] = 'Salón actualizado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar el salón.';
            }
            $this->redirect('mesas');
        } else {
            $this->redirect('mesas');
        }
    }

    // Desactiva lógicamente un salón
    public function deleteSalon($id_salon) {
        $this->requireAuth(['Super Admin']);
        if ($this->mesasModel->deleteSalon($id_salon)) {
            $_SESSION['success_message'] = 'Salón desactivado exitosamente.';
        } else {
            $_SESSION['error_message'] = 'Error al desactivar el salón.';
        }
        $this->redirect('mesas');
    }

    // --- Acciones para Mesas ---

    // Muestra el formulario para crear una nueva mesa
    public function createMesa() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Crear Nueva Mesa';
        $data['salones'] = $this->mesasModel->getAllSalones(); // Necesitamos los salones para el select
        $this->view('mesas/create_mesa', $data);
    }

    // Procesa el envío del formulario para crear una mesa
    public function storeMesa() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_mesa = trim($_POST['numero_mesa'] ?? '');
            $capacidad = (int)$_POST['capacidad'] ?? 0;
            $id_salon = (int)$_POST['id_salon'] ?? 0;

            if (empty($numero_mesa) || $capacidad <= 0 || $id_salon <= 0) {
                $_SESSION['error_message'] = 'Todos los campos obligatorios para la mesa deben ser llenados.';
                $this->redirect('mesas/createMesa');
            }
            
            if ($this->mesasModel->mesaExistsInSalon($numero_mesa, $id_salon)) {
                $_SESSION['error_message'] = 'Ya existe una mesa con ese número en este salón.';
                $this->redirect('mesas/createMesa');
            }

            if ($this->mesasModel->createMesa($numero_mesa, $capacidad, $id_salon)) {
                $_SESSION['success_message'] = 'Mesa creada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al crear la mesa.';
            }
            $this->redirect('mesas');
        } else {
            $this->redirect('mesas');
        }
    }

    // Muestra el formulario para editar una mesa
    public function editMesa($id_mesa) {
        $this->requireAuth(['Super Admin']);
        $mesa = $this->mesasModel->getMesaById($id_mesa);

        if (!$mesa) {
            $_SESSION['error_message'] = 'Mesa no encontrada.';
            $this->redirect('mesas');
        }

        $data['title'] = 'Editar Mesa: ' . htmlspecialchars($mesa['numero_mesa']);
        $data['mesa'] = $mesa;
        $data['salones'] = $this->mesasModel->getAllSalones();
        $this->view('mesas/edit_mesa', $data);
    }

    // Procesa el envío del formulario para actualizar una mesa
    public function updateMesa() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_mesa = (int)$_POST['id_mesa'] ?? 0;
            $numero_mesa = trim($_POST['numero_mesa'] ?? '');
            $capacidad = (int)$_POST['capacidad'] ?? 0;
            $id_salon = (int)$_POST['id_salon'] ?? 0;
            $estado = $_POST['estado'] ?? 'Libre';
            $activo = isset($_POST['activo']) ? 1 : 0;

            if (empty($numero_mesa) || $capacidad <= 0 || $id_salon <= 0 || $id_mesa <= 0) {
                $_SESSION['error_message'] = 'Datos inválidos para actualizar la mesa.';
                $this->redirect('mesas');
            }
            
            if ($this->mesasModel->mesaExistsInSalon($numero_mesa, $id_salon, $id_mesa)) {
                $_SESSION['error_message'] = 'Ya existe otra mesa con ese número en este salón.';
                $this->redirect('mesas/editMesa/' . $id_mesa);
            }

            if ($this->mesasModel->updateMesa($id_mesa, $numero_mesa, $capacidad, $id_salon, $estado, $activo)) {
                $_SESSION['success_message'] = 'Mesa actualizada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar la mesa.';
            }
            $this->redirect('mesas');
        } else {
            $this->redirect('mesas');
        }
    }

    // Desactiva lógicamente una mesa
    public function deleteMesa($id_mesa) {
        $this->requireAuth(['Super Admin']);
        if ($this->mesasModel->deleteMesa($id_mesa)) {
            $_SESSION['success_message'] = 'Mesa desactivada exitosamente.';
        } else {
            $_SESSION['error_message'] = 'Error al desactivar la mesa.';
        }
        $this->redirect('mesas');
    }
}
