<?php
// Initialize the session
session_start();

require "../Setup/config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../Login/login.php");
    exit;
}

$nome=$_GET['prodotto'];
$new_name=$_POST['new_name'];
$new_info=$_POST['new_info'];
$new_code=$_POST['new_code'];
$cancella=$_POST['cancella'];


//-----------------------------------MODIFICA PARAMETRI------------------------//



//modifica informazioni
if (!empty($new_info)) {
    $id= $_SESSION["prodotti"]["id"];
    $query="UPDATE Prodotto SET info = '$new_info' WHERE Prodotto.id = '$id'";
    $result_info=mysqli_query($link, $query);
}

//modifica codice a barre
if (!empty($new_code)) {
    $id_Prodotto=$_SESSION["prodotti"]["id_Prodotto"];
    $id=$_SESSION["prodotti"]["id"];
   
    if (empty($id_Prodotto)) {
        
        $query="INSERT INTO ProdottiConosciuti (`id_Prodotto`, `codice_a_barre`, `data_iscrizione`) VALUES ('$id', '$new_code', CURRENT_TIMESTAMP)";
        $result_code=mysqli_query($link, $query);
    }
    else{
       
        $query="UPDATE ProdottiConosciuti SET codice_a_barre = '$new_code' WHERE ProdottiConosciuti.id_Prodotto = '$id'";
        $result_code=mysqli_query($link, $query);
    }    
}

//modifica nome
if (!empty($new_name)) {
  $id= $_SESSION["prodotti"]["id"];
  $query="UPDATE Prodotto SET nome = '$new_name' WHERE Prodotto.id = '$id'";
  $result_info=mysqli_query($link, $query);
  header("location: prodotti.php?prodotto=".$new_name);
}

//cancello l'elemento
if ($cancella=='Cancella Elemento') {
    $id_Prodotto=$_SESSION["prodotti"]["id_Prodotto"];
    $id=$_SESSION["prodotti"]["id"];

    //controllo se è presente in qualche lista
      //controllo nella lista to do
    $query= "SELECT Prodotto.id
              From Prodotto, DaAcquisire
              WHERE DaAcquisire.id_Prodotto=Prodotto.id
              AND Prodotto.id='$id'";
    if ($result=mysqli_query($link, $query)) {
      # code...
      if(mysqli_num_rows($result)> 0){
        $query = "DELETE FROM DaAcquisire WHERE id_Prodotto ='$id' ";
        $result=mysqli_query($link, $query);
      }
    }
      //controllo se è nel conteiner
    $query= "SELECT ProdottiConosciuti.id_Prodotto
            From Prodotto, Conteiner
            WHERE Contiene.id_ProdottoConosciuto=ProdottiConosciuti.id_Prodotto
            AND ProdottiConosciuti.id='$id_Prodotto'";
    if ($result=mysqli_query($link, $query)) {
      if(mysqli_num_rows($result)> 0){
        $query = "DELETE FROM Contiene WHERE id_ProdottoConosciuto ='$id_Prodotto' ";
        $result=mysqli_query($link, $query);
      }
    }


    $query = "DELETE FROM ProdottiConosciuti WHERE id_Prodotto ='$id_Prodotto' ";
    $result=mysqli_query($link, $query);

    $query = "DELETE FROM Prodotto WHERE id ='$id' ";
    $result=mysqli_query($link, $query);

    header("location: ../Login/login.php");
}

//trovo il prodotto corrispondente
if (!empty($nome)) {
    # code...
    $query="SELECT * 
        FROM Prodotto Left JOIN ProdottiConosciuti ON (Prodotto.id=ProdottiConosciuti.id_Prodotto ) 
        WHERE Prodotto.nome='$nome'";

    if (!$result= mysqli_query($link, $query)) {
        //errore;
     }
     $row = mysqli_fetch_assoc($result);
     $_SESSION["prodotti"]["id"]=$row["id"];
     $_SESSION["prodotti"]["id_Prodotto"]=$row['id_Prodotto'];
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $home="home.php";
    $log= "Logout";
    
  
  }
  else {
  $home="../index.php";
  $log= "Login";
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
    <link rel="stylesheet" href="../CSS/style.css?v=3.4.1" />

    <link rel="stylesheet/less" type="text/css" href="../CSS/styles.less" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>

    <style>
      @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap");
    </style>

    <title>SCANNER</title>
  </head>

  <body>
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

    <!------------------------------->
   
     <!----------MAIN--------------------->
    <br>
    <br>
    <main style="margin: 20px">
<!----------OGGETTO--------------------->
        <div class="card">
            <?php 
                
            ?>
            <p class="titolocard normal-text">
                <?php 
                    echo $row["nome"];
                ?>
            </p>
            <br>

            <p class="normal-text white-text link"> 
                <?php 
                    echo '<span style="font-weight: bold;">Nome: </span>'.$row["nome"];
                    echo "<br>";
                    echo "<br>";

                    $var=str_replace(' ', '+', $nome);
                    $link=
                    "<a href=https://www.amazon.it/s?k=".$var.' class=link target="_blank"'.">COMPRA</a>";
                    echo '<span style="font-weight: bold;">Link Amazon: </span>'.$link ;
                    
                    echo "<br>";
                    echo "<br>";
                    echo '<span style="font-weight: bold;">Info: </span>'.$row["info"];
                    echo "<br>";
                    echo "<br>";
                    echo '<span style="font-weight: bold;">Codice a Barre: </span>'.$row["codice_a_barre"];
                    echo "<br>";
                    echo "<br>";
                    echo '<span style="font-weight: bold;">Data Scansione: </span>'.$row["data_iscrizione"];
                    echo "<br>";

                 ?>
                <br>
            </p>
            <button class="buttonBase" onclick="displayMODIFICA('modifica')">Modifica</button>
        </div>

        <br>
        <br>
<!----------------MODIFICA--------------------->
        <div class="card" id="modifica" style="display:none">
            <p class="normal-text titolocard">
                MODIFICA
                <br>
            </p>
            <br>
            <div class="normal-text white-text">
                <form novalidate action=<?php $var=str_replace(' ', '+', $nome); echo "prodotti.php?prodotto=".$var ?> method="post" class="normal-text white-text">
                    <input type="text" required="" name="new_name" class=""></input>
                    <label alt="Nome" placeholder="Nome" class=""></label>

                    <input type="text" required="" name="new_info" class=""></input>
                    <label alt="Info" placeholder="Info" class=""></label>
                    
                    
                    <input type="text" required="" name="new_code" class=""></input>
                    <label alt="Codice a barre" placeholder="Codice a barre" class=""></label>
                    
                    <input type="submit" value="Invia" class="buttonBase">
                </form>  
                <br>

                <form action=<?php $var=str_replace(' ', '+', $nome); echo "prodotti.php?prodotto=".$var; ?>  method="post">
                            
                  <input type="submit" name="cancella" value="Cancella Elemento" class="buttonBase">
                </form>
               
            </div>

            
            <?php 
            echo $err;
            ?>

        </div>



    </main>

    <script src="../Librerie/quagga.min.js"></script>
    <script src="../JavaScript.js"></script>
    
    </body>

