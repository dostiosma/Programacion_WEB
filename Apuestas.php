<?php
require('fpdf.php');

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "concurso") or die("Error al conectar a la base de datos");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $cedula = $_POST["cedula"];

    // Validar los datos del formulario
    if ($nombre == "" || $apellido == "" || $cedula == "") {
        echo "Debes llenar todos los campos del formulario";
    } else {
        // Insertar los datos en la tabla
        $consulta = "INSERT INTO consumidores (nombre, apellido, cedula) VALUES ('$nombre', '$apellido', '$cedula')";
        $resultado = mysqli_query($conexion, $consulta) or die("Error al insertar los datos en la base de datos");
    }
}
// Crear un objeto FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Configurar fuentes y colores
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(40, 10, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Apellido', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Cedula', 1, 1, 'C', 1);

// Mostrar registros en la tabla "consumidores"
$consulta = "SELECT * FROM consumidores";
$resultado = mysqli_query($conexion, $consulta) or die("Error al consultar la base de datos");

echo "<h2>Registros en la tabla:</h2>";

echo "<table border='1'>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Cédula</th>
        </tr>";

while ($fila = mysqli_fetch_array($resultado)) {
    echo "<tr>
            <td>" . $fila['nombre'] . "</td>
            <td>" . $fila['apellido'] . "</td>
            <td>" . $fila['cedula'] . "</td>
        </tr>";
}

echo "</table>";

$nombre_a_eliminar = "jorge"; // Cambia esto por el nombre que quieras eliminar

$consulta_eliminar = "DELETE FROM consumidores WHERE nombre = '$nombre_a_eliminar'";
$resultado_eliminar = mysqli_query($conexion, $consulta_eliminar);

$cedula_original = "123456";
$nueva_cedula = "1010765412";

// Consulta de actualización
$consulta_actualizar = "UPDATE consumidores SET cedula = '$nueva_cedula' WHERE cedula = '$cedula_original'";
$resultado_actualizar = mysqli_query($conexion, $consulta_actualizar);

// Cerrar la conexión
mysqli_close($conexion);
?>
