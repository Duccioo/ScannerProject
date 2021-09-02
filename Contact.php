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
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap");
    </style>

    <title>Chi Sono</title>
    <script src="Librerie/quagga.min.js"></script>
    <script src="JavaScript.js"></script>
  
    <body>
    <header class="headercard">
      <div class="logo intro-text">
        <input
          type="file"
          id="file"
          accept="image/*"
          capture="camera"
          style="display: none"
        />
        <a href="index.php" id="logo"><h1>SCANNER</h1></a>
      </div>

      <div class="union">
        <ul class="menu">
          <li class="">
            <a href= <?php echo $home; ?>>
              <span style="" class="normal-text">Home</span>
            </a>
          </li>
          <li><a href="/Contact.php"  class="normal-text">Chi sono</a></li>
        </ul>
        <h3><a href=<?php echo "Login/".$log.".php"; ?> class="buttonBase login"><?php echo $log; ?></a></h3>
      </div>
    </header>
    <br>
    
    <main style="margin: 20px;">
      <div class="card">
      <h2 class="normal-text titolocard">Progetto di Duccio Meconcelli</h2>
      <br>
        <h3 class="normal-text link">
          Email:
          <a href="mailto:meconcelliduccio@gmail.com">meconcelliduccio@gmail.com</a>
        </h3>
        <h3 class="normal-text link">Sito:
            <a href="http://www.duccio.me" target="_blank" >Duccio.me</a>
        </h3>
        <h3 class="normal-text link">
            Github:
            <a href="https://github.com/Duccioo" target="_blank">Duccioo</a>
        </h3>
      </div>
      <br>
      <br>
      <div class="card">
        <div class="titolocard normal-text white-text">RISORSE</div>
        <br>
        <p class="normal-text white-text link">
          Libreria per scansione dei codici a barre: <a target="_blank" href="https://serratus.github.io/quaggaJS/examples/file_input.html">QuaggaJS</a>
          <br>
          Stile degli input di testo: <a target="_blank" href="https://codepen.io/dannykingme/pen/IvFuB">Adaptive Placeholder</a>
          <br>
          Font 'Roboto': <a target="_blank" href="https://fonts.google.com/" >Google Font</a>
          <br>
          Icone: <a target="_blank" href="https://fonts.google.com/icons" >Google Icon</a>
        </p>
      </div>
    </main>
  </body>
</html>
