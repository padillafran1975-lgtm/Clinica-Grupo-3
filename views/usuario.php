<?php
// Incluimos la conexión.
require_once __DIR__ . '/clases/conexion.php'; 

// Incluimos el header (barra superior)
require_once __DIR__ . '/header.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoger y sanitizar los datos
    $nombre       = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo       = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contrasena   = mysqli_real_escape_string($conexion, $_POST['contrasena']);
    $id_rol       = mysqli_real_escape_string($conexion, $_POST['id_rol']);
    $activo       = mysqli_real_escape_string($conexion, $_POST['activo']);

    // Hashear la contraseña antes de guardar (SEGURIDAD)
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $fecha_creacion = date("Y-m-d H:i:s");

    // Consulta SQL para agregar usuario
    $sql = "INSERT INTO Usuarios (nombre, correo, contrasena_hash, id_rol, activo, fecha_creacion) 
            VALUES ('$nombre', '$correo', '$contrasena_hash', '$id_rol', '$activo', '$fecha_creacion')";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div class='alert alert-success text-center'>Usuario agregado con éxito.</div>";
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
                    <h4 class="m-0 text-center">Crear Nuevo Usuario</h4>
                </div>

                <div class="card-body">

                    <?php echo $mensaje; ?>

                    <form method="POST" action="agregar_usuario.php">

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" id="nombre" name="nombre" class="form-control"
                                   placeholder="Ej: Juan Pérez" required>
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" id="correo" name="correo" class="form-control"
                                   placeholder="Ej: ejemplo@gmail.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" id="contrasena" name="contrasena" class="form-control"
                                   placeholder="Ingresa una contraseña segura" required>
                        </div>

                        <div class="mb-3">
                            <label for="id_rol" class="form-label">Rol del Usuario</label>
                            <select id="id_rol" name="id_rol" class="form-select" required>
                                <option value="">Seleccione un rol</option>
                                <option value="1">Administrador</option>
                                <option value="2">Médico</option>
                                <option value="3">Secretaria</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="activo" class="form-label">Estado del Usuario</label>
                            <select id="activo" name="activo" class="form-select" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Guardar Usuario
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php 
// Incluimos footer
require_once __DIR__ . '/footer.php'; 
?>


