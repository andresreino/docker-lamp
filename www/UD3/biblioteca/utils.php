<?php
function mostrarResultado($data){
    if($data[0]){
        echo '<div class="alert alert-success" role="alert">' . $data[1] . '</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">' . $data[1] . '</div>';
    }
}



?>