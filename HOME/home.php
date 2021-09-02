<?php
// Initialize the session
session_start();
require "../Setup/config.php";

//mi salvo l'id utente e il nome 
$id=$_SESSION["id"];
$username=$_SESSION["username"]; 
$lista_nome=$_POST["lista_nome"];
$lista_descrizione=$_POST["lista_descrizione"];

$conteiner_nome=$_POST["conteiner_nome"];
$conteiner_descrizione=$_POST["conteiner_descrizione"];




//--------------AGGIUNGI PRODOTTO-----------//
//inserisco un Prodotto
if (isset($_POST['nome'])&&!empty($_POST["nome"])) {
  //se nome e info sono settati e nome è non nullo allora:
  $nome=trim($_POST['nome']);
  $info=trim($_POST['info']);  

  //controllo se il prodotto è già presente del DB
  $queryNOME = "SELECT nome FROM Prodotto WHERE nome = '$nome' ";  
  if (CercaTorF($link,$queryNOME)==true) {
    //elemento già nel database;
    $err='<p class="intro-text">elemento già nel database</p>';
  }  

  else {
       //se non è nel DB lo inserisco
      $query = "INSERT INTO `Prodotto` (`nome`, `info`, `id_UtenteRegistrato`) VALUES ('$nome', '$info','$id')";
      if (mysqli_query($link, $query)) {
        $err='<p class="intro-text">elemento inserito con successo</p>';
      }
      
      //controllo se il campo codice a barre è presente
      if (isset($_POST['codice_a_barre'])&& !empty($_POST["codice_a_barre"])) {

        //inserisco poi il prodotto conosciuto accoppiando lo stesso id
        $codice_a_barre = trim($_POST['codice_a_barre']);
        $queryCODICE = "SELECT codice_a_barre FROM ProdottiConosciuti WHERE codice_a_barre = $codice_a_barre";
        
        if (CercaTorF($link,$queryCODICE)==false) {
          //non è presente nessun altro codice uguale

          //trovo l'id del prodotto ad esso associato
          $queryNOME = "SELECT id FROM Prodotto WHERE nome = '$nome'";
          $resultNOME= mysqli_query($link,$queryNOME);
          $row= mysqli_fetch_assoc($resultNOME);
          $row=$row['id']; //prendo il primo risultato corrispondente e ne seleziono il campo id
          $query = "INSERT INTO `ProdottiConosciuti` (`id_Prodotto`, `codice_a_barre`, `data_iscrizione`) VALUES ('$row', '$codice_a_barre', CURRENT_TIMESTAMP)";
          if (mysqli_query($link, $query)) {
              $err='<p class="intro-text">elemento inserito con successo</p>';
          }
          
        } 
      }  
  }
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




//--------------LISTA TO-DO-----------//
//creo la query per mostrare tutte le liste dell'utente con id = $id
$query="SELECT *
  FROM ListaToDo, Registrato
  WHERE ListaToDo.id_UtenteRegistrato = Registrato.id_Utente
  AND id_Utente = '$id'  ";

//invio la richiesta e poi salvo i risultati in un arrey;
if (!$result= mysqli_query($link, $query)) {
  return "error";
}
$temparray = array();
while ($row = mysqli_fetch_assoc($result)) {
   $temparray[] = $row;
}

//creo nuova lista to do
if (!empty($lista_nome)) {
  $query = "INSERT INTO `ListaToDo` (`nome`, `data_creazione`, `descrizione`, id_UtenteRegistrato) 
            VALUES ('$lista_nome', CURRENT_TIMESTAMP,  '$lista_descrizione','$id')";
  if (mysqli_query($link, $query)) {
    $err_todolist='<p class="intro-text">Lista Creata</p>';
    header("location: home.php");
  }
  
  
}



//--------------LISTA CONTEINER----------- //
//creo la query per mostrare tutte le liste dell'utente con id = $id
$query="SELECT *
  FROM ListaImmagazzinamento, Registrato
  WHERE ListaImmagazzinamento.id_UtenteRegistrato = Registrato.id_Utente
  AND id_Utente = '$id'  ";

//invio la richiesta e poi salvo i risultati in un arrey;
if (!$result= mysqli_query($link, $query)) {
  return "error";
}
$contarray = array();
while ($row = mysqli_fetch_assoc($result)) {
   $contarray[] = $row;
}

//creo nuova lista conteiner
if (!empty($conteiner_nome)) {
  $query = "INSERT INTO `ListaImmagazzinamento` (`nome`, `descrizione`, id_UtenteRegistrato) 
            VALUES ('$conteiner_nome',  '$conteiner_descrizione','$id')";
  if (mysqli_query($link, $query)) {
    $err_conteiner='<p class="intro-text">Lista Creata</p>';
    header("location: home.php");
  }
  
  
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
<!----------HEADER----------------------------------->
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

<!------------------------------->
   
<!----------MAIN------------------------------------->
    
    
    <main id="main">
    <div id="base">
      <!----------CERCA--------------------->    
      <br>    
      <div class="card">
        <p class="titolocard normal-text">
        <span class="material-icons md-18">search</span>
        CERCA
          <br>
          <br>
          <input type="text" required="" name="cerca" id="boxCerca" class="boxCerca" oninput="search(this.value,'misto')"></input>
          <label alt="" placeholder="" class=""></label>
        </p>
        <br>
         
        <ul id="lista">

        </ul>
      </div>
  	  <br>
      <!----------Scansione--------------------->
      <br>
      <div class="card">
            <p class="normal-text titolocard"> 
                <span class="material-icons md-18" onclick="displayMODIFICA('risultato')" style="margin-top:px; color:white;">image </span>
                <span class="normal-text" onclick="displayMODIFICA('risultato')" style=" font-family: 'Roboto', 'sans-serif';">SCANSIONE </span>      
            </p>
            <br>
            <p>
            <a href="#" id="img" class="buttonBase" style="margin-top:px; color:white;">SCAN </a>
            <input type="file" id="fileElem" accept="image/*" style="display: none"/>
            </p>
            <br>
            
            <span style="display:none" id="risultato">
    
              <input type="text" required="" name="nome" id="result" class=""></input>
              <label alt="" placeholder="" class=""></label>
              <button id="copy" class="buttonBase icon" >
              <span class="material-icons md-18">
                content_copy
              </span>
            </span>
      </div>
      <br>
      <!----------AGGIUNGI--------------------->
      <br>
      <div class="card">
        
          <form action="home.php" novalidate method="post" class="normal-text white-text">
            	<p class='titolocard normal-text white-text' onclick="displayMODIFICA('aggiungi')">
                <span class="material-icons md-18" >
                  add
                </span>
                <span class="white-text" >AGGIUNGI OGGETTO</span>
                
              </p>
              
              <div id="aggiungi" style="display:none">
              <br>
                  <input type="text" required="" name="nome" id="nome" class=""></input>
                  <label alt="Nome Obbligatorio" placeholder="Nome" class=""></label>
                
                  <input type="text" required="" name="codice_a_barre" class=""></input>
                  <label alt="Codice a Barre" placeholder="Codice a Barre" class=""></label>
              
                  <input type="text" required="" name="info" class=""></input>
                  <label alt="Info" placeholder="Info" class=""></label>
             
                  <button class="buttonBase">Invia</button>
              </div>
                  
          </form>
          
          <span>
            <br>
            <?php echo $err;?>
          </span>
      </div>
      <br>
    </div>



<!----------LISTE------------------------->
      <div id="liste">
        <!----------to do list--------------->
        <br>
        <div class="card">
          <p class="titolocard normal-text" onclick="displayMODIFICA('ToDoList')">To Do List</p>
          
          <div class="link" id="ToDoList">
          <br>
            <?php 
            
              for ($i=0; $i < count($temparray); $i++) { 
                # code...
                echo '<div class="todolist normal-text white-text" style="display:flex; justify-content:space-between">'
                      ."<div>"
                      .$temparray[$i]['nome']
                      ."</div>"
                      ."<div>"
                      .'<a class="normal-text white-text" href="ToDoList.php?listatodo='
                      .$temparray[$i]['id'].'"> APRI</a>'
                      ."</div>"
                      ."</div>";
                echo "<br>";
              }
            ?>
            <br>
          <!--bottone per creare todolist  -->
          <button class="buttonBase" onclick="displayMODIFICA('creaToDoList')">Crea ToDoList</button>

          </div>
        
          <div id="creaToDoList" style="display:none">
              <br>
              <p class="titolocard">Crea ToDoList</p>
              <form action="home.php" novalidate method="post" class="normal-text">
              <br>
                <div>
                    <input type="text" required="" name="lista_nome" id="nome" class=""></input>
                    <label alt="Nome Lista" placeholder="Nome Lista" class=""></label>
                  
                    <input type="text" required="" name="lista_descrizione" class=""></input>
                    <label alt="Descrizione" placeholder="Descrizione" class=""></label>
        
                    <button class="buttonBase">Invia</button>
                </div>

              </form>
          </div>
          <span>
              <br>
              <?php echo $err_todolist;?>
            </span>
        </div>
        <br>

      
        <!----------Liste immagazzinamento--------------->
        
        <br>
        <div class="card">
          <p class="titolocard normal-text" onclick="displayMODIFICA('Conteiner')">Conteiner</p>
          
          <div class="link" id="Conteiner">
          <br>
            <?php 
            
              for ($i=0; $i < count($contarray); $i++) { 
                # code...
                echo '<div class="todolist normal-text white-text" style="display:flex; justify-content:space-between">'
                      ."<div>"
                      .$contarray[$i]['nome']
                      ."</div>"
                      ."<div>"
                      .'<a class="normal-text white-text" href="Conteiner.php?id='
                      .$contarray[$i]['id'].'"> APRI</a>'
                      ."</div>"
                      ."</div>";
                echo "<br>";
              }
            ?>
          <br>
          <!-- bottone per creare conteiner -->
          <button class="buttonBase" onclick="displayMODIFICA('creaConteiner')">Crea Conteiner</button>

          </div>
          
          <div id="creaConteiner" style="display:none">
              <br>
              <p class="titolocard">Crea Conteiner</p>
              <form action="home.php" novalidate method="post" class="normal-text">
              <br>
                <div>
                    <input type="text" required="" name="conteiner_nome" class=""></input>
                    <label alt="Nome Lista" placeholder="Nome Lista" class=""></label>
                  
                    <input type="text" required="" name="conteiner_descrizione" class=""></input>
                    <label alt="Descrizione" placeholder="Descrizione" class=""></label>
        
                    <button class="buttonBase">Invia</button>
                </div>

              </form>
          </div>
          <span>
              <br>
              <?php echo $err_conteiner;?>
            </span>
        </div>
        <br>
      </div>

     </main>

    <script src="../Librerie/quagga.min.js"></script>
    <script src="../JavaScript.js"></script>
        
</body>

</html>
