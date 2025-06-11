<?php
// app/models/ProductoModel.php

class ProductoModel extends Model {

    // --- Métodos para Categorías de Productos ---

    // Obtener todas las categorías de productos (activas o inactivas)
    public function getAllCategorias($onlyActive = false) {
        $sql = "SELECT * FROM categorias_productos";
        if ($onlyActive) {
            $sql .= " WHERE activo = 1"; // Asume que tienes una columna 'activo'
        }
        $sql .= " ORDER BY nombre_categoria";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una categoría por su ID
    public function getCategoriaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorias_productos WHERE id_categoria = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva categoría
    public function createCategoria($nombre_categoria, $descripcion = null) {
        $stmt = $this->db->prepare("INSERT INTO categorias_productos (nombre_categoria, descripcion) VALUES (?, ?)");
        return $stmt->execute([$nombre_categoria, $descripcion]);
    }

    // Actualizar una categoría existente
    public function updateCategoria($id, $nombre_categoria, $descripcion = null) {
        $stmt = $this->db->prepare("UPDATE categorias_productos SET nombre_categoria = ?, descripcion = ? WHERE id_categoria = ?");
        return $stmt->execute([$nombre_categoria, $descripcion, $id]);
    }
    
    // Desactivar (eliminar lógicamente) una categoría
    public function deleteCategoria($id) {
        // En lugar de DELETE, actualizamos 'activo' a 0 (si la columna existe)
        // Si no tienes 'activo', considera añadirla o usar DELETE CASCADE con precaución.
        $stmt = $this->db->prepare("UPDATE categorias_productos SET activo = 0 WHERE id_categoria = ?");
        return $stmt->execute([$id]);
    }

    // Verificar si el nombre de una categoría ya existe
    public function categoriaExists($nombre_categoria, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM categorias_productos WHERE nombre_categoria = ?";
        $params = [$nombre_categoria];
        if ($excludeId) {
            $sql .= " AND id_categoria != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }


    // --- Métodos para Productos ---

    // Obtener todos los productos activos, con su categoría y unidad de medida de venta
    public function getAllProductos($onlyActive = false) {
        $sql = "
            SELECT p.*, cp.nombre_categoria, um.nombre_unidad as unidad_venta_nombre
            FROM productos p
            JOIN categorias_productos cp ON p.id_categoria = cp.id_categoria
            LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad
        ";
        if ($onlyActive) {
            $sql .= " WHERE p.activo = 1";
        }
        $sql .= " ORDER BY cp.nombre_categoria, p.nombre_producto";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos por ID de categoría
    public function getProductosByCategoria($id_categoria, $onlyActive = true) {
        $sql = "SELECT * FROM productos WHERE id_categoria = ?";
        $params = [$id_categoria];
        if ($onlyActive) {
            $sql .= " AND activo = 1";
        }
        $sql .= " ORDER BY nombre_producto";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por su ID
    public function getProductoById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, cp.nombre_categoria, um.nombre_unidad as unidad_venta_nombre
            FROM productos p
            JOIN categorias_productos cp ON p.id_categoria = cp.id_categoria
            LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad
            WHERE p.id_producto = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo producto
    public function createProducto($nombre_producto, $descripcion, $precio_venta, $id_categoria, $tipo_producto, $stock_actual, $id_unidad_medida) {
        $stmt = $this->db->prepare("INSERT INTO productos (nombre_producto, descripcion, precio_venta, id_categoria, tipo_producto, stock_actual, id_unidad_medida) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre_producto, $descripcion, $precio_venta, $id_categoria, $tipo_producto, $stock_actual, $id_unidad_medida]);
    }

    // Actualizar un producto existente
    public function updateProducto($id, $nombre_producto, $descripcion, $precio_venta, $id_categoria, $tipo_producto, $stock_actual, $id_unidad_medida, $activo) {
        $stmt = $this->db->prepare("UPDATE productos SET nombre_producto = ?, descripcion = ?, precio_venta = ?, id_categoria = ?, tipo_producto = ?, stock_actual = ?, id_unidad_medida = ?, activo = ? WHERE id_producto = ?");
        return $stmt->execute([$nombre_producto, $descripcion, $precio_venta, $id_categoria, $tipo_producto, $stock_actual, $id_unidad_medida, $activo, $id]);
    }

    // Desactivar (eliminar lógicamente) un producto
    public function deleteProducto($id) {
        $stmt = $this->db->prepare("UPDATE productos SET activo = 0 WHERE id_producto = ?");
        return $stmt->execute([$id]);
    }

    // Verificar si el nombre de un producto ya existe
    public function productoExists($nombre_producto, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM productos WHERE nombre_producto = ?";
        $params = [$nombre_producto];
        if ($excludeId) {
            $sql .= " AND id_producto != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // --- Métodos para Unidades de Medida ---

    // Obtener todas las unidades de medida (activas o inactivas)
    public function getAllUnidadesMedida($onlyActive = false) {
        $sql = "SELECT * FROM unidades_medida";
        if ($onlyActive) {
            $sql .= " WHERE activo = 1"; // Asume que tienes una columna 'activo'
        }
        $sql .= " ORDER BY nombre_unidad";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener una unidad de medida por su ID
    public function getUnidadMedidaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM unidades_medida WHERE id_unidad = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva unidad de medida
    public function createUnidadMedida($nombre_unidad, $abreviatura) {
        $stmt = $this->db->prepare("INSERT INTO unidades_medida (nombre_unidad, abreviatura) VALUES (?, ?)");
        return $stmt->execute([$nombre_unidad, $abreviatura]);
    }

    // Actualizar una unidad de medida existente
    public function updateUnidadMedida($id, $nombre_unidad, $abreviatura) {
        $stmt = $this->db->prepare("UPDATE unidades_medida SET nombre_unidad = ?, abreviatura = ? WHERE id_unidad = ?");
        return $stmt->execute([$nombre_unidad, $abreviatura, $id]);
    }

    // Desactivar (eliminar lógicamente) una unidad de medida
    public function deleteUnidadMedida($id) {
        // Asume columna 'activo' en unidades_medida
        $stmt = $this->db->prepare("UPDATE unidades_medida SET activo = 0 WHERE id_unidad = ?");
        return $stmt->execute([$id]);
    }

    // Verificar si el nombre de una unidad de medida ya existe
    public function unidadMedidaExists($nombre_unidad, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM unidades_medida WHERE nombre_unidad = ?";
        $params = [$nombre_unidad];
        if ($excludeId) {
            $sql .= " AND id_unidad != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // --- Métodos para Ingredientes ---

    // Obtener todos los ingredientes con su unidad de medida
    public function getAllIngredientes($onlyActive = false) {
        $sql = "
            SELECT i.*, um.nombre_unidad as unidad_medida_nombre
            FROM ingredientes i
            JOIN unidades_medida um ON i.id_unidad_medida = um.id_unidad
        ";
        if ($onlyActive) {
            $sql .= " WHERE i.activo = 1";
        }
        $sql .= " ORDER BY i.nombre_ingrediente";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un ingrediente por su ID
    public function getIngredienteById($id) {
        $stmt = $this->db->prepare("
            SELECT i.*, um.nombre_unidad as unidad_medida_nombre
            FROM ingredientes i
            JOIN unidades_medida um ON i.id_unidad_medida = um.id_unidad
            WHERE i.id_ingrediente = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo ingrediente
    public function createIngrediente($nombre_ingrediente, $costo_unitario, $id_unidad_medida, $stock_actual = 0, $stock_minimo = 0) {
        $stmt = $this->db->prepare("INSERT INTO ingredientes (nombre_ingrediente, costo_unitario, id_unidad_medida, stock_actual, stock_minimo) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre_ingrediente, $costo_unitario, $id_unidad_medida, $stock_actual, $stock_minimo]);
    }

    // Actualizar un ingrediente existente
    public function updateIngrediente($id, $nombre_ingrediente, $costo_unitario, $id_unidad_medida, $stock_actual, $stock_minimo, $activo) {
        $stmt = $this->db->prepare("UPDATE ingredientes SET nombre_ingrediente = ?, costo_unitario = ?, id_unidad_medida = ?, stock_actual = ?, stock_minimo = ?, activo = ? WHERE id_ingrediente = ?");
        return $stmt->execute([$nombre_ingrediente, $costo_unitario, $id_unidad_medida, $stock_actual, $stock_minimo, $activo, $id]);
    }

    // Desactivar (eliminar lógicamente) un ingrediente
    public function deleteIngrediente($id) {
        $stmt = $this->db->prepare("UPDATE ingredientes SET activo = 0 WHERE id_ingrediente = ?");
        return $stmt->execute([$id]);
    }

    // Verificar si el nombre de un ingrediente ya existe
    public function ingredienteExists($nombre_ingrediente, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM ingredientes WHERE nombre_ingrediente = ?";
        $params = [$nombre_ingrediente];
        if ($excludeId) {
            $sql .= " AND id_ingrediente != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}