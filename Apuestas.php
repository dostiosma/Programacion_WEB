<?php
require('fpdf.php');

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "concurso") or die("Error al conectar a la base de datos");

// Operación Crear (INSERT)
if (isset($_POST["submit"])) {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $cedula = $_POST["cedula"];

    if ($nombre != "" && $apellido != "" && $cedula != "") {
        $consulta = "INSERT INTO consumidores (nombre, apellido, cedula) VALUES ('$nombre', '$apellido', '$cedula')";
        $resultado = mysqli_query($conexion, $consulta) or die("Error al insertar los datos en la base de datos");
    } else {
        echo "Debes llenar todos los campos del formulario";
    }
}

// Operación Eliminar (DELETE)
if (isset($_GET['eliminar'])) {
    $nombre_a_eliminar = $_GET['eliminar'];
    $consulta_eliminar = "DELETE FROM consumidores WHERE nombre = '$nombre_a_eliminar'";
    $resultado_eliminar = mysqli_query($conexion, $consulta_eliminar);
}

// Operación Actualizar (UPDATE)
if (isset($_POST['actualizar'])) {
    $cedula_original = $_POST['cedula_original'];
    $nueva_cedula = $_POST['nueva_cedula'];

    $consulta_actualizar = "UPDATE consumidores SET cedula = '$nueva_cedula' WHERE cedula = '$cedula_original'";
    $resultado_actualizar = mysqli_query($conexion, $consulta_actualizar);
}

// Función para generar PDF con datos de la tabla
function generarPDF($conexion) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetFillColor(200, 200, 200);

    $consulta = "SELECT * FROM consumidores";
    $resultado = mysqli_query($conexion, $consulta) or die("Error al consultar la base de datos");

    while ($fila = mysqli_fetch_array($resultado)) {
        $pdf->Cell(40, 10, $fila['nombre'], 1, 0, 'C');
        $pdf->Cell(40, 10, $fila['apellido'], 1, 0, 'C');
        $pdf->Cell(40, 10, $fila['cedula'], 1, 1, 'C');
        $pdf->Ln();
    }

    $pdf->Output();
}

// Operación Leer (SELECT) y Generar PDF
generarPDF($conexion);

// Obtener registros de la base de datos para mostrar en la tabla HTML
$consulta_mostrar = "SELECT * FROM consumidores";
$resultado_mostrar = mysqli_query($conexion, $consulta_mostrar) or die("Error al consultar la base de datos");

echo "<table border='1'>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Cédula</th>
            <th>Acciones</th>
        </tr>";

while ($fila = mysqli_fetch_array($resultado_mostrar)) {
    echo "<tr>
            <td>" . $fila['nombre'] . "</td>
            <td>" . $fila['apellido'] . "</td>
            <td>" . $fila['cedula'] . "</td>
            <td>
                <a href='procesar.php?eliminar=" . $fila['nombre'] . "'>Eliminar</a>
                <form action='index.php' method='POST'>
                    <input type='hidden' name='cedula_original' value='" . $fila['cedula'] . "'>
                    <input type='text' name='nueva_cedula' placeholder='Nueva Cédula'>
                    <input type='submit' name='actualizar' value='Actualizar'>
                </form>
            </td>
        </tr>";
}

echo "</table>";

// Cerrar la conexión
mysqli_close($conexion);
?>
