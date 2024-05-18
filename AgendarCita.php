<?php
include ("controlador.php");
include ("Citas\CITA_PDF.php");

$Nombre = $_POST['nombre'];
$Apellido = $_POST['apellido'];
$Telefono = $_POST['telefono'];
$Correo = $_POST['correo'];
$Direccion = $_POST['direccion'];
$Sucursal = $_POST['sucursal'];
$Modelo = $_POST['modelo'];
$Fecha = $_POST['fecha'];
$Hora = $_POST['hora'];

$mensaje = "";

$Con = Conectar();

//Se inserta el nuevo usuario
$SQL = "INSERT INTO t_clientes (cNombreC, cApellidoC, cTelefonoC, cCorreoC, cDireccionC)
     VALUES ('$Nombre','$Apellido',$Telefono,'$Correo','$Direccion');";

//$Result = Ejecutar($Con, $SQL); //Descomentar para iunsertar en clientes

//Obtiene el ID del ultimo cliente
$SQL = "SELECT nClienteID FROM t_clientes ORDER BY nClienteID DESC LIMIT 1;";
$ID_cliente = Ejecutar($Con, $SQL);
$ID_cliente = mysqli_fetch_array($ID_cliente)['nClienteID'];

//Verifica que el vehiculo este en existencia
$SQL = "SELECT * FROM v_inventarios_disponibles WHERE cModelo = '$Modelo' AND nSucursalID = '$Sucursal';";
$ID_vehiculo = Ejecutar($Con, $SQL);

if (mysqli_num_rows($ID_vehiculo) > 0) { //El vehiculo esta en inventario
    $ID_vehiculo = mysqli_fetch_array($ID_vehiculo)['nVehiculoID'];

    //Asigna la reserva al empleado con menos reservas
    $SQL = "SELECT e.nEmpleadoID FROM v_empleados_sucursales e LEFT JOIN (
        SELECT nEmpleadoID, COUNT(*) AS reservas
        FROM t_reservas
        GROUP BY nEmpleadoID
    ) r ON e.nEmpleadoID = r.nEmpleadoID
    WHERE e.nSucursalID = '$Sucursal'
    ORDER BY COALESCE(r.reservas, 0) ASC LIMIT 1;";

    $Empleado = Ejecutar($Con, $SQL);
    $Empleado = mysqli_fetch_array($Empleado)['nEmpleadoID'];

    $SQL = "INSERT INTO t_reservas(dFechaR,dHoraR,nClienteID,nVehiculoID,nEmpleadoID,nSucursalID) VALUES
    ('$Fecha','$Hora','$ID_cliente','$ID_vehiculo','$Empleado','$Sucursal');";

    //$Result = Ejecutar($Con, $SQL); //Descomentar para iunsertar en reservas

    $SQL = "SELECT nReservaID FROM t_reservas ORDER BY nReservaID DESC LIMIT 1;";
    $ID_Reserva = Ejecutar($Con, $SQL);
    $ID_Reserva = mysqli_fetch_array($ID_Reserva)['nReservaID'];

    $NombreCompleto = $Nombre . " " . $Apellido;
    $mensaje = "Cita agendada correctamente";

    GenerarCita($NombreCompleto, $ID_cliente, $ID_Reserva, $Fecha, $Hora, $Sucursal, $ID_vehiculo, $Empleado, $Con);
    $mensaje = "Cita agendada correctamente";
} else {
    $mensaje = "El vehículo seleccionado no esta disponible en este momento";
}

Desconectar($Con);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link rel="icon" href="Images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styleAgendar.css">

    <script>
        // Función para obtener la fecha actual en formato AAAA-MM-DD
        function obtenerFechaActual() {
            const hoy = new Date();
            let mes = hoy.getMonth() + 1;
            let dia = hoy.getDate();

            // Agrega un cero delante si el mes o el día son menores a 10
            mes = mes < 10 ? '0' + mes : mes;
            dia = dia < 10 ? '0' + dia : dia;

            return hoy.getFullYear() + '-' + mes + '-' + dia;
        }
    </script>
</head>

<body>

    <header>
        <script>
            window.onload = function () {
                const params = new URLSearchParams(window.location.search);
                const modelo = params.get('modelo'); // Obtiene el modelo desde la URL
                if (modelo) {
                    const select = document.getElementById('modelSelect');
                    select.value = modelo; // Establece el valor del select al modelo obtenido
                }
            };

        </script>

        <div class="logo">
            <a href="index.html">
                <img src="Images/BYD_Logo.png" alt="BYD Logo">
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
                <img src="Images/Help.png" alt="Help">
            </a>
        </div>

    </header>

    <form>
        <div class="container">
            <section class="two">
                <div class="form-container">
                    <div class="checkboxes">
                        <a>
                            <?php echo $mensaje . "<br>" ?>
                        </a>
                    </div>
                    <div class="button-regresar">
                        <a href="index.html" class="boton-regresar">Imprimir hoja de información</a>
                    </div>
            </section>
        </div>
    </form>
    <div id="confirmationMessage" style="display: none;">Cita agendada correctamente</div>
    <script src="scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('fecha').setAttribute('min', obtenerFechaActual());
    </script>
</body>

</html>