<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
</head>
<body>
    <h1>Lista de usuarios</h1>
    <?php if (empty($usuarios)): ?>
        <p>No hay usuarios registrados.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <tr><th>ID</th><th>Nombre</th><th>Email</th></tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>