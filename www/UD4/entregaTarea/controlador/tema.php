<?php
// Almacenamos la ruta de la página desde la que se pidió el cambio de tema
$referer = $_SERVER['HTTP_REFERER'];

$cookieName = 'tema';
//$tema = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : "light";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $tema = isset($_POST["tema"]) ? $_POST["tema"] : 'light';
    setcookie($cookieName, $tema, time() + (86400 * 30), "/");
}

// Redirigimos a la página desde donse se solicitó el cambio
header("Location: " . $referer);
?>