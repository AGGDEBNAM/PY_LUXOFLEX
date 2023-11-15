<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link rel="stylesheet" href="prueba_1.css">
</head>

<body>

  <div class="login-box">
    <h2>Login</h2>

    <?php
    $servername = "localhost";
    $database = "luxoflex";

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Recuperar credenciales del formulario
      $usuario = $_POST['username'];
      $contrasena = $_POST['password'];

      // Intentar crear conexión
      try {
        $conn = new mysqli($servername, $usuario, $contrasena, $database);

        // Verificar la conexión
        if ($conn->connect_error) {
          die("Error de conexión: " . $conn->connect_error);
        }

        // Si no hubo problemas hasta aquí, el inicio de sesión es exitoso
        echo "<p class='success'>Inicio de sesión exitoso para $usuario</p>";

        // Cerrar conexión
        $conn->close();
      } catch (mysqli_sql_exception $e) {
        // Manejar excepción en caso de error de conexión
        // Credenciales inválidas
        echo "<p class='message'>Inicio de sesión fallido.<br>Usuario o contraseña incorrectos.</p>";
      }
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="user-box">
        <input type="text" name="username" required>
        <label>Username</label>
      </div>
      <div class="user-box">
        <input type="password" name="password" required>
        <label>Password</label>
      </div>
      <button type="submit" class="btn">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        Submit
      </button>
    </form>
  </div>

</body>

</html>