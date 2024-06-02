<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}
if ($_SESSION['usuario'] !== 'administrador') {
    header("Location: inicio_users.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["ver_users"])) {
        header("Location: Ver_Usuarios.php");
        exit();
    }

    if (isset($_POST["admin_users"])) {
        header("Location: viewAdmin.php");
        exit();
    }

    if (isset($_POST["logout"])) {
        session_unset();
        session_destroy();
        header("Location: inicio.php");
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
    <link rel="stylesheet" type="text/css" href="Inicio.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap');
    </style>

</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <a href="inicio_admins.php">
                    <img src="img_py/LUXFLEX.PNG" alt="LUXO FLEX" />
                </a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="https://github.com/AGGDEBNAM">CONTACTO</a></li>
                    <li><a href="https://github.com/AGGDEBNAM">ABOUT</a></li>
                </ul>
            </div>
            <div class="cta-buttons">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <button type="submit" name="ver_users" class="button">Ver Users</button>
                    <button type="submit" name="admin_users" class="button">admin users</button>
                    <button type="submit" name="logout" class="button">logout</button>
                </form>
            </div>
        </nav>
    </header>

    <main>
        <ul class='slider'>
            <?php
            $images = [
                // 'image1.jpg' => ["LUXOFLEX IMPRESIONES", "Con más de 15 años de experiencia, Luxoflex Impresiones se distingue en la elaboración de productos con un compromiso inquebrantable hacia la calidad y la excelencia."],
                'img_py/image1.jpg' => ["NUESTRA ESPECIALIDAD", "Nos destacamos en la fabricación, diseño y comercialización de etiquetas autoadheribles, ofreciendo soluciones innovadoras que impulsan la identidad de tu marca."],
                'img_py/image2.jpg' => ["NUESTRA LABOR", "Brindamos servicios integrales que incluyen pre-prensa y diseño. Contamos con tecnología Aqua Flex de hasta siete colores y laminado, garantizando resultados de la más alta calidad."],
                'img_py/image3.jpg' => ["ATENCIÓN A MAYORISTAS", "Especializados en satisfacer las necesidades de pequeñas y medianas empresas, ofrecemos soluciones personalizadas y precios competitivos para nuestros clientes mayoristas."],
                'img_py/image4.jpg' => ["INTERNACIONALIZACIÓN", "Con una red de traders y emprendedores en todo el mundo, facilitamos la internacionalización de tu negocio. Conectamos oportunidades y fomentamos el desarrollo global de empresas."],
                'img_py/image5.jpg' => ["SERVICIOS PROFESIONALES", "Comprometidos con la excelencia, ofrecemos servicios profesionales de impresión de etiquetas diseñados para adaptarse a cualquier uso y disposición. Confía en nosotros para realzar la presentación de tus productos."],
                'img_py/image6.jpg' => ["DIVERSIDAD Y VERSATILIDAD", "En Luxoflex Impresiones, contamos con una amplia gama de materiales y aplicaciones para tus etiquetas. Desde opciones clásicas hasta innovaciones de vanguardia, tenemos la solución perfecta para tu producto."],
            ];

            foreach ($images as $image => $info) {
                echo '<li class="item" style="background-image: url(\'' . $image . '\')">';
                echo '<div class="content">';
                echo '<h2 class="title">' . $info[0] . '</h2>';
                echo '<p class="description">' . $info[1] . '</p>';
                echo '<button>Read More</button>';
                echo '</div></li>';
            }
            ?>
        </ul>
        <nav class='nav'>
            <ion-icon class='btn prev' name="arrow-back-outline"></ion-icon>
            <ion-icon class='btn next' name="arrow-forward-outline"></ion-icon>
        </nav>
    </main>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="inicio.js"></script>

</body>

</html>