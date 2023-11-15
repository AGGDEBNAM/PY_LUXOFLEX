<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["login"])) {
        header("Location: login.php");
        exit();
    }

    if (isset($_POST["signup"])) {
        header("Location: sign_up.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUXO FLEX</title>
    <link rel="stylesheet" type="text/css" href="inicio.css" />
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <a href="inicio.php">
                    <img src="LUXFLEX.PNG" alt="LUXO FLEX" />
                </a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="/pricing">Pricing</a></li>
                    <li><a href="/about">About</a></li>
                </ul>
            </div>
            <div class="cta-buttons">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <button type="submit" name="login" class="button">Log in</button>
                    <button type="submit" name="signup" class="button">Sign up</button>
                </form>
            </div>
        </nav>
    </header>
</body>

</html>