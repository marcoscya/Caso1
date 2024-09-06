<?php
$carpetaNombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$carpetaRuta = "./descarga/" . $carpetaNombre;


/*=========================================*/
/*           BOTON ELIMINAR                */
/*=========================================*/
if (isset($_POST['eliminarTodo'])) {
    foreach(glob("$carpetaRuta/*.*") as $archivos_eliminados) {
        unlink($archivos_eliminados);
    }
}
/*=========================================*/
/*                                         */ 
/*=========================================*/



/*=========================================*/
/*              BOTON DESCARGAR            */
/*=========================================*/
if (isset($_POST['descargar'])) {
    $carpetaNombre = $_GET['nombre'];
    $carpetaRuta = "./descarga/" . $carpetaNombre;
    
    // Obtener todos los archivos en la carpeta
    $archivos = glob("$carpetaRuta/*.*");
    
    // Verificar si hay al menos un archivo
    if (count($archivos) > 0) {
        $zip = new ZipArchive();
        $zipName = 'archivos_' . $carpetaNombre . '.zip';

        // Crear un nuevo archivo ZIP
        if ($zip->open($zipName, ZipArchive::CREATE) !== TRUE) {
            exit("No se pudo crear el archivo ZIP.");
        }

        // Añadir archivos al ZIP
        foreach ($archivos as $file) {
            $zip->addFile($file, basename($file));
        }

        // Cerrar el archivo ZIP
        $zip->close();

        // Forzar la descarga del archivo ZIP
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipName . '"');
        header('Content-Length: ' . filesize($zipName));
        readfile($zipName);

        // Eliminar el archivo ZIP después de la descarga
        unlink($zipName);
    } else {
        // Opcional: Puedes mostrar un mensaje si no hay archivos
        echo "No hay archivos para descargar. Ingresa uno";
    }
}
/*=========================================*/
/*                                         */ 
/*=========================================*/

/*
try {
    if (!file_exists($carpetaRuta)) {
        mkdir($carpetaRuta, 0755, true);
        $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
    } else {
        $mensaje = "La carpeta '$carpetaNombre' ya existe.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['archivo'])) {
            $archivo = $_FILES['archivo'];

            if (move_uploaded_file($archivo['tmp_name'], $carpetaRuta . '/' . $archivo['name'])) {
                $subido = true;
                $mensaje = "Archivo subido con éxito.";
            } else {
                throw new Exception("Error al subir el archivo.");
            }
        }
    }

    if (isset($_POST['eliminarArchivo'])) {
        $archivoAEliminar = $_POST['eliminarArchivo'];
        $archivoRutaAEliminar = $carpetaRuta . '/' . $archivoAEliminar;

        if (file_exists($archivoRutaAEliminar)) {
            if (unlink($archivoRutaAEliminar)) {
                $mensaje = "Archivo '$archivoAEliminar' eliminado con éxito.";
            } else {
                throw new Exception("Error al eliminar el archivo.");
            }
        } else {
            throw new Exception("El archivo '$archivoAEliminar' no existe.");
        }
    }
} catch (Exception $e) {
    $mensaje = "Error: " . htmlspecialchars($e->getMessage());
}*/

/*verificacion*/
try {
    if (!file_exists($carpetaRuta)) {
        mkdir($carpetaRuta, 0755, true);
        $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
    } else {
        $mensaje = "La carpeta '$carpetaNombre' ya existe.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['archivo'])) {
            $archivos = $_FILES['archivo'];
            
            // Recorrer cada archivo
            foreach ($archivos['tmp_name'] as $key => $tmp_name) {
                $nombre_real = $archivos['name'][$key];
                $ruta_destino = $carpetaRuta . '/' . $nombre_real;
                
                if (move_uploaded_file($tmp_name, $ruta_destino)) {
                    $mensaje = "Archivo '$nombre_real' subido con éxito.";
                } else {
                    throw new Exception("Error al subir el archivo '$nombre_real'.");
                }
            }
        }
    }

    if (isset($_POST['eliminarArchivo'])) {
        $archivoAEliminar = $_POST['eliminarArchivo'];
        $archivoRutaAEliminar = $carpetaRuta . '/' . $archivoAEliminar;

        if (file_exists($archivoRutaAEliminar)) {
            if (unlink($archivoRutaAEliminar)) {
                $mensaje = "Archivo '$archivoAEliminar' eliminado con éxito.";
            } else {
                throw new Exception("Error al eliminar el archivo.");
            }
        } else {
            throw new Exception("El archivo '$archivoAEliminar' no existe.");
        }
    }
} catch (Exception $e) {
    $mensaje = "Error: " . htmlspecialchars($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir archivos</title>
    <script src="parametro.js?=v2"></script>
    <link rel="stylesheet" href="estilo.css?=v3">
    <link rel="manifest" href="manifest.json?=v2">
</head>

<body>
    <div class="caja-titulo">
    <ul>
        <li><h1>Compartir archivos <sup class="beta">BETA</sup></h1></li>
        <!--Boton Actualizar v.beta-->
        <li><button type="button" id="refreshButton" onclick="actualizarPagina()">Actualizar</button></li>
    </ul>
    </div>

    <div class="content">
        <h3>Sube tus archivos y comparte este enlace temporal: <span>ibu.pe/?nombre=<?php echo $carpetaNombre;?></span></h3>
        <div class="container">
            <div class="drop-area" id="drop-area">
                <form action="" id="form" method="POST" enctype="multipart/form-data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" style="fill:#0730c5;transform: ;msFilter:;"><path d="M13 19v-4h3l-4-5-4 5h3v4z"></path><path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path></svg> <br>
                    <!--archivo[] multiple array-->
                    <input type="file" class="file-input" name="archivo[]" id="archivo" onchange="document.getElementById('form').submit()" multiple>
                    <label class="text"> Arrastra tus archivos aquí<br>o</label>
                    <p class="text" ><b>Abre el explorador</b></p> 
                </form>
            </div>


            <div class="container2" id="container2">
            <div id="progress-container"></div>
                <div id="file-list" class="pila">
                    <?php
                    $targetDir = $carpetaRuta;

                    $files = scandir($targetDir);
                    $files = array_diff($files, array('.', '..'));

                    if (count($files) > 0) {
                        echo " <h3 style='margin-bottom:10px;'>Archivos Subidos:</h3>";

                        foreach ($files as $file) {
                            echo "<div class='archivos_subidos'>
                            <div><a href='$carpetaRuta/$file' download class='boton-descargar'>$file</a></div>
                            <div>
                            <form action='' method='POST' style='display:inline;'>
                                <input type='hidden' name='eliminarArchivo' value='$file'>
                                <button type='submit' class='btn_delete'>
                                    <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-trash' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                        <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                        <path d='M4 7l16 0' />
                                        <path d='M10 11l0 6' />
                                        <path d='M14 11l0 6' />
                                        <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                        <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        </div>";
                        }
                    } else {
                        echo "No se han subido archivos.";
                    }
                    ?>
                </div>
            </div>

            <div class="container3">
                <ul>
                    <li>
                    <!--BOTON DESCARGAR TODO-->
                    <form method="post" id="descargarForm">
                    <input type="hidden" name="descargar" value="true">
                    <button type="submit" class="boton-descargar-todo">
                        <p class="text">Descargar</p>
                        <div class="svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                            </svg>
                        </div>
                    </button>
                    </form>
                    </li>

                    <!--BOTON ELIMINAR TODO-->
                    <li>
                    <form method="POST">
                        <button type="submit" name="eliminarTodo" class="boton-eliminar">
	                        <p class="text">Eliminar</p>
                            <div class="svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                            </svg>
                            </div>
                        </button>
                    </form>   
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- <script src="parametro.js"></script> -->

</body>

<script>
    if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("sw.js");
    }
</script>

</html>
