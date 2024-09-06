<?php
// Nombre de la carpeta a crear (obtenido del parámetro)
$carpetaNombre = $_GET['nombre'];

// Ruta donde deseas crear la carpeta (por ejemplo, en la carpeta 'descarga')
$carpetaRuta = "./descarga/" . $carpetaNombre;

// Verifica si la carpeta ya existe antes de crearla
if (!file_exists($carpetaRuta)) {
    // Crea la carpeta con permisos adecuados (por ejemplo, 0755)
    mkdir($carpetaRuta, 0755, true);
    $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
} else {
    $mensaje = "La carpeta '$carpetaNombre' ya existe.";
}

// Procesar varios archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivos = $_FILES['archivo'];

    foreach ($archivos['tmp_name'] as $key => $tmp_name) {
        // Reemplazar espacios por guiones bajos en el nombre del archivo
        $nombre_real = str_replace(' ', '_', $archivos['name'][$key]);
        
        // Mover el archivo a la carpeta destino
        if (move_uploaded_file($tmp_name, $carpetaRuta . '/' . $nombre_real)) {
            echo "Archivo '$nombre_real' subido con éxito.";
        } else {
            echo "Error al subir el archivo '$nombre_real'.";
        }
    }
}
?>
