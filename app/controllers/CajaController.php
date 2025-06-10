<?php
// app/controllers/CajaController.php

class CajaController extends Controller {
    private $cajaModel;
    private $pedidoModel; // Para obtener pedidos para facturar
     private $configModel; // <-- Nuevo: para cargar la configuración de la empresa

    public function __construct() {
        parent::__construct();
        require_once ROOT_PATH . 'app/models/CajaModel.php';
        require_once ROOT_PATH . 'app/models/PedidoModel.php';
        require_once ROOT_PATH . 'app/models/ConfigsModel.php'; // <-- Nuevo: Cargar ConfigsModel
        $this->cajaModel = new CajaModel();
        $this->pedidoModel = new PedidoModel();
        $this->configModel = new ConfigsModel(); // <-- Nuevo: Instanciar ConfigsModel
    }

    // Muestra la interfaz principal de caja (apertura/cierre/estado)
    public function index() {
        $this->requireAuth(['Super Admin', 'Cajero']); // Solo Super Admin y Cajero

        $data['title'] = 'Gestión de Caja';
        $data['openCaja'] = $this->cajaModel->getOpenCaja();

        // Si hay una caja abierta, obtener movimientos y saldos
        if ($data['openCaja']) {
            $data['movimientos'] = $this->cajaModel->getMovimientosCaja($data['openCaja']['id_caja']);
            $data['total_ingresos'] = $this->cajaModel->getMovimientosSum($data['openCaja']['id_caja'], 'Ingreso');
            $data['total_egresos'] = $this->cajaModel->getMovimientosSum($data['openCaja']['id_caja'], 'Egreso');
            $data['saldo_actual'] = $data['openCaja']['monto_inicial'] + $data['total_ingresos'] - $data['total_egresos'];
        }

        // Mensajes de sesión
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('caja/index', $data);
    }

    // Procesa la apertura de caja
    public function open() {
        $this->requireAuth(['Super Admin', 'Cajero']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $monto_inicial = filter_input(INPUT_POST, 'monto_inicial', FILTER_VALIDATE_FLOAT);

            if ($monto_inicial === false || $monto_inicial < 0) {
                $_SESSION['error_message'] = 'Monto inicial inválido.';
                $this->redirect('caja');
            }

            if ($this->cajaModel->getOpenCaja()) {
                $_SESSION['error_message'] = 'Ya existe una caja abierta.';
                $this->redirect('caja');
            }

            if ($this->cajaModel->openCaja($this->user_id, $monto_inicial)) {
                $_SESSION['success_message'] = 'Caja abierta exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al abrir la caja.';
            }
            $this->redirect('caja');
        } else {
            $this->redirect('caja');
        }
    }

    // Procesa el cierre de caja
    public function close() {
        $this->requireAuth(['Super Admin', 'Cajero']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_caja = (int)$_POST['id_caja'] ?? 0;
            $monto_final = filter_input(INPUT_POST, 'monto_final', FILTER_VALIDATE_FLOAT);

            if ($id_caja <= 0 || $monto_final === false || $monto_final < 0) {
                $_SESSION['error_message'] = 'Datos de cierre de caja inválidos.';
                $this->redirect('caja');
            }

            if (!$this->cajaModel->getOpenCaja() || $this->cajaModel->getOpenCaja()['id_caja'] != $id_caja) {
                 $_SESSION['error_message'] = 'La caja que intenta cerrar no está abierta.';
                 $this->redirect('caja');
            }

            if ($this->cajaModel->closeCaja($id_caja, $this->user_id, $monto_final)) {
                $_SESSION['success_message'] = 'Caja cerrada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al cerrar la caja.';
            }
            $this->redirect('caja');
        } else {
            $this->redirect('caja');
        }
    }

    // Muestra la interfaz para registrar una venta (factura/boleta)
    public function registerSale($pedido_id = null) {
        $this->requireAuth(['Super Admin', 'Cajero']);

        $data['title'] = 'Registrar Venta';
        $data['metodos_pago'] = $this->cajaModel->getMetodosPago();
        $data['pedido'] = null;
        $data['total_pedido'] = 0;

        // Si viene de un pedido, cargar sus datos
        if ($pedido_id) {
            $pedido_info = $this->pedidoModel->getPedidoWithDetails($pedido_id);
            if ($pedido_info) {
                $data['pedido'] = $pedido_info;
                $data['total_pedido'] = $pedido_info['total_pedido'];
                $data['title'] = 'Facturar Pedido #' . htmlspecialchars($pedido_id);
            } else {
                $_SESSION['error_message'] = 'Pedido no encontrado para facturar.';
                $this->redirect('caja/listSales'); // O a la vista principal de caja
            }
        }

        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('caja/register_sale', $data);
    }

    // Procesa el registro de una venta
    public function storeSale() {
        $this->requireAuth(['Super Admin', 'Cajero']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_caja_abierta = $this->cajaModel->getOpenCaja();
            if (!$id_caja_abierta) {
                $_SESSION['error_message'] = 'No hay una caja abierta para registrar la venta.';
                $this->redirect('caja');
            }
            $id_caja = $id_caja_abierta['id_caja'];

            $id_pedido = !empty($_POST['id_pedido']) ? (int)$_POST['id_pedido'] : null;
            $total_venta = filter_input(INPUT_POST, 'total_venta', FILTER_VALIDATE_FLOAT);
            $monto_recibido = filter_input(INPUT_POST, 'monto_recibido', FILTER_VALIDATE_FLOAT);
            $id_metodo_pago = (int)$_POST['id_metodo_pago'] ?? 0;
            $tipo_documento = $_POST['tipo_documento'] ?? '';
            $numero_documento = trim($_POST['numero_documento'] ?? '');
            $cliente_razon_social = trim($_POST['cliente_razon_social'] ?? '');
            $cliente_tipo_doc = $_POST['cliente_tipo_doc'] ?? '';
            $cliente_num_doc = trim($_POST['cliente_num_doc'] ?? '');
            $cliente_direccion = trim($_POST['cliente_direccion'] ?? '');
            $cliente_telefono = trim($_POST['cliente_telefono'] ?? '');
            $cliente_correo = trim($_POST['cliente_correo'] ?? '');

            if ($total_venta === false || $total_venta < 0 || $monto_recibido === false || $monto_recibido < 0 || $id_metodo_pago <= 0 || empty($tipo_documento)) {
                $_SESSION['error_message'] = 'Datos de venta incompletos o inválidos.';
                $this->redirect('caja/registerSale' . ($id_pedido ? '/' . $id_pedido : ''));
            }

            if ($monto_recibido < $total_venta) {
                $_SESSION['error_message'] = 'El monto recibido es menor al total de la venta.';
                $this->redirect('caja/registerSale' . ($id_pedido ? '/' . $id_pedido : ''));
            }
            $cambio = $monto_recibido - $total_venta;

            $id_cliente = null;
            // Si es factura o boleta que requiere cliente
            if (($tipo_documento === 'Factura' || $tipo_documento === 'Boleta') && !empty($cliente_num_doc)) {
                $cliente = $this->cajaModel->getClienteByDocumento($cliente_num_doc);
                if ($cliente) {
                    $id_cliente = $cliente['id_cliente'];
                } else {
                    // Crear nuevo cliente
                    $id_cliente = $this->cajaModel->createCliente($cliente_razon_social, $cliente_tipo_doc, $cliente_num_doc, $cliente_direccion, $cliente_telefono, $cliente_correo);
                    if (!$id_cliente) {
                        $_SESSION['error_message'] = 'Error al registrar el cliente.';
                        $this->redirect('caja/registerSale' . ($id_pedido ? '/' . $id_pedido : ''));
                    }
                }
            }

            $venta_id = $this->cajaModel->registerVenta($id_pedido, $id_caja, $this->user_id, $total_venta, $monto_recibido, $cambio, $id_metodo_pago, $tipo_documento, !empty($numero_documento) ? $numero_documento : null, $id_cliente);

            if ($venta_id) {
                $_SESSION['success_message'] = 'Venta #' . $venta_id . ' registrada exitosamente.';
                $this->redirect('caja/viewSale/' . $venta_id); // Redirigir a ver la venta/imprimir
            } else {
                $_SESSION['error_message'] = 'Error al registrar la venta.';
                $this->redirect('caja/registerSale' . ($id_pedido ? '/' . $id_pedido : ''));
            }

        } else {
            $this->redirect('caja');
        }
    }

    // Muestra los detalles de una venta y permite imprimir
    public function viewSale($id_venta) {
        $this->requireAuth(['Super Admin', 'Cajero']);
        $venta = $this->cajaModel->getVentaById($id_venta);

        if (!$venta) {
            $_SESSION['error_message'] = 'Venta no encontrada.';
            $this->redirect('caja/listSales');
        }

        $data['title'] = 'Detalles de Venta #' . htmlspecialchars($venta['id_venta']);
        $data['venta'] = $venta;
        $data['company_config'] = $this->configModel->getCompanyConfig(); // <-- Nuevo: Cargar config de empresa

        // Si la venta viene de un pedido, cargar sus detalles para imprimir
        $data['pedido_details'] = null;
        if ($venta['id_pedido']) {
            $data['pedido_details'] = $this->pedidoModel->getPedidoWithDetails($venta['id_pedido']);
        }

        $this->view('caja/view_sale', $data);
    }

    // Listado de ventas
    public function listSales() {
        $this->requireAuth(['Super Admin', 'Cajero']);
        $data['title'] = 'Listado de Ventas';
        // Aquí necesitarías un método en CajaModel para obtener todas las ventas o filtrar por fecha
        // Por simplicidad, por ahora no lo incluimos, pero se haría así:
        // $data['ventas'] = $this->cajaModel->getAllSales(); 
        $this->view('caja/list_sales', $data);
    }

    // Muestra formulario para registrar ingreso/egreso
    public function registerMovement() {
        $this->requireAuth(['Super Admin', 'Cajero']);
        $data['title'] = 'Registrar Movimiento de Caja';
        $data['tipos_movimiento'] = $this->cajaModel->getTiposMovimiento();
        $data['openCaja'] = $this->cajaModel->getOpenCaja();

        if (!$data['openCaja']) {
            $_SESSION['error_message'] = 'Necesitas abrir una caja para registrar movimientos.';
            $this->redirect('caja');
        }

        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('caja/register_movement', $data);
    }

    // Procesa el registro de ingreso/egreso
    public function storeMovement() {
        $this->requireAuth(['Super Admin', 'Cajero']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_caja_abierta = $this->cajaModel->getOpenCaja();
            if (!$id_caja_abierta) {
                $_SESSION['error_message'] = 'No hay una caja abierta para registrar movimientos.';
                $this->redirect('caja');
            }
            $id_caja = $id_caja_abierta['id_caja'];

            $id_tipo_movimiento = (int)$_POST['id_tipo_movimiento'] ?? 0;
            $monto = filter_input(INPUT_POST, 'monto', FILTER_VALIDATE_FLOAT);
            $descripcion = trim($_POST['descripcion'] ?? '');
            $referencia_externa = trim($_POST['referencia_externa'] ?? '');

            if ($id_tipo_movimiento <= 0 || $monto === false || $monto <= 0 || empty($descripcion)) {
                $_SESSION['error_message'] = 'Datos de movimiento incompletos o inválidos.';
                $this->redirect('caja/registerMovement');
            }

            if ($this->cajaModel->registerMovimientoCaja($id_caja, $id_tipo_movimiento, $monto, $descripcion, $this->user_id, $referencia_externa)) {
                $_SESSION['success_message'] = 'Movimiento registrado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al registrar el movimiento.';
            }
            $this->redirect('caja'); // Volver a la interfaz principal de caja
        } else {
            $this->redirect('caja');
        }
    }
}