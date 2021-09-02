<?php
// Initialize the session
session_start();
require "../Setup/config.php";

//--------------VARIABILI----------- //  
//mi salvo l'id utente e il nome 
$id=$_SESSION["id"];
$username=$_SESSION["username"]; 

//quando entro nella pagina dalla Home
$lista=$_GET["id"];

//quando cancello un elemento
$cancella_elemento=$_GET["cancella"];

//quando cancello la lista
$cancella_lista=$_POST["cancella_lista"];

//quando modifico le info della lista
$newname=$_POST["NewName"];
$newdesc=$_POST["NewDesc"];

$add=$_GET['add']; //$add è il nome del prodotto
//nel caso aggiungo un elemento mi vado a prendere il nome della lista dalla sessione

$quantita=$_GET['quantita'];
$id_elemento=$_GET['id_elemento'];


if (!empty($add)) {
  $lista=$_SESSION["conteiner"]["id"];
}else{
  //mi salvo il nome della lista nella sessione
  $_SESSION["conteiner"]["id"]=$lista;
}


//--------------Bottoni Reattivi----------- //  
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../Login/login.php");
    exit;
}
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  $home="home.php";
  $log= "Logout";
}
else {
$home="../index.php";
$log= "Login";
}

//----------------------Cancello l'elemento dalla lista-------------------//
 //cancello l'elemento
 if (!empty($cancella_elemento)) {
    $query = "DELETE FROM Contiene WHERE id_ProdottoConosciuto ='$cancella_elemento' ";
    $result=mysqli_query($link, $query);
    header("location: Conteiner.php?id=".$lista);
}


//----------------------Mostro i prodotti nella lista-------------------//
//controllo se la lista esiste

//prendo le informazioni della lista
$query="SELECT * FROM ListaImmagazzinamento, Registrato 
         WHERE ListaImmagazzinamento.id_UtenteRegistrato= Registrato.id_Utente
         AND ListaImmagazzinameNto.id='$lista' AND Registrato.username='$username'";
if (!$result= mysqli_query($link, $query)) {
    //errore la lista non è presente nel database;
    $err=" Lista NON Trovata";
    

 }
 else{
    //salvo le informazioni della lista
    $row = mysqli_fetch_assoc($result);
    $descrizione=$row["descrizione"];
    $lista_nome=$row['nome'];
    //preparo la query 
    //vado a selezionare tutti i prodotti dentro la lista 
    $query="SELECT Prodotto.nome, Prodotto.info, Prodotto.id, Contiene.quantità, ProdottiConosciuti.codice_a_barre
            FROM Prodotto,ListaImmagazzinamento, Contiene, Registrato, ProdottiConosciuti
            WHere ListaImmagazzinamento.id='$lista' AND Registrato.username='$username'
            AND ListaImmagazzinamento.id_UtenteRegistrato= Registrato.id_Utente
            AND Contiene.id_ProdottoConosciuto=ProdottiConosciuti.id_Prodotto 
            AND ProdottiConosciuti.id_Prodotto=Prodotto.id
            AND Contiene.id_ListaImmagazzinamento=ListaImmagazzinamento.id";
    if (!$result= mysqli_query($link, $query)) {
        //errore la lista non è presente nel database;
        $err=" ERRORE LISTA";
     }
     while ($row = mysqli_fetch_assoc($result)) {
        $emparray[] = $row;
    }
 }

//----------------------MODIFICO LA LISTA-------------------//

//Mostro tutti i prodotti Conosciuti non ancora aggiunti al conteiner con id=$id
$query="SELECT Prodotto.id,Prodotto.nome,Prodotto.info
        FROM Prodotto, ProdottiConosciuti
        WHERE Prodotto.id=ProdottiConosciuti.id_Prodotto
        
        AND Prodotto.id <> ALL(
            SELECT Prodotto.id
            FROM Prodotto,ListaImmagazzinamento, Contiene, Registrato, ProdottiConosciuti
            WHere ListaImmagazzinamento.id='$lista' AND Registrato.username='$username'
            AND ListaImmagazzinamento.id_UtenteRegistrato= Registrato.id_Utente
            AND Contiene.id_ProdottoConosciuto=ProdottiConosciuti.id_Prodotto 
            AND ProdottiConosciuti.id_Prodotto=Prodotto.id
            AND Contiene.id_ListaImmagazzinamento=ListaImmagazzinamento.id)";
        
if (!$result= mysqli_query($link, $query)) {
    $err="ERRORE";
 }
 while ($row = mysqli_fetch_assoc($result)) {
    $tutti_prodotti[] = $row;
}

//Aggingo elemento alla lista: 
if (!empty($add)) {//trovo l'id del prodotto corrispondente
  $query="SELECT id 
          FROM ProdottiConosciuti, Prodotto 
          WHERE Prodotto.nome='$add'
          AND  Prodotto.id=ProdottiConosciuti.id_Prodotto";

    if ($result= mysqli_query($link, $query)) {
        //se è presente lo aggiungo
        $id_elemento = mysqli_fetch_assoc($result)['id'];
        $query="INSERT INTO `Contiene` (`id_ProdottoConosciuto`, `id_ListaImmagazzinamento`) 
                VALUES ('$id_elemento', '$lista')";
        if (!$result= mysqli_query($link, $query)) {
             //errore elemento già nella lista;
             $err=" Elemento già nella lista";
        }else {
            
            header("location: Conteiner.php?id=".$lista);
        }
    }
    else {
        $err="ERRORE non riesco a trovare";
       header("location: Conteiner.php?id=".$lista);
    }   
}


//Modifico la descrizione o il nome
if (!empty($newname)) { //se almeno la desc. o il nome sono stati cambiati
  $query="UPDATE ListaImmagazzinamento SET nome = '$newname' WHERE id = '$lista'";
  $result_info=mysqli_query($link, $query);
  header("location: Conteiner.php?id=".$lista);
}if (!empty($newdesc)) {
  $query="UPDATE ListaImmagazzinamento SET descrizione = '$newdesc' WHERE id = '$lista'";
  $result_info=mysqli_query($link, $query);
  header("location: Conteiner.php?id=".$lista);
}

//Aggiungo Quantità all' elemento iesimo
if ($quantita=='plus1') {
  //trovo la quantità dell'elemento iesimo
  $query="SELECT quantità
          FROM Contiene
          WHERE id_ProdottoConosciuto= $id_elemento;
          ";
  $result=mysqli_query($link, $query);
  
  //aggingo più uno alla quantità
  $q=mysqli_fetch_assoc($result)["quantità"]+1;
  $query="UPDATE Contiene SET quantità = '$q' 
          WHERE Contiene.id_ProdottoConosciuto = $id_elemento
          AND Contiene.id_ListaImmagazzinamento = '$lista'";
 if ( !$result=mysqli_query($link, $query)) {
   $err="aaaaaa";
 }
  header("location: Conteiner.php?id=".$lista);
}

//cancello Quantità all'elemento
if ($quantita=='meno1') {
  //trovo la quantità dell'elemento iesimo
  $query="SELECT quantità
          FROM Contiene
          WHERE id_ProdottoConosciuto= $id_elemento;
          ";
  $result=mysqli_query($link, $query);
  
  $q=mysqli_fetch_assoc($result)["quantità"];
  if ($q>0) {
    # code...
    $q--;
  }
  else {
    $q=0;
  }
 
  $query="UPDATE Contiene SET quantità = '$q' 
          WHERE Contiene.id_ProdottoConosciuto = $id_elemento
          AND Contiene.id_ListaImmagazzinamento = '$lista'";
 if ( !$result=mysqli_query($link, $query)) {
   $err="aaaaaa";
 }
  header("location: Conteiner.php?id=".$lista);
}



//Cancello la lista
if ($cancella_lista=="Cancella Lista") {
  # code...
  $query = "DELETE FROM Contiene WHERE id_ListaImmagazzinamento='$lista' ";
  $result=mysqli_query($link, $query);
  $query = "DELETE FROM ListaImmagazzinamento WHERE id ='$lista' ";
  $result=mysqli_query($link, $query);
  header("location: ../Login/Login.php");

}


mysqli_close($link);
?>





<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"
      integrity="sha512-NmLkDIU1C/C88wi324HBc+S2kLhi08PN5GDeUVVVC/BVt/9Izdsc9SVeVfA1UZbY3sHUlDSyRXhCzHfr6hmPPw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />

    <link rel="stylesheet/less" type="text/css" href="../CSS/styles.less" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>

    <link rel="stylesheet" href="../CSS/style.css?v=3.4.1" />

    <style>
      @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap");
    </style>

  
    <title>SCANNER</title>
  </head>

  <body onload="setHandlersHOME()">
<!----------HEADER--------------------->
    <header class="headercard">
      <div class="logo intro-text">
        <input
          type="file"
          id="file"
          accept="image/*"
          capture="camera"
          style="display: none"
        />
        <a href="../index.php" id="logo"><h1>SCANNER</h1></a>
      </div>

      <div class="union">
        <ul class="menu">
          <li class="">
            <a href= <?php echo $home; ?>>
              <span style="" class="normal-text white-text">Home</span>
            </a>
          </li>
          <li><a href="../Contact.php"  class="normal-text white-text">Chi sono</a></li>
        </ul>
        <h3><a href="../Login/logout.php" class="buttonBase login"><?php echo $log; ?></a></h3>
      </div>
    </header>

    <main style="margin: 20px">

<!----------VISUALIZZAZIONE--------------------->
        <div>
          <div class="card ">
            <p class="normal-text titolocard">
                <?php echo $lista_nome;//stampo il nome della lista?>
                <br>
            </p>
            <p>
              <br>
              <div class="white-text titolocard">
                    <?php
                    //stampo la descrizione della lista se ne ha una
                      if (empty($descrizione)) {
                        echo "nessuna descrizione";
                      }
                      else{
                        echo $descrizione;
              
                      }
                     ?>
              </div> 
              <br>
              <?php
                //mostro tutti gli elementi della lista 
                foreach ($emparray as $key => $value) {
                  $link=str_replace(' ', '+', $value['nome']);
                

                  echo  '
                        <div class="colored-black-text link todolist small-text"
                        style="margin-right:20px;margin-left:20px; 
                        padding: 10px 7px 10px 7px";>'.

                        '<span>'.
                        '<span class="code">' .
                        
                        $value['codice_a_barre'].
                        " <br> ".
                        $value['nome'] .
                        " </span>" .
                        
                        "<br>".
                        $value['info'] .
                       
                        '</span>'.

                        '<div>'.
                        
            
                        

                        
                        '</div>'.

                        '<div>'.


                        '<span class="buttonEst">' .
                        
                        "<a href=Conteiner.php?quantita=meno1&id=".
                        $lista.
                        '&id_elemento='.
                        $value['id'].
                        '><div class="material-icons md-18">remove</div></a>' .
                        "<br>".
                        $value['quantità'].
                        "<br>".
                        "<a href=Conteiner.php?quantita=plus1&id=".
                        $lista.
                        '&id_elemento='.
                        $value['id'].
                        '><span class="material-icons md-18">add</span></a>' .

                        '</span>'.
                        
                        '<div>' .
                        
                        "<a href=/HOME/prodotti.php?prodotto=" .
                        $link.
                        '><span class="material-icons">
                        info
                        </span></a>' .
                        '<br>'.
                        "<a href=/HOME/Conteiner.php?cancella=" .
                        $value['id'].
                        '&id='.
                        $lista.
                        " " .
                        '><span class="material-icons">
                        clear
                        </span></a>' .
                        '</div>'.

                        '</div>'.
                        "</div>" .
                        "<br>";
                  } 
              ?>
  
            </p>
            <br>
            <div style="display:flex; justify-content:space-between">
            <button class="buttonBase"  onclick="displayMODIFICA('modifica')">
                <span class="material-icons">
                  settings
                </span>
              </button>
              <button class="buttonBase"  onclick="displayMODIFICA('aggiungi')">
                <span class="material-icons">
                  add_box
    	          </span>
              </button>
            </div>
              
            <p class="intro-text white-text">
              <?php 
                echo "<br>";
                echo $err;
              ?>
            </p>

          </div>
          <br>
          <br>
          <div class="card" id="quantità" style="display:none">
                
          </div>
          <br>
          <br>
       </div>
        
        

<!----------MODIFICA--------------------->
        <div id="Modifica&ADD">
          <div id="modifica" style="display:none">
            <!-- modifica dati della lista -->
            <div class="card">
              <p class="titolocard normal-text">
                MODIFICA LISTA
              </p>
              <br>
              <div class="normal-text white-text">
                  <form novalidate action=<?php echo "Conteiner.php?id=".$lista ?> 
                        method="post" class="normal-text white-text">

                    <input type="text" required="" name="NewName" class=""></input>
                    <label alt="Nome" placeholder="Nome" class=""></label>

                    <input type="text" required="" name="NewDesc" class=""></input>
                    <label alt="Descrizione" placeholder="Descrizione" class=""></label>
          
                    <input type="submit" value="Invia" class="buttonBase">
                  </form>  
                  <br>

                  <form action=<?php echo "Conteiner.php?id=".$lista; ?>  method="post">
                    <input type="submit" name="cancella_lista" value="Cancella Lista" class="buttonBase">
                  </form>
                
              </div>
            </div>
            <br>
            <br>
          </div>

            <!-- aggiungi prodotto -->
            <div id="aggiungi" style="display:none;">
              <div class="card">
                <p class="titolocard normal-text">    
                    AGGIUNGI PRODOTTO
                    <br>
                    <br>
                    <input type="text" required="" name="cerca" id="boxCerca" 
                    class="boxCerca" oninput="search(this.value,'conteiner')"></input>
                    <label alt="" placeholder="" class=""></label>
                </p>
                <br>
                <ul id="lista">
                  <!-- mostro tutti i prodotti del database -->
                    <?php 
                      foreach ($tutti_prodotti as $key => $value) {  
                        $link=str_replace(' ', '+', $value['nome']);
                        echo  '<div class="white-text link titolocard small-text" 
                                style="margin-right:20px;margin-left:20px;
                                padding: 10px 7px 10px 7px;
                                display:flex; justify-content:space-between" >' .

                                
                                '<div class="code">'.
                                $value['nome'].
                                "</div> ".
                              
                                "<a href=Conteiner.php?add=".
                                $link.
                                ">".
                                '<div class="material-icons buttonBase">
                                add_circle
                                </div>'.
                                "</a>".
                              
                                "</div>".
                                "<br>";
                      } 
                    ?>
                </ul>
              </div>
            </div>
        </div>

    </main>
    <span style="display;none" id="copy"></span>
    <span id="img" style="display:none"></span>
    <span id="fileElem" style="display:none"></span>

    <script src="../Librerie/quagga.min.js"></script>
    <script src="../JavaScript.js"></script>
        
</body>


<!-------