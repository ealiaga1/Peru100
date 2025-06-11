<?php
// app/controllers/InventarioController.php

class InventarioController extends Controller {
    private $productoModel;

    public function __construct() {
        parent::__construct();
        require_once ROOT_PATH . 'app/models/ProductoModel.php';
        $this->productoModel = new ProductoModel();
    }

    // --- Categorías de Productos ---

    public function categorias() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Gestión de Categorías';
        $data['categorias'] = $this->productoModel->getAllCategorias(false); // Obtener todas, incluyendo inactivas
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);
        $this->view('inventario/categorias/index', $data);
    }

    public function createCategoria() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Crear Nueva Categoría';
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('inventario/categorias/create', $data);
    }

    public function storeCategoria() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');

            if (empty($nombre)) {
                $_SESSION['error_message'] = 'El nombre de la categoría es obligatorio.';
                $this->redirect('inventario/createCategoria');
            }
            if ($this->productoModel->categoriaExists($nombre)) {
                $_SESSION['error_message'] = 'Ya existe una categoría con ese nombre.';
                $this->redirect('inventario/createCategoria');
            }

            if ($this->productoModel->createCategoria($nombre, $descripcion)) {
                $_SESSION['success_message'] = 'Categoría creada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al crear la categoría.';
            }
            $this->redirect('inventario/categorias');
        } else {
            $this->redirect('inventario/categorias');
        }
    }

    public function editCategoria($id) {
        $this->requireAuth(['Super Admin']);
        $categoria = $this->productoModel->getCategoriaById($id);
        if (!$categoria) {
            $_SESSION['error_message'] = 'Categoría no encontrada.';
            $this->redirect('inventario/categorias');
        }
        $data['title'] = 'Editar Categoría: ' . htmlspecialchars($categoria['nombre_categoria']);
        $data['categoria'] = $categoria;
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('inventario/categorias/edit', $data);
    }

    public function updateCategoria() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'] ?? 0;
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');

            if ($id <= 0 || empty($nombre)) {
                $_SESSION['error_message'] = 'Datos inválidos para actualizar categoría.';
                $this->redirect('inventario/categorias');
            }
            if ($this->productoModel->categoriaExists($nombre, $id)) {
                $_SESSION['error_message'] = 'Ya existe otra categoría con ese nombre.';
                $this->redirect('inventario/editCategoria/' . $id);
            }

            if ($this->productoModel->updateCategoria($id, $nombre, $descripcion)) {
                $_SESSION['success_message'] = 'Categoría actualizada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar la categoría.';
            }
            $this->redirect('inventario/categorias');
        } else {
            $this->redirect('inventario/categorias');
        }
    }

    public function deleteCategoria($id) {
        $this->requireAuth(['Super Admin']);
        if ($this->productoModel->deleteCategoria($id)) {
            $_SESSION['success_message'] = 'Categoría desactivada exitosamente.';
        } else {
            $_SESSION['error_message'] = 'Error al desactivar la categoría.';
        }
        $this->redirect('inventario/categorias');
    }

    // --- Unidades de Medida ---

    public function unidadesMedida() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Gestión de Unidades de Medida';
        $data['unidades'] = $this->productoModel->getAllUnidadesMedida(false);
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);
        $this->view('inventario/unidades_medida/index', $data);
    }

    public function createUnidadMedida() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Crear Nueva Unidad de Medida';
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('inventario/unidades_medida/create', $data);
    }

    public function storeUnidadMedida() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $abreviatura = trim($_POST['abreviatura'] ?? '');

            if (empty($nombre) || empty($abreviatura)) {
                $_SESSION['error_message'] = 'Nombre y abreviatura son obligatorios.';
                $this->redirect('inventario/createUnidadMedida');
            }
            if ($this->productoModel->unidadMedidaExists($nombre)) {
                $_SESSION['error_message'] = 'Ya existe una unidad de medida con ese nombre.';
                $this->redirect('inventario/createUnidadMedida');
            }

            if ($this->productoModel->createUnidadMedida($nombre, $abreviatura)) {
                $_SESSION['success_message'] = 'Unidad de medida creada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al crear la unidad de medida.';
            }
            $this->redirect('inventario/unidadesMedida');
        } else {
            $this->redirect('inventario/unidadesMedida');
        }
    }

    public function editUnidadMedida($id) {
        $this->requireAuth(['Super Admin']);
        $unidad = $this->productoModel->getUnidadMedidaById($id);
        if (!$unidad) {
            $_SESSION['error_message'] = 'Unidad de medida no encontrada.';
            $this->redirect('inventario/unidadesMedida');
        }
        $data['title'] = 'Editar Unidad de Medida: ' . htmlspecialchars($unidad['nombre_unidad']);
        $data['unidad'] = $unidad;
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('inventario/unidades_medida/edit', $data);
    }

    public function updateUnidadMedida() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'] ?? 0;
            $nombre = trim($_POST['nombre'] ?? '');
            $abreviatura = trim($_POST['abreviatura'] ?? '');

            if ($id <= 0 || empty($nombre) || empty($abreviatura)) {
                $_SESSION['error_message'] = 'Datos inválidos para actualizar unidad de medida.';
                $this->redirect('inventario/unidadesMedida');
            }
            if ($this->productoModel->unidadMedidaExists($nombre, $id)) {
                $_SESSION['error_message'] = 'Ya existe otra unidad de medida con ese nombre.';
                $this->redirect('inventario/editUnidadMedida/' . $id);
            }

            if ($this->productoModel->updateUnidadMedida($id, $nombre, $abreviatura)) {
                $_SESSION['success_message'] = 'Unidad de medida actualizada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar la unidad de medida.';
            }
            $this->redirect('inventario/unidadesMedida');
        } else {
            $this->redirect('inventario/unidadesMedida');
        }
    }

    public function deleteUnidadMedida($id) {
        $this->requireAuth(['Super Admin']);
        if ($this->productoModel->deleteUnidadMedida($id)) {
            $_SESSION['success_message'] = 'Unidad de medida desactivada exitosamente.';
        } else {
            $_SESSION['error_message'] = 'Error al desactivar la unidad de medida.';
        }
        $this->redirect('inventario/unidadesMedida');
    }

    // --- Productos ---

    public function productos() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Gestión de Productos del Menú';
        $data['productos'] = $this->productoModel->getAllProductos(false); // Obtener todos, incluyendo inactivos
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);
        $this->view('inventario/productos/index', $data);
    }

    public function createProducto() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Crear Nuevo Producto';
        $data['categorias'] = $this->productoModel->getAllCategorias(true); // Solo activas
        $data['unidades_medida'] = $this->productoModel->getAllUnidadesMedida(true); // Solo activas
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('inventario/productos/create', $data);
    }

    public function storeProducto() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_producto = trim($_POST['nombre_producto'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio_venta = filter_input(INPUT_POST, 'precio_venta', FILTER_VALIDATE_FLOAT);
            $id_categoria = (int)$_POST['id_categoria'] ?? 0;
            $tipo_producto = trim($_POST['tipo_producto'] ?? '');
            $stock_actual = filter_input(INPUT_POST, 'stock_actual', FILTER_VALIDATE_FLOAT);
            $id_unidad_medida = (int)$_POST['id_unidad_medida'] ?? 0;

            if (empty($nombre_producto) || $precio_venta === false || $precio_venta < 0 || $id_categoria <= 0 || empty($tipo_producto) || $stock_actual === false || $stock_actual < 0 || $id_unidad_medida <= 0) {
                $_SESSION['error_message'] = 'Todos los campos obligatorios deben ser llenados correctamente.';
                $this->redirect('inventario/createProducto');
            }
            if ($this->productoModel->productoExists($nombre_producto)) {
                $_SESSION['error_message'] = 'Ya existe un producto con ese nombre.';
                $this->redirect('inventario/createProducto');
            }

            if ($this->productoModel->createProducto($nombre_producto, $descripcion, $precio_venta, $id_categoria, $tipo_producto, $stock_actual, $id_unidad_medida)) {
                $_SESSION['success_message'] = 'Producto creado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al crear el producto.';
            }
            $this->redirect('inventario/productos');
        } else {
            $this->redirect('inventario/productos');
        }
    }

    public function editProducto($id) {
        $this->requireAuth(['Super Admin']);
        $producto = $this->productoModel->getProductoById($id);
        if (!$producto) {
            $_SESSION['error_message'] = 'Producto no encontrado.';
            $this->redirect('inventario/productos');
        }
        $data['title'] = 'Editar Producto: ' . htmlspecialchars($producto['nombre_producto']);
        $data['producto'] = $producto;
        $data['categorias'] = $this->productoModel->getAllCategorias(true);
        $data['unidades_medida'] = $this->productoModel->getAllUnidadesMedida(true);
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('inventario/productos/edit', $data);
    }

    public function updateProducto() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'] ?? 0;
            $nombre_producto = trim($_POST['nombre_producto'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio_venta = filter_input(INPUT_POST, 'precio_venta', FILTER_VALIDATE_FLOAT);
            $id_categoria = (int)$_POST['id_categoria'] ?? 0;
            $tipo_producto = trim($_POST['tipo_producto'] ?? '');
            $stock_actual = filter_input(INPUT_POST, 'stock_actual', FILTER_VALIDATE_FLOAT);
            $id_unidad_medida = (int)$_POST['id_unidad_medida'] ?? 0;
            $activo = isset($_POST['activo']) ? 1 : 0;

            if ($id <= 0 || empty($nombre_producto) || $precio_venta === false || $precio_venta < 0 || $id_categoria <= 0 || empty($tipo_producto) || $stock_actual === false || $stock_actual < 0 || $id_unidad_medida <= 0) {
                $_SESSION['error_message'] = 'Datos inválidos para actualizar producto.';
                $this->redirect('inventario/productos');
            }
            if ($this->productoModel->productoExists($nombre_producto, $id)) {
                $_SESSION['error_message'] = 'Ya existe otro producto con ese nombre.';
                $this->redirect('inventario/editProducto/' . $id);
            }

            if ($this->productoModel->updateProducto($id, $nombre_producto, $descripcion, $precio_venta, $id_categoria, $tipo_producto, $stock_actual, $id_unidad_medida, $activo)) {
                $_SESSION['success_message'] = 'Producto actualizado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar el producto.';
            }
            $this->redirect('inventario/productos');
        } else {
            $this->redirect('inventario/productos');
        }
    }

    public function deleteProducto($id) {
        $this->requireAuth(['Super Admin']);
        if ($this->productoModel->deleteProducto($id)) {
            $_SESSION['success_message'] = 'Producto desactivado exitosamente.';
        } else {
            $_SESSION['error_message'] = 'Error al desactivar el producto.';
        }
        $this->redirect('inventario/productos');
    }

    // --- Ingredientes ---

    public function ingredientes() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Gestión de Ingredientes';
        $data['ingredientes'] = $this->productoModel->getAllIngredientes(false);
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);
        $this->view('inventario/ingredientes/index', $data);
    }

    public function createIngrediente() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Crear Nuevo Ingrediente';
        $data['unidades_medida'] = $this->productoModel->getAllUnidadesMedida(true);
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('inventario/ingredientes/create', $data);
    }

    public function storeIngrediente() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_ingrediente = trim($_POST['nombre_ingrediente'] ?? '');
            $costo_unitario = filter_input(INPUT_POST, 'costo_unitario', FILTER_VALIDATE_FLOAT);
            $id_unidad_medida = (int)$_POST['id_unidad_medida'] ?? 0;
            $stock_actual = filter_input(INPUT_POST, 'stock_actual', FILTER_VALIDATE_FLOAT);
            $stock_minimo = filter_input(INPUT_POST, 'stock_minimo', FILTER_VALIDATE_FLOAT);

            if (empty($nombre_ingrediente) || $costo_unitario === false || $costo_unitario < 0 || $id_unidad_medida <= 0 || $stock_actual === false || $stock_actual < 0 || $stock_minimo === false || $stock_minimo < 0) {
                $_SESSION['error_message'] = 'Todos los campos obligatorios deben ser llenados correctamente.';
                $this->redirect('inventario/createIngrediente');
            }
            if ($this->productoModel->ingredienteExists($nombre_ingrediente)) {
                $_SESSION['error_message'] = 'Ya existe un ingrediente con ese nombre.';
                $this->redirect('inventario/createIngrediente');
            }

            if ($this->productoModel->createIngrediente($nombre_ingrediente, $costo_unitario, $id_unidad_medida, $stock_actual, $stock_minimo)) {
                $_SESSION['success_message'] = 'Ingrediente creado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al crear el ingrediente.';
            }
            $this->redirect('inventario/ingredientes');
        } else {
            $this->redirect('inventario/ingredientes');
        }
    }

    public function editIngrediente($id) {
        $this->requireAuth(['Super Admin']);
        $ingrediente = $this->productoModel->getIngredienteById($id);
        if (!$ingrediente) {
            $_SESSION['error_message'] = 'Ingrediente no encontrado.';
            $this->redirect('inventario/ingredientes');
        }
        $data['title'] = 'Editar Ingrediente: ' . htmlspecialchars($ingrediente['nombre_ingrediente']);
        $data['ingrediente'] = $ingrediente;
        $data['unidades_medida'] = $this->productoModel->getAllUnidadesMedida(true);
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        $this->view('inventario/ingredientes/edit', $data);
    }

    public function updateIngrediente() {
        $this->requireAuth(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'] ?? 0;
            $nombre_ingrediente = trim($_POST['nombre_ingrediente'] ?? '');
            $costo_unitario = filter_input(INPUT_POST, 'costo_unitario', FILTER_VALIDATE_FLOAT);
            $id_unidad_medida = (int)$_POST['id_unidad_medida'] ?? 0;
            $stock_actual = filter_input(INPUT_POST, 'stock_actual', FILTER_VALIDATE_FLOAT);
            $stock_minimo = filter_input(INPUT_POST, 'stock_minimo', FILTER_VALIDATE_FLOAT);
            $activo = isset($_POST['activo']) ? 1 : 0;

            if ($id <= 0 || empty($nombre_ingrediente) || $costo_unitario === false || $costo_unitario < 0 || $id_unidad_medida <= 0 || $stock_actual === false || $stock_actual < 0 || $stock_minimo === false || $stock_minimo < 0) {
                $_SESSION['error_message'] = 'Datos inválidos para actualizar ingrediente.';
                $this->redirect('inventario/ingredientes');
            }
            if ($this->productoModel->ingredienteExists($nombre_ingrediente, $id)) {
                $_SESSION['error_message'] = 'Ya existe otro ingrediente con ese nombre.';
                $this->redirect('inventario/editIngrediente/' . $id);
            }

            if ($this->productoModel->updateIngrediente($id, $nombre_ingrediente, $costo_unitario, $id_unidad_medida, $stock_actual, $stock_minimo, $activo)) {
                $_SESSION['success_message'] = 'Ingrediente actualizado exitosamente.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar el ingrediente.';
            }
            $this->redirect('inventario/ingredientes');
        } else {
            $this->redirect('inventario/ingredientes');
        }
    }

    public function deleteIngrediente($id) {
        $this->requireAuth(['Super Admin']);
        if ($this->productoModel->deleteIngrediente($id)) {
            $_SESSION['success_message'] = 'Ingrediente desactivado exitosamente.';
        } else {
            $_SESSION['error_message'] = 'Error al desactivar el ingrediente.';
        }
        $this->redirect('inventario/ingredientes');
    }

    // --- Index principal del módulo de Inventario ---
    public function index() {
        $this->requireAuth(['Super Admin']);
        $data['title'] = 'Panel de Control de Inventario';
        $data['error_message'] = $_SESSION['error_message'] ?? null;
        $data['success_message'] = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);
        $this->view('inventario/index', $data);
    }
}