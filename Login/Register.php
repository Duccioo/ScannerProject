<?php
// Include config file
require_once "../Setup/config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "INSERIRE USERNAME.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username può solo contenere lettere o numeri";
    } else{
        // Prepare a select statement
        $sql = "SELECT id_Utente FROM Registrato WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Questo Username è già stato usato <br>";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Iserire una Password";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La password deve almeno avere 6 caratteri <br>";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Ripeti la password <br>";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Le passsword non combaciano <br>";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Registrato (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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

    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
    <link rel="stylesheet" href="../CSS/style.css?v=3.4.1" />

    <style>
      @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap");
    </style>

    <link rel="stylesheet/less" type="text/css" href="../CSS/styles.less" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>

    <title>REGISTER</title>
  </head>

</head>
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
        <a href="../index.html" id="logo"><h1>SCANNER</h1></a>
      </div>

      <div class="union">
        <ul class="menu">
          <li class="">
            <a href="../index.php">
              <span style="" class="normal-text">Home</span>
            </a>
          </li>
          <li><a href="../Contact.php" class="normal-text">Chi sono</a></li>
        </ul>
        <h3>
          <a href="Login.php" class="buttonBase login">Login</a>
        </h3>
      </div>
    </header>
    <br>
    <br>

   
<main style="margin: 20px">
    <div class="wrapper"> 
        <div class="card">
        <h2 class="intro-text titolocard">ISCRVITI</h2> <br>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group normal-text">
                <input type="text" required="" name="username" size="15" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"></input>
                <label alt="Username" placeholder="Username" ></label>
            </div>    

            <br>

            <div class="form-group normal-text">
                <input required=""  type="password" name="password" size="15" class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>"></input>
                <label alt="Password" placeholder="Password" ></label>
            </div>

            <div class="form-group normal-text">
                <input required="" type="password" name="confirm_password"  size="15" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>"></input>
                <label alt="Conferma Password" placeholder="Conferma Password" ></label>
            </div>

            <br>

            <div class="form-group">
                <span class="invalid-feedback colored-text"><?php echo $username_err; ?></span>
                
                <span class="invalid-feedback colored-text"><?php echo $password_err; ?></span>
               
                <span class="invalid-feedback colored-text"><?php echo $confirm_password_err; ?></span>
                
                <input type="submit" class="buttonBase" value="Submit">
                
            </div>
            <br>

            <p class="normal-text link">Hai già un account? <a href="login.php">Login QUI</a>.</p>
        </form>
        </div>
        
    </div>    
</main>
    
</body>
</html>