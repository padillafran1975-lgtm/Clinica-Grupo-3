<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/clases/conexion.php'; 

$mensaje = "";
$paciente = null;

// 1. Cargar información del paciente
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_paciente_editar = mysqli_real_escape_string($conexion, $_GET['id']);
    $sql_select = "SELECT * FROM Pacientes WHERE id_paciente = '$id_paciente_editar'";
    $resultado = mysqli_query($conexion, $sql_select);

    if ($resultado && mysqli_num_rows($resultado) == 1) {
        $paciente = mysqli_fetch_assoc($resultado);
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>Error: Paciente no encontrado.</div>";
    }
}

// 2. Procesar formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente      = mysqli_real_escape_string($conexion, $_POST['id_paciente']);
    $tipo_documento   = mysqli_real_escape_string($conexion, $_POST['tipo_documento']);
    $numero_documento = mysqli_real_escape_string($conexion, $_POST['numero_documento']);
    $nombre           = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
    $telefono         = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $direccion        = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $activo           = mysqli_real_escape_string($conexion, $_POST['activo']);

    $sql_update = "UPDATE Pacientes SET
                    tipo_documento = '$tipo_documento',
                    numero_documento = '$numero_documento',
                    nombre = '$nombre',
                    fecha_nacimiento = '$fecha_nacimiento',
                    telefono = '$telefono',
                    direccion = '$direccion',
                    activo = '$activo'
                   WHERE id_paciente = '$id_paciente'";

    if (mysqli_query($conexion, $sql_update)) {
        $mensaje = "<div class='alert alert-success text-center'>Paciente actualizado con éxito.</div>";

        // Actualizar variable $paciente para mostrar datos en el formulario
        $paciente['tipo_documento'] = $tipo_documento;
        $paciente['numero_documento'] = $numero_documento;
        $paciente['nombre'] = $nombre;
        $paciente['fecha_nacimiento'] = $fecha_nacimiento;
        $paciente['telefono'] = $telefono;
        $paciente['direccion'] = $direccion;
        $paciente['activo'] = $activo;
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>Error al actualizar: " . mysqli_error($conexion) . "</div>";
    }
}

// Si no se cargó paciente, no mostramos el formulario
if (!$paciente) {
    echo "<div class='alert alert-danger text-center'>Paciente no encontrado.</div>";
    echo "<p class='text-center'><a href='listar_pacientes.php'>Volver al listado</a></p>";
    exit();
}

include 'header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Editar Paciente: <?= htmlspecialchars($paciente['nombre']); ?></h2>

    <?= $mensaje ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="editar_paciente.php?id=<?= htmlspecialchars($paciente['id_paciente']); ?>">
                        <input type="hidden" name="id_paciente" value="<?= htmlspecialchars($paciente['id_paciente']); ?>">

                        <div class="mb-3">
                            <label for="tipo_documento" class="form-label">Tipo Documento:</label>
                            <input type="text" id="tipo_documento" name="tipo_documento" class="form-control" required
                                   value="<?= htmlspecialchars($paciente['tipo_documento']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="numero_documento" class="form-label">Número Documento:</label>
                            <input type="text" id="numero_documento" name="numero_documento" class="form-control" required
                                   value="<?= htmlspecialchars($paciente['numero_documento']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required
                                   value="<?= htmlspecialchars($paciente['nombre']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required
                                   value="<?= htmlspecialchars($paciente['fecha_nacimiento']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" class="form-control"
                                   value="<?= htmlspecialchars($paciente['telefono']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección:</label>
                            <input type="text" id="direccion" name="direccion" class="form-control"
                                   value="<?= htmlspecialchars($paciente['direccion']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="activo" class="form-label">Activo:</label>
                            <select id="activo" name="activo" class="form-select" required>
                                <option value="1" <?= $paciente['activo'] == 1 ? 'selected' : '' ?>>Sí</option>
                                <option value="0" <?= $paciente['activo'] == 0 ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
