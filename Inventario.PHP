<?php
include ("controlador.php");

// Procesar filtros
$modelo = isset($_GET['model']) ? $_GET['model'] : '';
$estado = isset($_GET['state']) ? $_GET['state'] : '';

// Conectar a la base de datos
$Con = Conectar();

// Construir la consulta SQL con los filtros
$SQL = "SELECT v.cModelo, v.nYear, v.nPrecio, v.cInterior, v.cColor, v.cRin, s.cNombreS, s.cDireccionS
        FROM t_vehiculos v
        JOIN t_inventarios i ON v.nVehiculoID = i.nVehiculoID
        JOIN t_sucursales s ON i.nSucursalID = s.nSucursalID
        WHERE i.cEstadoI = 'Disponible'";

if (!empty($modelo)) {
    $SQL .= " AND v.cModelo = '$modelo'";
}

if (!empty($estado)) {
    $SQL .= " AND s.cDireccionS LIKE '%$estado%'";
}

// Ejecutar la consulta
$Result = Ejecutar($Con, $SQL);

// Desconectar de la base de datos
Desconectar($Con);

// Preparar el mensaje de resultado
$mensaje = '';
if (mysqli_num_rows($Result) > 0) {
    $mensaje .= '<table border="1"><tr><th>Imagen</th><th>Modelo</th><th>Año</th><th>Precio</th><th>Interior</th><th>Color</th><th>Rin</th><th>Sucursal</th><th>Dirección</th></tr>';
    while ($Fila = mysqli_fetch_assoc($Result)) {
        // Generar el nombre de la imagen basado en el modelo y el color, eliminando espacios
        $modeloImagen = strtolower(str_replace(' ', '_', trim($Fila['cModelo'])));
        $colorImagen = strtolower(str_replace(' ', '_', trim($Fila['cColor'])));
        $nombreImagen = $modeloImagen . '-' . $colorImagen . '.jpg';

        // Generar la fila de la tabla
        $mensaje .= '<tr>';
        $mensaje .= '<td data-label="Imagen"><img src="Images/ModelosInventario/' . $nombreImagen . '" alt="' . $Fila['cModelo'] . ' ' . $Fila['cColor'] . '" width="100"></td>';
        $mensaje .= '<td data-label="Modelo">' . $Fila['cModelo'] . '</td>';
        $mensaje .= '<td data-label="Año">' . $Fila['nYear'] . '</td>';
        $mensaje .= '<td data-label="Precio">' . $Fila['nPrecio'] . '</td>';
        $mensaje .= '<td data-label="Interior">' . $Fila['cInterior'] . '</td>';
        $mensaje .= '<td data-label="Color">' . $Fila['cColor'] . '</td>';
        $mensaje .= '<td data-label="Rin">' . $Fila['cRin'] . '</td>';
        $mensaje .= '<td data-label="Sucursal">' . $Fila['cNombreS'] . '</td>';
        $mensaje .= '<td data-label="Dirección">' . $Fila['cDireccionS'] . '</td>';
        $mensaje .= '</tr>';
    }
    $mensaje .= '</table>';
} else {
    $mensaje .= '<p>No se encontraron coches disponibles con los filtros seleccionados.</p>';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/styleInventario.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.html">
                <img src="Images/BYD_Logo.png" alt="BYD Logo"> <!-- Enlace al logo como imagen -->
            </a>
        </div>
        <div class="bars" onclick="toggleMenu()">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <nav class="nav-bar">
            <ul>
                <li>
                    <a href="#" id="modelos" class="active">Modelos</a>
                    <div class="modelosMenu">
                        <label for="option1" class="radio-button"
                            onclick="showImage('Images/modelos/han.png', 'han.html')">BYD HAN</label>
                        <label for="option2" class="radio-button"
                            onclick="showImage('Images/modelos/tang.png', 'tang.html')">BYD TANG</label>
                        <label for="option3" class="radio-button"
                            onclick="showImage('Images/modelos/yuan.png', 'yuan.html')">BYD YUAN</label>
                        <label for="option4" class="radio-button"
                            onclick="showImage('Images/modelos/seal.png', 'seal.html')">BYD SEAL</label>
                        <label for="option2" class="radio-button"
                            onclick="showImage('Images/modelos/dolphin.png', 'dolphin.html')">BYD DOLPHIN</label>
                        <label for="option3" class="radio-button"
                            onclick="showImage('Images/modelos/dolphinMini.png', 'dolphinMini.html')">BYD DOLPHIN
                            MINI</label>
                        <label for="option4" class="radio-button"
                            onclick="showImage('Images/modelos/song.png', 'song.html')">BYD SONG</label>
                        <img id="modelImage" class="option-image" style="display: none;" />
                        <!-- Contenedor de imagen inicial oculto -->
                    </div>
                </li>
                <li>
                    <a href="AcercaBYD.html" id="acerca" class="active">Acerca de BYD</a>
                </li>
                <li>
                    <a href="Inventario.php" id="inventario" class="active">Inventario</a>
                </li>
            </ul>
        </nav>
        <div class="help">
            <a href="help.html">
                <img src="Images/Help.png" alt="Help"> <!-- Enlace al logo como imagen -->
            </a>
        </div>
    </header>

    <div class="main">
        <section class="section">
            <div class="filters">
                <form method="GET" action="Inventario.php" enctype="multipart/form-data">
                    <label for="model">Modelo:</label>
                    <select id="model" name="model">
                        <option value="">Todos los modelos</option>
                        <option value="BYD-TANG">BYD TANG</option>
                        <option value="BYD-YUAN">BYD YUAN</option>
                        <option value="BYD-SEAL">BYD SEAL</option>
                        <option value="BYD-DOLPHIN">BYD DOLPHIN</option>
                        <option value="BYD-DOLPHIN-MINI">BYD DOLPHIN MINI</option>
                        <option value="BYD-SONG">BYD SONG</option>
                        <option value="BYD-HAN">BYD HAN</option>
                    </select>
                    <label for="state">Sucursal:</label>
                    <select id="state" name="state">
                        <option value="">Todas las sucursales</option>
                        <option value="Sonora">Sonora</option>
                        <option value="Sinaloa">Sinaloa</option>
                        <option value="Aguascalientes">Aguascalientes</option>
                        <option value="Guanajuato">Guanajuato</option>
                        <option value="Querétaro">Querétaro</option>
                        <option value="Michoacán">Michoacán</option>
                        <option value="Estado de México">Estado de México</option>
                        <option value="Puebla">Puebla</option>
                        <option value="Quintana Roo">Quintana Roo</option>
                        <option value="Jalisco">Jalisco</option>
                        <option value="Nuevo León">Nuevo León</option>
                        <option value="CDMX">CDMX</option>
                        <option value="Hidalgo">Hidalgo</option>
                    </select>
                    <button type="submit">Buscar</button>
                </form>
            </div>
        </section>
        <aside class="aside">
            <div class="cars">
                <?php echo $mensaje; ?>
            </div>
        </aside>
    </div>

    <script src="scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>