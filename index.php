<?php

// -----------------------------------------------------------
// 1. Configuración Inicial y Carga de Archivos Necesarios
// -----------------------------------------------------------

// Mostrar errores en desarrollo. ¡IMPORTANTE: DESACTIVAR EN PRODUCCIÓN!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define la ruta raíz absoluta de tu proyecto.
// Esto es crucial para incluir otros archivos de forma consistente.
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

// Carga los archivos de configuración de tu aplicación.
// Al cargar config/app.php, la constante BASE_URL (y APP_NAME) ya estará disponible.
require_once ROOT_PATH . 'config/app.php';
require_once ROOT_PATH . 'config/database.php';

// Carga las clases fundamentales del "core" de tu framework MVC.
require_once ROOT_PATH . 'core/Database.php';
require_once ROOT_PATH . 'core/Model.php';
require_once ROOT_PATH . 'core/Controller.php';


// -----------------------------------------------------------
// 2. Obtener la Ruta de la Solicitud y Enrutamiento
// -----------------------------------------------------------

// La constante BASE_URL ya está disponible porque se incluyó config/app.php.
// Ahora la usamos para limpiar el REQUEST_URI.

// Construimos la URL completa para asegurarnos de que la coincidencia de str_replace sea exacta.
$full_request_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Eliminamos la BASE_URL de la URL completa para obtener solo la "ruta interna" de la aplicación.
$request_uri_no_base = str_replace(BASE_URL, '', $full_request_uri);

// parse_url con PHP_URL_PATH elimina cualquier query string (ej. ?param=valor).
$request_uri_no_base = parse_url($request_uri_no_base, PHP_URL_PATH);

// Divide la URL limpia en segmentos (ej. ['dashboard', 'index', 'parametro1']).
// trim() elimina las barras iniciales y finales.
$url_parts = explode('/', trim($request_uri_no_base, '/'));

// Determina el nombre del controlador y la acción.
// El primer segmento es el controlador (por defecto 'DashboardController').
$controller_name = !empty($url_parts[0]) ? ucfirst(array_shift($url_parts)) . 'Controller' : 'DashboardController';
// El segundo segmento es la acción (por defecto 'index').
$action_name = !empty($url_parts[0]) ? array_shift($url_parts) : 'index';
// Los segmentos restantes son los parámetros que se pasarán a la acción.
$params = $url_parts;


// -----------------------------------------------------------
// 3. Cargar y Ejecutar el Controlador y Acción Correspondientes
// -----------------------------------------------------------

$controller_file = ROOT_PATH . 'app/controllers/' . $controller_name . '.php';

// Verifica si el archivo del controlador existe en la ruta esperada.
if (file_exists($controller_file)) {
    require_once $controller_file; // Incluye el archivo del controlador.

    // Verifica si la clase del controlador realmente existe dentro del archivo incluido.
    if (class_exists($controller_name)) {
        $controller = new $controller_name(); // Instancia el controlador.

        // Verifica si la acción (método) solicitada existe en la instancia del controlador.
        if (method_exists($controller, $action_name)) {
            // Llama dinámicamente al método de la acción, pasándole los parámetros.
            // call_user_func_array es útil cuando tienes un array de argumentos.
            call_user_func_array([$controller, $action_name], $params);
        } else {
            // Si la acción no se encuentra, mostramos un error 404.
            http_response_code(404); // Establece el código de estado HTTP a 404.
            echo "<h1>Error 404</h1><p>Acción no encontrada en el controlador: <strong>" . htmlspecialchars($action_name) . "</strong> en <strong>" . htmlspecialchars($controller_name) . "</strong>.</p>";
            echo "<p>Intenta <a href=\"" . BASE_URL . "dashboard\">ir al Dashboard</a>.</p>";
            exit();
        }
    } else {
        // Si la clase del controlador no se encuentra (a pesar de que el archivo existe).
        http_response_code(404);
        echo "<h1>Error 404</h1><p>Clase de controlador no encontrada: <strong>" . htmlspecialchars($controller_name) . "</strong>.</p>";
        echo "<p>Intenta <a href=\"" . BASE_URL . "dashboard\">ir al Dashboard</a>.</p>";
        exit();
    }
} else {
    // Si el archivo del controlador no existe.
    http_response_code(404);
    echo "<h1>Error 404</h1><p>Controlador no encontrado: <strong>" . htmlspecialchars($controller_name) . "</strong>. Archivo: " . htmlspecialchars($controller_file) . "</p>";
    echo "<p>Intenta <a href=\"" . BASE_URL . "dashboard\">ir al Dashboard</a>.</p>";
    exit();
}

?>