<?php

class Fichero {
    // Valor de $id es autoincremental en BD, por lo que iniciamos a null. Para poder hacerlo, en PHP 
    // hay que poner ?int, si no daría error al asignar null a una variable tipo int
    private ?int $id = null;
    private String $nombre;
    private String $file;
    private String $descripcion;
    private int $idTarea;

    // Constantes estáticas de la clase Fichero
    public const FORMATOS = ['image/jpeg', 'image/png', 'application/pdf'];
    public const MAX_SIZE = 20 * 1024 * 1024; // 20MB en bytes
    
    public function __construct(String $nombre, String $file, String $descripcion, int $idTarea){
        $this->nombre = $nombre;
        $this->file = $file;
        $this->descripcion = $descripcion;
        $this->idTarea = $idTarea;
    }

    // Le ponemos ?int porque podría devolver null al estar inicializado así
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
    
    public function getNombre(): String {
        return $this->nombre;
    }

    public function setNombre(String $nombre): void {
        $this->nombre = $nombre;
    }

    public function getFile(): String {
        return $this->file;
    }

    public function setFile(String $file): void {
        $this->file = $file;
    }

    public function getDescripcion(): String {
        return $this->descripcion;
    }
    
    public function setDescripcion(String $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function getIdTarea(): int {
        return $this->idTarea;
    }

    public function setIdTarea(int $idTarea): void {
        $this->idTarea = $idTarea;
    }

    // Método estático que comprueba que los atributos del fichero por parámetro cumplen con las características indicadas
    // OJO: Al ser estático, para llamarlo luego tenemos que pasarle un fichero por parámetro, no como método validar de
    // clases Usuario y Tarea. En estos no hacen falta porque son métodos de la clase y llaman a la instancia usando "this"
    public static function validar($fichero): array {
        
        $errores = [];
        // Le pasamos trim() a nombre y descripción por si usuario introduce solo espacios en blanco
        // ya que si los introduce empty() no lo detecta como vacío
        $nombre = trim($fichero->getNombre());
        $file = $fichero->getFile();
        $descripcion = trim($fichero->getDescripcion());
        $idTarea = $fichero->getIdTarea();

        if (empty($nombre) || !is_string($nombre) || strlen($nombre) < 2) {
            $errores['nombre'] = 'El nombre es obligatorio, debe ser una cadena de texto y tener al menos 2 caracteres.';
        }

        if (empty($file) || !is_string($file)) {
            $errores['file'] = 'El file es obligatorio y debe ser una cadena de texto.';
        }

        if (empty($descripcion) || !is_string($descripcion)) {
            $errores['descripcion'] = 'La descripción es obligatoria y debe ser una cadena de texto.';
        }

        // Filtra un entero con filter_var. Con constante predefinida FILTER_VALIDATE_INT valida que sea un número entero. Si lo valida devuelve el número, si no devuelve FALSE
        if (empty($idTarea) || !filter_var($idTarea, FILTER_VALIDATE_INT)) {
            $errores['idTarea'] = 'El idTarea es obligatorio y debe ser un número entero positivo.';
        }

        return $errores;
    }
}

?>