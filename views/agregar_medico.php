<?php
// Incluimos la conexión.
require_once __DIR__ . '/clases/conexion.php'; 

// Incluir header (barra superior)
require_once __DIR__ . '/header.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_usuario    = mysqli_real_escape_string($conexion, $_POST['id_usuario']);
    $especialidad  = mysqli_real_escape_string($conexion, $_POST['especialidad']);
    $disponible    = mysqli_real_escape_string($conexion, $_POST['disponible']); 

    $sql = "INSERT INTO Medicos (id_usuario, especialidad, disponible) 
            VALUES ('$id_usuario', '$especialidad', '$disponible')";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div class='alert alert-success text-center'>Perfil de Médico agregado con éxito.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conexion) . "</div>";
    }
}

// Cerramos la conexión
mysqli_close($conexion);
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0 text-center">Crear Perfil de Médico</h4>
                </div>

                <div class="card-body">

                    <?php echo $mensaje; ?>

                    <form method="POST" action="agregar_medico.php">
                        
                        <div class="mb-3">
                            <label for="id_usuario" class="form-label">ID de Usuario (Cuenta de Acceso)</label>
                            <input type="number" id="id_usuario" name="id_usuario" class="form-control"
                                   placeholder="Ej: 15" required>
                        </div>

                        <div class="mb-3">
                            <label for="especialidad" class="form-label">Especialidad</label>
                            <input type="text" id="especialidad" name="especialidad" class="form-control"
                                   placeholder="Ej: Cardiología" required>
                        </div>

                        <div class="mb-3">
                            <label for="disponible" class="form-label">Disponibilidad</label>
                            <select id="disponible" name="disponible" class="form-select" required>
                                <option value="1">Disponible</option>
                                <option value="0">No Disponible</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Guardar Médico</button>
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
