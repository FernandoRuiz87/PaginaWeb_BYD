<?php
ob_start();
function GenerarCita($NombreCompleto, $ID_cliente, $ID_reserva, $Fecha, $Hora, $Sucursal, $ID_vehiculo, $Empleado, $Con)
{
    require ('fpdf.php');

    $SQL = "SELECT cNombreE AS Nombre, cApellidoE AS Apellido FROM t_empleados WHERE nEmpleadoID = '$Empleado'";
    $Resultado = Ejecutar($Con, $SQL);
    $Fila = mysqli_fetch_array($Resultado);
    $NombreEmpleado = $Fila['Nombre'] . ' ' . $Fila['Apellido'];

    $SQL = "SELECT * FROM t_sucursales WHERE nSucursalID = '$Sucursal'";
    $InfoSucursal = Ejecutar($Con, $SQL);
    $FilaSucursal = mysqli_fetch_array($InfoSucursal);
    $Direccion = $FilaSucursal['cNombreS'] . ' - ' . $FilaSucursal['cDireccionS'];
    $TelefonoSucursal = $FilaSucursal['cTelefonoS'];
    $CorreoSucursal = $FilaSucursal['cCorreoS'];

    $SQL = "SELECT * FROM t_vehiculos WHERE nVehiculoID = '$ID_vehiculo'";
    $Resultado = Ejecutar($Con, $SQL);
    $Fila = mysqli_fetch_array($Resultado);
    $Vehiculo = $Fila['cModelo'] . ' | ' . $Fila['nYear'] . ' | ' . $Fila['cInterior'] . ' | ' . $Fila['cColor'];

    // Crear una instancia de FPDF
    $pdf = new FPDF('P', 'mm', 'Letter');

    $pdf->AddPage();
    $pdf->SetMargins(10, 0, 10);

    //Header
    $pdf->SetFillColor(37, 39, 40);
    $pdf->Rect(0, 0, 250, 25, 'F');
    $pdf->Image("Citas/Imagenes/BYD_Logo.png", 10, 5, 60, 15);

    $pdf->SetXY(90, 12);
    $pdf->SetFont('Helvetica', '', 30);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(30, 2, utf8_decode("Confirmación de cita"), 0, 1, 'L');


    // Ajustes iniciales
    $pdf->SetFont('Times', '', 15);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->Line(10, 35, 200, 35);

    // Asunto
    $pdf->Ln(25);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 2, utf8_decode("Asunto: "), 0, 0, 'L');
    $pdf->SetFont('Times', '', 15);
    $pdf->Cell(0, 2, utf8_decode("Confirmación de su cita"), 0, 1, 'L');

    $pdf->Line(10, 45, 200, 45);

    // Saludo
    $pdf->Ln(8);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(0, 2, utf8_decode("Estimado/a: $NombreCompleto"), 0, 1, 'L');

    // Mensaje de confirmación
    $pdf->Ln(3);
    $pdf->SetFont('Times', '', 15);
    $pdf->MultiCell(0, 6, utf8_decode("Nos complace confirmar su cita con nosotros. A continuación, encontrará los detalles de su cita:"), 0, 'J');

    //Informacion
    $pdf->SetLeftMargin(15); //Aplica sangria

    $pdf->Ln(6);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 2, utf8_decode("- ID de cliente:"), 0, 0, 'L');
    $pdf->SetFont('Times', '', 15);
    $pdf->SetLeftMargin(50);
    $pdf->Cell(24, 2, utf8_decode("$ID_cliente"), 0, 1, 'L');
    $pdf->SetLeftMargin(15);

    $pdf->Ln(8);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 2, utf8_decode("- ID de reservación:"), 0, 0, 'L');
    $pdf->SetFont('Times', '', 15);
    $pdf->SetLeftMargin(60);
    $pdf->Cell(24, 2, utf8_decode("$ID_reserva"), 0, 1, 'L');
    $pdf->SetLeftMargin(15);

    $pdf->Ln(8);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 2, utf8_decode("- Fecha:"), 0, 0, 'L');
    $pdf->SetFont('Times', '', 15);
    $pdf->Cell(24, 2, utf8_decode("$Fecha"), 0, 1, 'L');
    $pdf->SetLeftMargin(15);

    $pdf->Ln(8);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 2, utf8_decode("- Hora:"), 0, 0, 'L');
    $pdf->SetFont('Times', '', 15);
    $pdf->Cell(24, 2, utf8_decode("$Hora"), 0, 1, 'L');

    $pdf->Ln(8);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 2, utf8_decode("- Sucursal:"), 0, 0, 'L');

    $pdf->SetLeftMargin(43);

    $pdf->SetFont('Times', '', 15);
    $pdf->Ln(-2);
    $pdf->MultiCell(0, 6, utf8_decode("$Direccion"), 0, 'L');

    $pdf->SetLeftMargin(15);

    $pdf->Ln(2);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 10, utf8_decode("- Vehículo: "), 0, 0, 'L');

    $pdf->SetFont('Times', '', 15);
    $pdf->Cell(0, 10, utf8_decode("    $Vehiculo"), 0, 1, 'L');

    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 10, utf8_decode("- Atendido por:"), 0, 0, 'L');
    $pdf->SetFont('Times', '', 15);
    $pdf->SetLeftMargin(52);
    $pdf->Cell(0, 10, utf8_decode("$NombreEmpleado"), 0, 1, 'L');

    // Restaurar el margen izquierdo original
    $pdf->SetLeftMargin(10);

    //Informacion adicional
    $pdf->Ln(3);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 10, utf8_decode("Instrucciones Adicionales"), 0, 1, 'L');

    $pdf->SetLeftMargin(15);

    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(24, 6, utf8_decode("- Por favor, llegue 15 minutos antes de su cita."), 0, 1, 'L');
    $pdf->Cell(24, 6, utf8_decode("- Por favor, recuerde traer este documento de información con usted."), 0, 1, 'L');
    $pdf->Cell(24, 6, utf8_decode("- Traiga consigo una identificación válida."), 0, 1, 'L');
    $pdf->Cell(24, 6, utf8_decode("- Si necesita reprogramar o cancelar su cita, comuníquese con nosotros con al menos 24 horas de antelación."), 0, 1, 'L');

    $pdf->SetLeftMargin(10);

    //Agradecimientos
    $pdf->Ln(3);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 10, utf8_decode("Agradecimiento:"), 0, 1, 'L');
    $pdf->SetFont('Times', '', 12);
    $pdf->MultiCell(0, 5, utf8_decode("Queremos agradecerle por elegir nuestros servicios. Valoramos su confianza y estamos comprometidos a brindarle la mejor experiencia posible. Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nosotros."), 0, 'J');

    //Datos de contacto
    $pdf->Ln(3);
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Cell(24, 10, utf8_decode("Datos de contacto:"), 0, 1, 'L');

    $pdf->Ln(3);
    $pdf->SetFont('Times', 'B', 13);
    $pdf->Cell(24, 2, utf8_decode("- Teléfono de la sucursal:"), 0, 0, 'L');
    $pdf->SetFont('Times', '', 13);
    $pdf->SetLeftMargin(60);
    $pdf->Cell(24, 2, utf8_decode("$TelefonoSucursal"), 0, 1, 'L');

    $pdf->SetLeftMargin(10);
    $pdf->Ln(5);
    $pdf->SetFont('Times', 'B', 13);
    $pdf->Cell(24, 2, utf8_decode("- Correo electronico de la sucursal:"), 0, 0, 'L');

    $pdf->SetLeftMargin(80);

    $pdf->SetFont('Times', 'U', 13);
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(24, 2, utf8_decode("$CorreoSucursal"), 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetLeftMargin(10);

    $pdf->Ln(10);
    $pdf->SetFont('Times', '', 15);
    $pdf->Cell(24, 2, utf8_decode("Esperamos verle pronto."), 0, 1, 'L');

    $pdf->Ln(5);
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(24, 2, utf8_decode("Atentamente, el equipo de BYD."), 0, 1, 'L');

    $pdf->Ln(3);
    $pdf->Cell(24, 2, utf8_decode("Innovando el Futuro de la Movilidad Sostenible"), 0, 0, 'L');

    //Footer
    $pdf->SetFillColor(37, 39, 40);
    $pdf->Rect(0, 265, 250, 25, 'F');
    $pdf->Image("Citas/Imagenes/BYD_Logo.png", 10, 5, 60, 15);

    $pdf->Image("Citas/Imagenes/Firmas_cover.png", 90, 240, 60, 15);

    // Guardar el PDF en una variable
    ob_end_clean(); // Limpiar el buffer de salida sin enviar al navegador
    $pdf_output = $pdf->Output('', 'S');

    // Especifica la ruta y el nombre del archivo
    $ruta_archivo = "Citas/PDFs/Cita_Cliente_" . $ID_cliente . ".pdf";

    // Escribe el contenido del PDF en el archivo
    file_put_contents($ruta_archivo, $pdf_output);
    ob_end_flush();
    $pdf->Output();

    // Output del archivo PDF para mostrar en el navegador
    $pdf->Output('D', 'Cita_Cliente_' . $ID_cliente . '.pdf');
}
?>