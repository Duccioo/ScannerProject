<?php
// Initialize the session
session_start();
require "Setup/config.php";


    $name = $_POST['nome'];
    $tipo = $_POST['tipo'];
    if (!empty($name)) {
        
        $data=Search($name,$tipo,$link);
        echo $data;
    }
    
?>


