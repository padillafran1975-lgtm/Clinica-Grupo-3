<?php
// Incluimos la conexión.
require_once __DIR__ . '/clases/conexion.php'; 

// Incluir header (barra superior)
require_once __DIR__ . '/header.php';

$mensaje = "";
$medico = null;

// 1. CARGAR INFORMACIÓN DEL MÉDICO
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_medico_editar = mysqli_real_escape_string($conexion, $_GET['id']);
    
    $sql_select = "SELECT 
                        M.id_medico, 
                        M.id_usuario,
                        M.especialidad, 
                        M.disponible,
                        U.nombre,
                        U.correo
                   FROM Medicos M
                   INNER JOIN Usuarios U ON M.id_usuario = U.id_usuario
                   WHERE M.id_medico = '$id_medico_editar'";
    
    $resultado = mysqli_query($conexion, $sql_select);
    
    if ($resultado && mysqli_num_rows($resultado) == 1) {
        $medico = mysqli_fetch_assoc($resultado);
    } else {
        echo "<div class='alert alert-danger text-center mt-4'>Perfil de Médico no encontrado.</div>";
        exit();
    }
}

// 2. PROCESAR FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_medico_post = mysqli_real_escape_string($conexion, $_POST['id_medico']);
    $especialidad   = mysqli_real_escape_string($conexion, $_POST['especialidad']);
    $disponible     = mysqli_real_escape_string($conexion, $_POST['disponible']); 

    $sql_update = "UPDATE Medicos SET
                    especialidad = '$especialidad', 
                    disponible = '$disponible'
                   WHERE id_medico = '$id_medico_post'";
                   
    if (mysqli_query($conexion, $sql_update)) {
        $mensaje = "<div class='alert alert-success text-center'>La información del médico se ha actualizado con éxito.</div>";
        $medico['especialidad'] = $especialidad;
        $medico['disponible'] = $disponible;
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>Error al actualizar: " . mysqli_error($conexion) . "</div>";
    }
}

// Si no se cargó el médico
if (!$medico) {
    exit();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white text-center">
                    <h4 class="m-0">Editar Perfil del Médico</h4>
                </div>

                <div class="card-body">

                    <?php echo $mensaje; ?>

                    <p><strong>Usuario Asociado:</strong> <?php echo htmlspecialchars($medico['nombre']); ?> (ID Usuario: <?php echo htmlspecialchars($medico['id_usuario']); ?>)</p>
                    <p><strong>Correo:</strong> <?php echo htmlspecialchars($medico['correo']); ?></p>
                    <p><a href="listar_medicos.php" class="btn btn-secondary btn-sm mb-3">← Volver al Listado</a></p>

                    <form method="POST" action="editar_medico.php?id=<?php echo htmlspecialchars($medico['id_medico']); ?>">
                        
                        <input type="hidden" name="id_medico" value="<?php echo htmlspecialchars($medico['id_medico']); ?>">

                        <div class="mb-3">
                            <label for="especialidad" class="form-label">Especialidad</label>
                            <input type="text" id="especialidad" name="especialidad" class="form-control"
                                   required value="<?php echo htmlspecialchars($medico['especialidad']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="disponible" class="form-label">Disponibilidad</label>
                            <select id="disponible" name="disponible" class="form-select" required>
                                <option value="1" <?php echo ($medico['disponible'] == 1) ? 'selected' : ''; ?>>Disponible</option>
                                <option value="0" <?php echo ($medico['disponible'] == 0) ? 'selected' : ''; ?>>No Disponible</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Guardar Cambios</button>

                        <p class="mt-3 text-muted"><small>*Para cambiar el nombre, correo o contraseña, edite la cuenta de usuario asociada.</small></p>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
// Footer bonito
require_once __DIR__ . '/footer.php';
?>
