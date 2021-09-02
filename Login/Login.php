<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ../HOME/home.php");
    exit;
}
 
// Include config file
require_once "../Setup/config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id_Utente, username, password FROM Registrato WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: ../HOME/home.php");
                        } else{
                            
                            // Password is not valid, display a generic error message
                            $password_err = "Invalid username or password.";
                            
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $username_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
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
    <link rel="stylesheet" href="../CSS/style.css?v=3.4.2" />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap");
    </style>
    <link rel="stylesheet/less" type="text/css" href="../CSS/styles.less" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>

    <title>SCANNER</title>
  </head>
  <script src="quagga.min.js"></script>
  <script src="JavaScript.js"></script>

  <body onload="setHandlers()">
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
            <a href="login.php">
              <span style="" class="normal-text">Home</span>
            </a>
          </li>
          <li><a href="../Contact.php" class="normal-text">Chi sono</a></li>
        </ul>
        <h3>
          <a href="Register.php" class="buttonBase login">Registrati</a>
        </h3>
      </div>
    </header>
<br>
<br>
    
    <MAIn style="margin: 20px">
    <div class="card">
      <p class="titolocard normal-text">LOGIN</p>
      <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group normal-text white-text">
                <input type="text" required="" name="username" value="<?php echo $username; ?>" class=""></input>
                <label alt="Username" placeholder="Username" class=""></label>
              
            </div>    
            
            <div class="normal-text white-text">
                <input type="password" required="" name="password"></input>
                <label alt="Password" placeholder="Password" class=""></label>    
            </div>

            <span class="normal-text white-text"><?php echo $username_err; ?></span>
            <span class="normal-text white-text"><?php echo $password_err; ?></span>
            

            <div class="form-group">
                <input type="submit" class="buttonBase" value="Login">
            </div>
            <br>
            <p class="normal-text link">Non sei iscritto? <a href="register.php">Iscriviti Ora!</a></p>
        </form>
        
    </div>
   
  
  
  </MAIn>


    <footer></footer>
  </body>
</html>
