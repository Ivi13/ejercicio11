<?php
// app/Vistas/alumnos/listar.php
?>

<?php if (!empty($error)): ?>
  <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="tarjeta">
  <h2>Listado de alumnos</h2>

  <?php if (empty($alumnos)): ?>
    <p>No hay alumnos todavía.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Edad</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($alumnos as $a): ?>
          <tr>
            <td><?php echo htmlspecialchars($a->fechaCreacion); ?></td>
            <td><?php echo htmlspecialchars($a->nombre); ?></td>
            <td><?php echo htmlspecialchars($a->email ?? ''); ?></td>
            <td><?php echo htmlspecialchars($a->edad); ?></td>
            <td>
              <a href="index.php?accion=borrar&id=<?php echo urlencode($a->id); ?>"
                 onclick="return confirm('¿Seguro que quieres borrar este alumno?');">
                🗑 Borrar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>