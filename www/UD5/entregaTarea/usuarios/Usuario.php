<?php

class Usuario {
    // Valor de $id es autoincremental en BD, por lo que iniciamos a null. Para poder hacerlo, en PHP 
    // hay que poner ?int, si no daría error al asignar null a una variable tipo int
    private ?int $id = null;
    private String $username;
    private String $nombre;
    private String $apellidos;
    private String $rol;
    private String $contrasena;
    
    public function __construct(String $username, String $nombre, String $apellidos, String $rol, String $contrasena){
        $this->username = $username;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->rol = $rol;
        $this->contrasena = $contrasena;
    }

    // Le ponemos ?int porque podría devolver null al estar inicializado así
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
    

    public function getUsername(): String {
        return $this->username;
    }

    public function setUsername(String $username): void {
        $this->username = $username;
    }
    
    public function getNombre(): String {
        return $this->nombre;
    }

    public function setNombre(String $nombre): void {
        $this->nombre = $nombre;
    }

    public function getApellidos(): String {
        return $this->apellidos;
    }

    public function setApellidos(String $apellidos): void {
        $this->apellidos = $apellidos;
    }

    public function getRol(): String {
        return $this->rol;
    }

    public function setRol(String $rol): void {
        $this->rol = $rol;
    }

    public function getContrasena(): String {
        return $this->contrasena;
    }

    public function setContrasena(String $contrasena): void {
        $this->contrasena = $contrasena;
    }

    // Comprueba que los atributos cumplen con las características indicadas
    public function validar(): array {
        $errores = [];

        if (empty($this->username) || !is_string($this->username) || strlen($this->username) < 3) {
            $errores['username'] = 'El username es obligatorio, debe ser una cadena de texto y tener al menos 3 caracteres.';
        }

        // Si usamos is_string para nombre y apellidos permite introducir sólo números, ya que datos del formulario llegan como cadena
        if (empty($this->nombre) || !is_string($this->nombre) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $this->nombre) || strlen($this->nombre) < 2) {
            $errores['nombre'] = 'El nombre es obligatorio, debe ser una cadena de texto, contener letras (espacios opcionales) y tener al menos 2 caracteres.';
        }

        if (empty($this->apellidos) || !is_string($this->apellidos) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $this->apellidos) || strlen($this->apellidos) < 2) {
            $errores['apellidos'] = 'Los apellidos son obligatorios, deben ser una cadena de texto, contener letras (espacios opcionales) y tener al menos 2 caracteres.';
        }
        if (empty($this->rol) || !is_string($this->rol)) {
            $errores['rol'] = 'El rol es obligatorio y debe ser una cadena de texto.';
        }

        /* Si tuviéramos que filtrar un entero podemos usar filter_var. Con constante predefinida FILTER_VALIDATE_INT
        validamos que sea un número entero. Si lo valida devuelve el número, si no devuelve FALSE

        if (empty($this->edad) || !filter_var($this->edad, FILTER_VALIDATE_INT) || $this->edad < 0) {
            $errores['edad'] = 'La edad es obligatoria y debe ser un número entero positivo.';
        } */

        if (empty($this->contrasena) || !is_string($this->contrasena) || strlen($this->contrasena) < 3) {
            $errores['contrasena'] = 'La contraseña es obligatoria, debe ser una cadena de texto y tener al menos 3 caracteres.';
        }

        return $errores;
    }
}

?>