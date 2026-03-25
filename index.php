<?php
// MySQL Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "guardianes";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current table and action
$table = $_GET['table'] ?? 'roles';
$action = $_POST['action'] ?? '';

// Handle CRUD Operations
if ($action) {
    if ($action == 'add_role') {
        $nombre = $conn->real_escape_string($_POST['nombre_rol']);
        $conn->query("INSERT INTO roles (nombre_rol) VALUES ('$nombre')");
    } elseif ($action == 'delete_role') {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM roles WHERE id_rol = $id");
    } elseif ($action == 'add_usuario') {
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $correo = $conn->real_escape_string($_POST['correo']);
        $password = password_hash($_POST['password_hash'], PASSWORD_BCRYPT);
        $id_rol = intval($_POST['id_rol']);
        $conn->query("INSERT INTO usuarios (nombre, correo, password_hash, id_rol) VALUES ('$nombre', '$correo', '$password', $id_rol)");
    } elseif ($action == 'delete_usuario') {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM usuarios WHERE id_usuario = $id");
    } elseif ($action == 'add_incidente') {
        $descripcion = $conn->real_escape_string($_POST['descripcion']);
        $fecha = $conn->real_escape_string($_POST['fecha']);
        $latitud = floatval($_POST['latitud']);
        $longitud = floatval($_POST['longitud']);
        $estado = $conn->real_escape_string($_POST['estado']);
        $id_usuario = intval($_POST['id_usuario']);
        $conn->query("INSERT INTO incidentes (descripcion, fecha, latitud, longitud, estado, id_usuario) VALUES ('$descripcion', '$fecha', $latitud, $longitud, '$estado', $id_usuario)");
    } elseif ($action == 'delete_incidente') {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM incidentes WHERE id_incidente = $id");
    } elseif ($action == 'add_evidencia') {
        $url = $conn->real_escape_string($_POST['url_archivo']);
        $id_incidente = intval($_POST['id_incidente']);
        $conn->query("INSERT INTO evidencias (url_archivo, id_incidente) VALUES ('$url', $id_incidente)");
    } elseif ($action == 'delete_evidencia') {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM evidencias WHERE id_evidencia = $id");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 10px 20px;
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .tab-btn:hover,
        .tab-btn.active {
            background: #667eea;
            color: white;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        button {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        button:hover {
            background: #764ba2;
        }
        .delete-btn {
            background: #dc3545;
            padding: 5px 10px;
            font-size: 12px;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #eee;
        }
        .form-section h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        .empty-msg {
            text-align: center;
            color: #999;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 CRUD Management Dashboard</h1>
        <p>Manage Roles, Users, Incidents, and Evidence</p>
    </div>

    <div class="container">
        <div class="tabs">
            <a href="?table=roles" class="tab-btn <?php echo $table == 'roles' ? 'active' : ''; ?>">🔑 Roles</a>
            <a href="?table=usuarios" class="tab-btn <?php echo $table == 'usuarios' ? 'active' : ''; ?>">👥 Users</a>
            <a href="?table=incidentes" class="tab-btn <?php echo $table == 'incidentes' ? 'active' : ''; ?>">🚨 Incidents</a>
            <a href="?table=evidencias" class="tab-btn <?php echo $table == 'evidencias' ? 'active' : ''; ?>">📁 Evidence</a>
        </div>

        <div class="content-section">
            <?php if ($table == 'roles'): ?>
                <!-- ROLES SECTION -->
                <div class="form-section">
                    <h3>➕ Add New Role</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Role Name</label>
                            <input type="text" name="nombre_rol" required>
                        </div>
                        <input type="hidden" name="action" value="add_role">
                        <button type="submit">Add Role</button>
                    </form>
                </div>

                <h3>📋 All Roles</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $result = $conn->query("SELECT * FROM roles");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id_rol']}</td>
                                <td>{$row['nombre_rol']}</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id_rol']}'>
                                        <input type='hidden' name='action' value='delete_role'>
                                        <button type='submit' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='empty-msg'>No roles found</td></tr>";
                    }
                    ?>
                </table>

            <?php elseif ($table == 'usuarios'): ?>
                <!-- USERS SECTION -->
                <div class="form-section">
                    <h3>➕ Add New User</h3>
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="correo" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password_hash" required>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="id_rol" required>
                                    <option value="">Select Role</option>
                                    <?php
                                    $roles = $conn->query("SELECT * FROM roles");
                                    while ($role = $roles->fetch_assoc()) {
                                        echo "<option value='{$role['id_rol']}'>{$role['nombre_rol']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="add_usuario">
                        <button type="submit">Add User</button>
                    </form>
                </div>

                <h3>📋 All Users</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $result = $conn->query("SELECT u.*, r.nombre_rol FROM usuarios u LEFT JOIN roles r ON u.id_rol = r.id_rol");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id_usuario']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['correo']}</td>
                                <td>{$row['nombre_rol']}</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id_usuario']}'>
                                        <input type='hidden' name='action' value='delete_usuario'>
                                        <button type='submit' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='empty-msg'>No users found</td></tr>";
                    }
                    ?>
                </table>

            <?php elseif ($table == 'incidentes'): ?>
                <!-- INCIDENTS SECTION -->
                <div class="form-section">
                    <h3>➕ Add New Incident</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="descripcion" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Date/Time</label>
                                <input type="datetime-local" name="fecha" required>
                            </div>
                            <div class="form-group">
                                <label>User</label>
                                <select name="id_usuario" required>
                                    <option value="">Select User</option>
                                    <?php
                                    $users = $conn->query("SELECT * FROM usuarios");
                                    while ($user = $users->fetch_assoc()) {
                                        echo "<option value='{$user['id_usuario']}'>{$user['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="number" name="latitud" step="0.00000001" required>
                            </div>
                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="number" name="longitud" step="0.00000001" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="estado" required>
                                <option value="Abierto">Open</option>
                                <option value="En Progreso">In Progress</option>
                                <option value="Cerrado">Closed</option>
                            </select>
                        </div>
                        <input type="hidden" name="action" value="add_incidente">
                        <button type="submit">Add Incident</button>
                    </form>
                </div>

                <h3>📋 All Incidents</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $result = $conn->query("SELECT i.*, u.nombre FROM incidentes i LEFT JOIN usuarios u ON i.id_usuario = u.id_usuario");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $desc = strlen($row['descripcion']) > 50 ? substr($row['descripcion'], 0, 50) . '...' : $row['descripcion'];
                            echo "<tr>
                                <td>{$row['id_incidente']}</td>
                                <td>{$desc}</td>
                                <td>{$row['fecha']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['estado']}</td>
                                <td>{$row['latitud']}, {$row['longitud']}</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id_incidente']}'>
                                        <input type='hidden' name='action' value='delete_incidente'>
                                        <button type='submit' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='empty-msg'>No incidents found</td></tr>";
                    }
                    ?>
                </table>

            <?php elseif ($table == 'evidencias'): ?>
                <!-- EVIDENCE SECTION -->
                <div class="form-section">
                    <h3>➕ Add New Evidence</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>File URL</label>
                            <input type="url" name="url_archivo" placeholder="https://example.com/file.pdf" required>
                        </div>
                        <div class="form-group">
                            <label>Incident</label>
                            <select name="id_incidente" required>
                                <option value="">Select Incident</option>
                                <?php
                                $incidents = $conn->query("SELECT * FROM incidentes");
                                while ($incident = $incidents->fetch_assoc()) {
                                    echo "<option value='{$incident['id_incidente']}'>#{$incident['id_incidente']} - {$incident['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="action" value="add_evidencia">
                        <button type="submit">Add Evidence</button>
                    </form>
                </div>

                <h3>📋 All Evidence</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>File URL</th>
                        <th>Incident ID</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $result = $conn->query("SELECT * FROM evidencias");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $url = strlen($row['url_archivo']) > 50 ? substr($row['url_archivo'], 0, 50) . '...' : $row['url_archivo'];
                            echo "<tr>
                                <td>{$row['id_evidencia']}</td>
                                <td><a href='{$row['url_archivo']}' target='_blank'>{$url}</a></td>
                                <td>{$row['id_incidente']}</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id_evidencia']}'>
                                        <input type='hidden' name='action' value='delete_evidencia'>
                                        <button type='submit' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='empty-msg'>No evidence found</td></tr>";
                    }
                    ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
