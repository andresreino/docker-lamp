<?php

class Tarea {
    // Valor de $id es autoincremental en BD, por lo que iniciamos a null. Para poder hacerlo, en PHP 
    // hay que poner ?int, si no daría error al asignar null a una variable tipo int
    private ?int $id = null;
    private String $titulo;
    private String $descripcion;
    private String $estado;
    private int $idUsuario;
    
    public function __construct(String $titulo, String $descripcion, String $estado, int $idUsuario){
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->idUsuario = $idUsuario;
    }

    // Le ponemos ?int porque podría devolver null al estar inicializado así
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getTitulo(): String {
        return $this->titulo;
    }

    public function setTitulo(String $titulo): void {
        $this->titulo = $titulo;
    }
    
    public function getDescripcion(): String {
        return $this->descripcion;
    }

    public function setDescripcion(String $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function getEstado(): String {
        return $this->estado;
    }

    public function setEstado(String $estado): void {
        $this->estado = $estado;
    }

    public function getIdUsuario(): int {
        return $this->idUsuario;
    }

    public function setIdUsuario(int $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }
    
    // Comprueba que los atributos cumplen con las características indicadas
    public function validar(): array {
        $errores = [];

        if (empty($this->titulo) || !is_string($this->titulo)) {
            $errores['titulo'] = 'El título es obligatorio y debe ser una cadena de texto.';
        }

        if (empty($this->descripcion) || !is_string($this->descripcion)) {
            $errores['descripcion'] = 'La descripcion es obligatoria y debe ser una cadena de texto.';
        }

        if (empty($this->estado) || !is_string($this->estado) || $this->estado != "usuario" || $this->estado != "administrador") {
            $errores['estado'] = 'El estado es obligatorio y únicamente puede ser "usuario" o "administrador".';
        }

        // Filtra un entero con filter_var. Con constante predefinida FILTER_VALIDATE_INT valida que sea un número entero. Si lo valida devuelve el número, si no devuelve FALSE
        if (empty($this->idUsuario) || !filter_var($this->idUsuario, FILTER_VALIDATE_INT)) {
            $errores['idUsuario'] = 'El idUsuario es obligatorio y debe ser un número entero positivo.';
        }

        return $errores;
    }

}

?>