<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se hizo clic en el botón de inicio de sesión
    if (isset($_POST["login"])) {
        header("Location: login.php"); // Cambia "archivo_login.php" al nombre de tu archivo de inicio de sesión
        exit();
    }

    // Verificar si se hizo clic en el botón de registro
    if (isset($_POST["signup"])) {
        header("Location: registro.php"); // Cambia "archivo_signup.php" al nombre de tu archivo de registro
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Mi Página Web</title>
    <link rel="stylesheet" type="text/css" href="Inicio.css" />
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <a href="/">
                    <img src="" alt="Luxo Flex" />
                </a>
                <div class="menu-toggle">
                    <input type="checkbox" />
                    <span></span><span></span><span></span>
                </div>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="/pricing">Pricing</a></li>
                    <li><a href="/about">About</a></li>
                </ul>
            </div>
            <div class="cta-buttons">
                <!-- Agregando un formulario para manejar la acción del clic -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <button type="submit" name="login" class="button">Log in</button>
                    <button type="submit" name="signup" class="button">Sign up</button>
                </form>
            </div>
        </nav>
    </header>
</body>
</html>