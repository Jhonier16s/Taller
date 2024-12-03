<?php
// Conectar a la base de datos
require('api/config/db_connection.php');

// Consultar los usuarios
$query = "SELECT first_name, last_name, email, created_at, status FROM client";
$result = pg_query($conn, $query);

// Verifica si hay resultados
if (!$result) {
    echo "Error en la consulta: " . pg_last_error($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #444;
            margin-top: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background-color: #4CAF50;
            color: white;
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
        }
        table th {
            font-weight: bold;
        }

        /* Encabezado de la tarjeta */
        .card-header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 4px 4px 0 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de Usuarios Registrados</h2>
        <!-- Tabla -->
        <div class="card">
            <div class="card-header">Usuarios</div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Fecha de Registro</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mostrar cada usuario en una fila de la tabla
                        while ($row = pg_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
pg_close($conn);
?>
