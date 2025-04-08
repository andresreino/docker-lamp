<?php 

// Filtra el campo que recibe por parámetros y lo devuelve filtrado
function filtrarCampo($campo){
    $campo = trim($campo);
    $campo = stripslashes($campo);
    $campo = htmlspecialchars($campo);
    return $campo;
}

// Comprueba que nombre cumple los requisitos establecidos
function nombreEsValido($datos){
    $nombre = filtrarCampo($datos);
    if (empty($nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]*$/', $nombre)) {
        return false;
    }else{
        return true;
    }
}

// Comprueba que email cumple los requisitos establecidos
function emailEsValido($datos){
    $email = filtrarCampo($datos);
    if (empty($email) || !preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        return false;
    }else{
        return true;
    }
}

// Comprueba que email cumple los requisitos establecidos
function passwordEsValido($datos){
    $password = filtrarCampo($datos);
    // Valida contraseña entre 6 y 20 caracteres (cualquier caracter, excepto salto de línea)
    if (empty($password) || !preg_match('/^.{6,20}$/', $password)) {
        return false;
    }else{
        return true;
    }
}

// Comprueba que teléfono cumple los requisitos establecidos
function telefonoEsValido($datos){
    $telefono = filtrarCampo($datos);
    // Valida teléfono con 9 dígitos obligatorios. Puede llevar prefijo internacional (+1, +245...)
    if (empty($telefono) || !preg_match('/^(\+[0-9]{1,3})?( )?[0-9]{9}$/', $telefono)) {
        return false;
    }else{
        return true;
    }
}

// Selecciona el usuario de la BD por el token pasado por parámetro
function seleccionarUsuarioPorToken($token){
    $sql = "SELECT * FROM usuarios WHERE token = :token";
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $usuario = $stmt->fetch();

    return $usuario;
}

// Selecciona el usuario de la BD por el email pasado por parámetro
function seleccionarUsuarioPorEmail($email){
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    // Esta variable guarda array con datos de usuario si lo encuentra o false si no está en BD
    $usuario = $stmt->fetch();

    return $usuario;
}

// Verifica que un contacto existe en la BD
function verificarContacto($idContacto){
    $sql = "SELECT * FROM contactos WHERE id = :id";
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(':id', $idContacto);
    $stmt->execute();
    $contacto = $stmt->fetch();

    return $contacto;
}

?>