<?php
	session_start();
    // Rellenamos el array superglobal con un array vacío para borrarlo
	$_SESSION = array();
	session_destroy();	
	header("Location: ../index.php");
?>