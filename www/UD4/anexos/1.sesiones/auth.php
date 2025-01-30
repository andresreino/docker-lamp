<?php
session_start();

function comporbar_usuario($nombre, $pass)
{
    if($nombre == "usuario" && $pass="abc123.")
    {
        $usuario['nombre']="usuario";
        $usuario['rol']=0;
        return $usuario;
    }
    elseif($nombre == "admin" && $pass="1234")
    {
        $usuario['nombre']="admin";
        $usuario['rol']=1;
        return $usuario;
    }
    else
    {
        return false;
    }
}

//Comprobar si se reciben los datos
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $usuario = $_POST["usuario"];
    $pass = $_POST["pass"];
    $user = comporbar_usuario($usuario, $pass );
    if(!$user)
    {
        header('Location: login.php?error=true');
    }
    else
    {
        $_SESSION['usuario'] = $user;
        //Redirigimos a index.php
        header('Location: index.php');
    }
}