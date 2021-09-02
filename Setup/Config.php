<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', '192.168.1.177:8889');
define('DB_USERNAME', 'duccio');
define('DB_PASSWORD', 'duccio');
define('DB_NAME', 'BarCodeScanner');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect("dosio.ddns.net:8889", DB_USERNAME, DB_PASSWORD, DB_NAME);


// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

function CercaTorF($link, $queri) {
    if ($result=mysqli_query($link,$queri)) {
  
      if (mysqli_num_rows($result)==0 ) {
        //se il numero di colonne è uguale a 0 allora non è presente nel database 
        return false;
      }
      else {
        //altrimenti è presente
        return true;
      }
    }
    else return false;
}

function Search($string, $tipo, $link){
    //$string è il nome o il codice che voglio cercare
    if ($tipo=="nome") {
        //cerco nel database tramite nome
        $query="SELECT * FROM Prodotto Left JOIN ProdottiConosciuti ON (Prodotto.id=ProdottiConosciuti.id_Prodotto ) WHERE Prodotto.nome LIKE'%$string%'";
        
    }elseif ($tipo=="codice") {
        //cerco nel database tramite codice
        $query="SELECT * FROM Prodotto Left JOIN ProdottiConosciuti ON (Prodotto.id=ProdottiConosciuti.id_Prodotto ) WHERE ProdottiConosciuti.codice_a_barre LIKE'$string%'";
    }elseif ($tipo=="misto") {
        $query="SELECT * 
        FROM Prodotto Left JOIN ProdottiConosciuti ON (Prodotto.id=ProdottiConosciuti.id_Prodotto ) 
        WHERE ProdottiConosciuti.codice_a_barre LIKE'$string%' 
        OR Prodotto.nome LIKE'%$string%'";
    }elseif ($tipo=="todolist") {
        if ($string==" ") {
            $query="SELECT * FROM Prodotto";
        }
        else $query="SELECT * FROM Prodotto WHERE Prodotto.nome LIKE'%$string%'";
         
    }
    elseif ($tipo="conteiner") {
        # code...
        if ($string==" ") {
            $query="SELECT * FROM Prodotto,ProdottiConosciuti 
                    WHERE ProdottiConosciuti.id_Prodotto=Prodotto.id";
        }
        else $query="SELECT * FROM Prodotto,ProdottiConosciuti 
                    WHERE ProdottiConosciuti.id_Prodotto=Prodotto.id
                    AND (ProdottiConosciuti.codice_a_barre LIKE'$string%'
                    OR Prodotto.nome LIKE'%$string%')";
    }


    else {
        //errore
    }
    
    if (!$result= mysqli_query($link, $query)) {
       return "ERRORE QUERY";
    }
    $emparray = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $emparray[] = $row;
    }

    return json_encode($emparray);
   
}
  
?>