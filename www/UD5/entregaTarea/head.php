<!DOCTYPE html>
<!-- Dinámicamente se imprime el valor de la cookie (si existe). Si no coge light por defecto -->
<html lang="es" data-bs-theme="<?php echo isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'light';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD5. Tarea DWCS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>