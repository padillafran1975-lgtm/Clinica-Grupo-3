<?php
// -------------------------------
// 1. CONEXIÓN A LA BD
// -------------------------------
require_once __DIR__ . '/clases/conexion.php'; 

// Consulta SQL tal como tú la tienes
$sql = "SELECT 
            M.id_medico, 
            M.especialidad, 
            M.disponible,
            U.nombre, 
            U.correo,
            U.id_rol  
        FROM 
            Medicos M
        INNER JOIN 
            Usuarios U ON M.id_usuario = U.id_usuario
        ORDER BY 
            U.nombre ASC";

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error al consultar la base de datos: " . mysqli_error($conexion));
}

// Contar filas
$num_medicos = mysqli_num_rows($resultado);

// *** NO CERRAMOS la BD aún porque la tabla se sigue usando ***

// ----------------------------------------
// 2. INCLUIR CABECERA CON BOOTSTRAP
// ----------------------------------------
include 'header.php';
?>

<!-- Título -->
<h2 class="fw-bold text-primary mb-4">
    Listado de Médicos Registrados (<?= $num_medicos ?>)
</h2>

<!-- Botón agregar + buscador -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <!-- Botón azul para agregar -->
    <a href="agregar_medico.php" class="btn btn-primary">
         Agregar Nuevo Médico
    </a>

    <!-- Buscador visual (no funcional, solo estético por ahora) -->
    <form class="d-flex" method="get">
        <input type="search" name="q" class="form-control me-2" placeholder="Buscar por nombre...">
        <button class="btn btn-outline-secondary">Buscar</button>
    </form>

</div>


<!-- TARJETA CON TABLA -->
<div class="card shadow-sm">
    <div class="card-body p-0">

        <!-- Tabla con estilos -->
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center mb-0">
                
                <thead class="table-primary">
                    <tr>
                        <th>ID Médico</th>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Correo</th>
                        <th>Rol ID</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($num_medicos > 0): ?>

                        <?php while ($medico = mysqli_fetch_assoc($resultado)): ?>

                            <?php 
                                // Iconos verde/rojo para disponibilidad
                                $disp = ($medico['disponible'] == 1)
                                        ? '<span class="text-success fw-bold">✔ Disponible</span>'
                                        : '<span class="text-danger fw-bold">✖ No disponible</span>';
                            ?>

                            <tr>
                                <td><?= htmlspecialchars($medico['id_medico']); ?></td>
                                <td class="text-start ps-4"><?= htmlspecialchars($medico['nombre']); ?></td>
                                <td><?= htmlspecialchars($medico['especialidad']); ?></td>
                                <td><?= htmlspecialchars($medico['correo']); ?></td>
                                <td><?= htmlspecialchars($medico['id_rol']); ?></td>
                                <td><?= $disp ?></td>

                                <td>
                                    <!-- Botón editar -->
                                    <a href="editar_medico.php?id=<?= $medico['id_medico']; ?>" 
                                       class="btn btn-warning btn-sm">
                                        ✏️ Editar
                                    </a>

                                    <!-- Botón eliminar -->
                                    <a href="eliminar_medico.php?id=<?= $medico['id_medico']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('¿Seguro que deseas eliminar al médico <?= htmlspecialchars($medico['nombre']); ?>?');">
                                        🗑️ Eliminar
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="py-3">
                                No hay perfiles de médicos registrados.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>
        </div>
    </div>
</div>

<?php
// Ahora sí cerramos conexión
mysqli_close($conexion);

// Pie de página
include 'footer.php';
?>
