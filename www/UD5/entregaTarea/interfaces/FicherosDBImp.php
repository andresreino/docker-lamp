<?php
include_once('FicherosDBInt.php');


class FicherosDBImp implements FicherosDBInt {

    // Guarda los datos de un fichero en la BD introduciendo objeto Fichero por parámetro (PDO)
    public function nuevoFichero($fichero): bool{
        try {
            $conexion = conectarDBPDO('tareas');
            //Consulta preparada
            $sql = "INSERT INTO ficheros (nombre, file, descripcion, id_tarea) VALUES (:nombre, :file, :descripcion, :id_tarea)";
            $stmt = $conexion->prepare($sql);

            $nombre = $fichero->getNombre();
            $file = $fichero->getFile();
            $descripcion = $fichero->getDescripcion();
            $idTarea = $fichero->getIdTarea();


            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':file', $file, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':id_tarea', $idTarea, PDO::PARAM_INT);
                    
            $stmt->execute();
            // Devuelve la ejecución y mensaje si todo es correcto
            //return [true, "Fichero guardado correctamente en la BD."];
            return true;
        } catch(Exception $e) {
            if($e instanceof DatabaseException){
                throw new DatabaseException("Error al guardar el fichero: " . $e->getMessage() . " Ha fallado el método " . $e->getMethod());
            } else {
                throw new Exception ("Error al guardar el fichero: " . $e->getMessage());
            }
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }
    }

    // Selecciona todos los ficheros que tiene una tarea y devuelve array con objetos Fichero
    function listaFicheros($idTarea): array {
        try {
            $conexion = conectarDBPDO('tareas');
            $sql = "SELECT * FROM ficheros WHERE id_tarea =" . $idTarea;

            $stmt = $conexion->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC); 
            $stmt->execute();
            
            $resultado = [];
            
            while($fila = $stmt->fetch()){
                $fichero = new Fichero('', '', '', 0);
                $fichero->setId($fila['id']);
                $fichero->setNombre($fila['nombre']);
                $fichero->setFile($fila['file']);
                $fichero->setDescripcion($fila['descripcion']);
                $fichero->setIdTarea($fila['id_tarea']);

                $resultado[] = $fichero;
            }

            return $resultado;

        } catch (Exception $e) {
            if($e instanceof DatabaseException){
                throw new DatabaseException("Error listando los ficheros: " . $e->getMessage() . " Ha fallado el método " . $e->getMethod());
            } else {
                throw new Exception ("Error listando los ficheros: " . $e->getMessage());
            }       
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }
    }

    // Selecciona un fichero de la BD
    function buscaFichero($idFichero): Fichero {
        try {
            $conexion = conectarDBPDO('tareas');
            $sql = "SELECT * FROM ficheros WHERE id =" . $idFichero;

            $stmt = $conexion->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC); 
            $stmt->execute();

            // Sólo necesitamos una fila, usamos fetch() no fetchAll()
            $fila = $stmt->fetch();
            // Creamos usuario indicando al constructor que inicialice con cadena vacía cada atributo o cero
            $fichero = new Fichero('', '', '', 0);
            $fichero->setId($fila['id']);
            $fichero->setNombre($fila['nombre']);
            $fichero->setFile($fila['file']);
            $fichero->setDescripcion($fila['descripcion']);
            $fichero->setIdTarea($fila['id_tarea']);

            return $fichero;

        } catch (Exception $e) {
            if($e instanceof DatabaseException){
                throw new DatabaseException("Error al buscar el fichero: " . $e->getMessage() . " Ha fallado el método " . $e->getMethod());
            } else {
                throw new Exception ("Error al buscar el fichero: " . $e->getMessage());
            }
        } finally {
            $conexion = null;
        }
    }

    // Elimina un fichero de la BD por su id
    function borraFichero($idFichero): bool {
        try {
            $conexion = conectarDBPDO('tareas');
            $sql = "DELETE FROM ficheros WHERE id =" . $idFichero;
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            //return [true, "Fichero eliminado correctamente."];
            return true;
        } catch (Exception $e) {
            if($e instanceof DatabaseException){
                throw new DatabaseException("Error al borrar el fichero: " . $e->getMessage() . " Ha fallado el método " . $e->getMethod());
            } else {
                throw new Exception ("Error al borrar el fichero: " . $e->getMessage());
            }
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }
    }
}

?>