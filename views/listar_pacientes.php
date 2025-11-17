<?php
// Incluir conexión
require_once __DIR__ . '/clases/conexion.php';

// Capturar mensajes GET (agregado, editado, eliminado, error)
$mensaje_alerta = "";
if (isset($_GET['mensaje'])) {
    switch ($_GET['mensaje']) {
        case 'eliminado':
            $mensaje_alerta = "<div class='alert alert-success text-center'>Paciente eliminado con éxito.</div>";
            break;
        case 'error':
            $mensaje_alerta = "<div class='alert alert-danger text-center'>Error al realizar la operación.</div>";
            break;
        case 'error_id':
            $mensaje_alerta = "<div class='alert alert-warning text-center'>ID inválido.</div>";
            break;
        case 'agregado':
            $mensaje_alerta = "<div class='alert alert-success text-center'>Paciente agregado con éxito.</div>";
            break;
        case 'editado':
            $mensaje_alerta = "<div class='alert alert-success text-center'>Paciente actualizado con éxito.</div>";
            break;
    }
}

// Consulta SQL para seleccionar pacientes
$sql = "SELECT id_paciente, tipo_documento, numero_documento, nombre, fecha_nacimiento, telefono, direccion, activo 
        FROM Pacientes ORDER BY nombre ASC";

$resultado = mysqli_query($conexion, $sql);
if (!$resultado) die("Error al consultar la base de datos: " . mysqli_error($conexion));

$num_pacientes = mysqli_num_rows($resultado);

// Incluir header
include 'header.php';
?>

<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">
        Listado de Pacientes Registrados (<?= $num_pacientes ?>)
    </h2>

    <?php if($mensaje_alerta) echo $mensaje_alerta; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="agregar_pacientes.php" class="btn btn-primary">➕ Agregar Nuevo Paciente</a>

        <form class="d-flex" method="get">
            <input type="search" name="q" class="form-control me-2" placeholder="Buscar por nombre...">
            <button class="btn btn-outline-secondary">Buscar</button>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Documento</th>
                            <th>Fecha Nacimiento</th>
                            <th>Teléfono</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($num_pacientes > 0): ?>
                            <?php while ($paciente = mysqli_fetch_assoc($resultado)): 
                                $estado_activo = ($paciente['activo'] == 1) ? 
                                    '<span class="badge bg-success">Sí</span>' : 
                                    '<span class="badge bg-secondary">No</span>';
                                $documento_completo = htmlspecialchars($paciente['tipo_documento']) . ': ' . htmlspecialchars($paciente['numero_documento']);
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($paciente['id_paciente']); ?></td>
                                    <td class="text-start ps-4"><?= htmlspecialchars($paciente['nombre']); ?></td>
                                    <td><?= $documento_completo; ?></td>
                                    <td><?= htmlspecialchars($paciente['fecha_nacimiento']); ?></td>
                                    <td><?= htmlspecialchars($paciente['telefono']); ?></td>
                                    <td><?= $estado_activo; ?></td>
                                    <td>
                                        <a href="editar_paciente.php?id=<?= $paciente['id_paciente']; ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                                        <a href="eliminar_paciente.php?id=<?= $paciente['id_paciente']; ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Seguro que deseas eliminar a <?= htmlspecialchars($paciente['nombre']); ?>?');">
                                           🗑️ Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="py-3">No hay pacientes registrados en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
mysqli_close($conexion);
include 'footer.php';
?>