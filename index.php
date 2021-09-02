<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $home="HOME/home.php";
    $log= "Logout";
}
else {
  $home="index.php";
  $log= "Login";
}

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

    <link rel="stylesheet" href="CSS/style.css?v=3.4.1" />

    <link rel="stylesheet/less" type="text/css" href="CSS/styles.less" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>

    <style>
      @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap");
    </style>

    <title>SCANNER</title>
  </head>

  <body onload="setHandlersINDEX()">
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
        <a href="#" id="logo"><h1>SCANNER</h1></a>
      </div>

      <div class="union">
        <ul class="menu">
          <li class="">
            <a href=<?php echo $home; ?>>
              <span style="" class="normal-text">Home</span>
            </a>
          </li>
          <li><a href="/Contact.php" class="normal-text">Chi sono</a></li>
        </ul>
        <h3>
          <a href=<?php echo "/Login/".$log.".php"; ?> class="buttonBase login"><?php echo $log; ?></a>
        </h3>
      </div>
    </header>
    <br>

    <!------------------------------->
    

    <!----------MAIN--------------------->

    <main style="margin: 20px">
        

        <br />
       

    <!----------Scannerizzazione--------------------->
      <div class="card">
        <p class="titolocard">
        <span class="material-icons md-18" style="margin-top:px; color:white;">image </span>
        <span class="normal-text">SCANNERIZZA</span>
        </p>
      
        <br>
        <input
          type="file"
          id="fileElem"
          accept="image/*"
          style="display: none"
        />
        <a href="#" id="img" class="buttonBase">Seleziona Immagine</a>
        <br/>
        <br>
        <span class="normal-text white-text">
          <input type="text" required="" name="nome" id="result" class=""></input>
          <label alt="Risultato Scansione" placeholder="Codice:" class=""></label>
          <button id="copy" class="buttonBase icon normal-text">
          <span class="material-icons md-18">
            content_copy
          </span>
          </button>
        </span>
        
      </div>
      <br>
      <br>

    <!----------RICERCA--------------------->
    <div CLASS="card">
      <div class="normal-text titolocard white-text">
        <span class="material-icons md-18">search</span>
        CERCA
        <br>
        <br>
        <input type="text" required="" name="cerca" id="boxCerca" size="15" class="boxCerca" oninput="search(this.value,'codice')"></input>
        <label alt="solo Codici a Barre" placeholder="Codici a Barre" class=""></label>
      </div> 
      <br>
      <ul id="lista">
          
      </ul> 
      <div id="prova"></div>
    </div>
    <br>
    <br>

     <!----------INFO--------------------->
    <div class="card" style="display:none" id="INFO">
      <h3 class="titolocard white-text">
        INFO
      </h3>
      <br>
      <p class="normal-text white-text link">
        Se vuoi aggiungere oggetti al database,
        <br>
        o se vuoi effettuare ricerche, 
        modificare prodotti, 
        creare liste e altro allora <a href="/Login/login.php">Loggati</a>
      </p>
    </div>
        
    </main>

  <!----------FOOTER--------------------->

    <footer></footer>
    <script src="Librerie/quagga.min.js"></script>
    <script src="JavaScript.js"></script>
    
  </body>
</html>
