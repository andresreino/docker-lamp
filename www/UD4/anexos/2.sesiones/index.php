<?php 
    session_start();
    if (!isset($_SESSION['usuario'])) {	
        header("Location: login.php?redirect=true");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="welcome-container">
        <h2>Bienvenido</h2>
        <p class="welcome-message">
            <?php echo "Hola, " . htmlspecialchars($_SESSION['usuario']['nombre']); ?>
        </p>
        <a href="logout.php" class="logout-button">Salir</a>
    </div>
</body>
</html>
