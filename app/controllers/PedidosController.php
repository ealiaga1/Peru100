<?php
// app/controllers/PedidoController.php

class PedidosController extends Controller {
    private $mesaModel;
    private $productoModel;
    private $pedidoModel;

    public function __construct() {
        parent::__construct();
        require_once ROOT_PATH . 'app/models/MesaModel.php';
        require_once ROOT_PATH . 'app/models/ProductoModel.php';
        require_once ROOT_PATH . 'app/models/PedidoModel.php';
        $this->mesaModel = new MesaModel();
        $this->productoModel = new ProductoModel();
        $this->pedidoModel = new PedidoModel();
    }

    // Muestra el dashboard de mesas para que el mesero elija una mesa
    public function index() {
        $this->requireAuth(['Super Admin', 'Mesero']); // Meseros y Admins pueden ver esto

        $data['title'] = 'Mesas Disponibles';
        $data['mesas'] = $this->mesaModel->getAllMesas(); // Obtener todas las mesas con su salón

        // Agrupar mesas por salón para una mejor visualización
        $mesas_por_salon = [];
        foreach ($data['mesas'] as $mesa) {
            $mesas_por_salon[$mesa['nombre_salon']][] = $mesa;
        }
        $data['mesas_por_salon'] = $mesas_por_salon;

        // Limpiar mensajes de sesión
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('pedidos/index', $data);
    }

    // Interfaz para tomar un pedido de una mesa específica
    public function takeOrder($id_mesa) {
        $this->requireAuth(['Super Admin', 'Mesero']);

        $mesa = $this->mesaModel->getMesaById($id_mesa);
        if (!$mesa) {
            $_SESSION['error_message'] = 'Mesa no encontrada.';
            $this->redirect('pedidos');
        }

        // Si la mesa está libre, la marcamos como "Ocupada" (o 'En Proceso de Pedido')
        // Si ya está ocupada o en pedido, cargamos el pedido existente
        $pedido_actual = null;
        if ($mesa['estado'] === 'Libre') {
            // Se puede crear un pedido provisional aquí, o al enviar el primer producto.
            // Por simplicidad, el pedido se crea al llamar a storeOrder.
            // Aquí solo marcamos la mesa como "Ocupada" al entrar a tomar el pedido.
            // Puedes ajustar esta lógica si prefieres que la mesa se marque solo al finalizar el pedido.
            // Para fines de este ejemplo, la marcamos aquí:
            $this->mesaModel->updateMesaEstado($id_mesa, 'Ocupada'); // O 'En Pedido'
            $data['title'] = 'Nuevo Pedido para Mesa ' . htmlspecialchars($mesa['numero_mesa']);
        } else if ($mesa['estado'] === 'Ocupada' || $mesa['estado'] === 'En preparación' || $mesa['estado'] === 'Servido') {
            // Buscar si ya hay un pedido "activo" para esta mesa (no pagado/cerrado)
            // Esto requeriría un método en PedidoModel como getActivePedidoByMesa($id_mesa)
            // Por ahora, asumimos que si está "Ocupada" o "En preparación", podemos seguir agregando al pedido
            $data['title'] = 'Pedido para Mesa ' . htmlspecialchars($mesa['numero_mesa']);
            // Podrías cargar el pedido existente aquí si lo tuvieras:
            // $pedido_actual = $this->pedidoModel->getActivePedidoByMesa($id_mesa);
        } else {
            $_SESSION['error_message'] = 'La mesa no está disponible para tomar pedidos (estado: ' . htmlspecialchars($mesa['estado']) . ').';
            $this->redirect('pedidos');
        }
        
        $data['mesa'] = $mesa;
        $data['categorias'] = $this->productoModel->getAllCategorias();
        $data['productos'] = $this->productoModel->getAllProductos(); // Todos los productos para filtrar en JS

        // Mensajes de sesión
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('pedidos/take_order', $data);
    }

    // API o método para obtener productos por categoría (usado por JS)
    public function getProductosByCategoryJson($id_categoria) {
        header('Content-Type: application/json');
        $this->requireAuth(['Super Admin', 'Mesero']); // Asegurar que solo usuarios autorizados accedan

        $productos = $this->productoModel->getProductosByCategoria($id_categoria);
        echo json_encode($productos);
        exit; // Terminar la ejecución para no cargar vista
    }

    // Procesa el envío del pedido
    public function storeOrder() {
        $this->requireAuth(['Super Admin', 'Mesero']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_mesa = (int)$_POST['id_mesa'] ?? 0;
            $items = json_decode($_POST['items'], true); // Los productos seleccionados desde JS
            $notas_pedido = trim($_POST['notas_pedido'] ?? '');

            if ($id_mesa <= 0 || empty($items)) {
                $_SESSION['error_message'] = 'No se seleccionaron productos o la mesa es inválida.';
                $this->redirect('pedidos/takeOrder/' . $id_mesa);
            }

            // Aquí puedes buscar un pedido "abierto" para esta mesa
            // Por ahora, siempre creamos uno nuevo por simplicidad del ejemplo.
            // En un sistema real, buscarías: getActivePedidoByMesa()
            
            $pedido_id = $this->pedidoModel->createPedido($id_mesa, $this->user_id, 'Mesa', $notas_pedido);

            if ($pedido_id) {
                // Preparar los datos de los ítems para el modelo
                $productos_data = [];
                foreach ($items as $item) {
                    $producto_info = $this->productoModel->getProductoById($item['id_producto']);
                    if ($producto_info) {
                        $productos_data[] = [
                            'id_producto' => $item['id_producto'],
                            'cantidad' => $item['cantidad'],
                            'precio_unitario' => $producto_info['precio_venta'], // Usar el precio de venta del producto
                            'notas_item' => $item['notas_item'] ?? ''
                        ];
                    }
                }
                
                if ($this->pedidoModel->addDetallesPedido($pedido_id, $productos_data)) {
                    // Marcar la mesa como 'Ocupada' (si no se hizo antes en takeOrder)
                    $this->mesaModel->updateMesaEstado($id_mesa, 'Ocupada');

                    // Registrar comandas (Cocina/Bar)
                    // Podrías tener una lógica más sofisticada para decidir qué comandas imprimir
                    if ($this->pedidoModel->registerComandaPrinted($pedido_id, 'Cocina', $this->user_id)) {
                        // Comanda de cocina registrada
                    }
                    if ($this->pedidoModel->registerComandaPrinted($pedido_id, 'Bar', $this->user_id)) {
                        // Comanda de bar registrada
                    }

                    $_SESSION['success_message'] = 'Pedido #' . $pedido_id . ' enviado exitosamente y comandas impresas.';
                    $this->redirect('pedidos/viewOrder/' . $pedido_id); // Redirige a ver el pedido
                } else {
                    $_SESSION['error_message'] = 'Error al añadir los detalles del pedido.';
                    $this->redirect('pedidos/takeOrder/' . $id_mesa);
                }
            } else {
                $_SESSION['error_message'] = 'Error al crear el pedido inicial.';
                $this->redirect('pedidos/takeOrder/' . $id_mesa);
            }
        } else {
            $this->redirect('pedidos'); // Redirige al listado de mesas si no es POST
        }
    }

    // Muestra los detalles de un pedido específico
    public function viewOrder($id_pedido) {
        $this->requireAuth(['Super Admin', 'Cajero', 'Mesero']); // Quienes pueden ver detalles

        $pedido = $this->pedidoModel->getPedidoWithDetails($id_pedido);
        if (!$pedido) {
            $_SESSION['error_message'] = 'Pedido no encontrado.';
            $this->redirect('pedidos'); // O a un listado de pedidos
        }

        $data['title'] = 'Detalles del Pedido #' . htmlspecialchars($pedido['id_pedido']);
        $data['pedido'] = $pedido;

        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('pedidos/view_order', $data);
    }

    // Listado general de todos los pedidos
    public function list() {
        $this->requireAuth(['Super Admin', 'Cajero']); // Solo Admins y Cajeros pueden ver todos los pedidos

        $data['title'] = 'Listado de Pedidos';
        $data['pedidos'] = $this->pedidoModel->getAllPedidos();

        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        $this->view('pedidos/list', $data);
    }

    // --- Acciones de Comandas (para Bar y Cocina) ---

    // Muestra las comandas pendientes para cocina
    public function cocina() {
        $this->requireAuth(['Super Admin', 'Cocina']);
        $data['title'] = 'Comandas de Cocina Pendientes';
        $data['comandas'] = $this->pedidoModel->getPendingComandas('Cocina');
        $this->view('pedidos/comandas_cocina', $data);
    }

    // Muestra las comandas pendientes para bar
    public function bar() {
        $this->requireAuth(['Super Admin', 'Bar']);
        $data['title'] = 'Comandas de Bar Pendientes';
        $data['comandas'] = $this->pedidoModel->getPendingComandas('Bar');
        $this->view('pedidos/comandas_bar', $data);
    }

    // Marcar un ítem de detalle de pedido como listo (desde cocina/bar)
    public function markItemReady($id_detalle_pedido, $return_to = 'pedidos') { // Añadimos $return_to
        $this->requireAuth(['Super Admin', 'Cocina', 'Bar']);
        if ($this->pedidoModel->updateDetallePedidoEstado($id_detalle_pedido, 'Listo')) {
            $_SESSION['success_message'] = 'Ítem marcado como listo.';
        } else {
            $_SESSION['error_message'] = 'Error al marcar ítem como listo.';
        }
        
        // Redireccionar de vuelta a la interfaz de comandas específica
        if ($return_to === 'cocina') {
            $this->redirect('pedidos/cocina');
        } elseif ($return_to === 'bar') {
            $this->redirect('pedidos/bar');
        } else {
            $this->redirect('pedidos'); // Fallback al dashboard de mesas
        }
    }

    // Actualizar estado de pedido completo (ej. Servido, Pagado)
    public function updateOrderStatus($id_pedido, $new_status) {
        $this->requireAuth(['Super Admin', 'Mesero', 'Cajero']); // Roles que pueden cambiar estado

        if ($this->pedidoModel->updatePedidoEstado($id_pedido, $new_status)) {
            $_SESSION['success_message'] = 'Estado del pedido actualizado a "' . htmlspecialchars($new_status) . '".';
        } else {
            $_SESSION['error_message'] = 'Error al actualizar el estado del pedido.';
        }
        $this->redirect('pedidos/viewOrder/' . $id_pedido);
    }
}