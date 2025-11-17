<?php
// 1. Incluir conexión
require_once __DIR__ . '/clases/conexion.php'; 

$mensaje = ""; // Mensaje de éxito o error

// 2. Procesar formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoger y sanitizar los datos
    $tipo_documento    = mysqli_real_escape_string($conexion, $_POST['tipo_documento']);
    $numero_documento  = mysqli_real_escape_string($conexion, $_POST['numero_documento']);
    $nombre            = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $fecha_nacimiento  = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
    $telefono          = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $direccion         = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $activo            = mysqli_real_escape_string($conexion, $_POST['activo']);

    // Insertar en la tabla Pacientes (sin email si no existe)
    $sql = "INSERT INTO Pacientes (tipo_documento, numero_documento, nombre, fecha_nacimiento, telefono, direccion, activo) 
            VALUES ('$tipo_documento', '$numero_documento', '$nombre', '$fecha_nacimiento', '$telefono', '$direccion', '$activo')";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div class='alert alert-success text-center'>Paciente agregado con éxito.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>Error al guardar: " . mysqli_error($conexion) . "</div>";
    }
}

include 'header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Ficha de Nuevo Paciente</h2>

    <?php echo $mensaje; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="agregar_pacientes.php">
                        
                        <div class="mb-3">
                            <label for="tipo_documento" class="form-label">Tipo Documento:</label>
                            <input type="text" id="tipo_documento" name="tipo_documento" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="numero_documento" class="form-label">Número Documento:</label>
                            <input type="text" id="numero_documento" name="numero_documento" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección:</label>
                            <input type="text" id="direccion" name="direccion" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="activo" class="form-label">Activo:</label>
                            <select id="activo" name="activo" class="form-select" required>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Guardar Paciente</button>
                        </div>

                    </form>

                    <p class="mt-3 text-center">
                        <a href="listar_pacientes.php">⬅ Volver al Listado de Pacientes</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
