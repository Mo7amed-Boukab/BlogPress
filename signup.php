<?php
   $error = "";
   session_start();   
  include('db.php');
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if (!preg_match('/^[a-zA-Z0-9_]{2,20}$/', $username)) {
        $error = "username invalid";
    }
    else if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = "Password invalid";  
    }
    else {
          $pass_hashed = password_hash($password, PASSWORD_DEFAULT); 


            if ($conn) {
                $stmt = $conn->prepare("INSERT INTO author (username, password) VALUES(?,?)");
                $stmt->bind_param("ss", $username, $pass_hashed);
                if ($stmt->execute()) {
                header("Location: login.php");
                exit(); 
              }
              $stmt->close();
              
            }    
            
         }
  }  
?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Document</title>
</head>
<body>
<div class="account-form" id="accountForm">
        <div class="title">
          <h1 id="titleForm">Create An Account</h1>
          <p id="message">Join us and start your journey as an author - for free!</p>
          <p class="error"><?php echo $error ?></p>
        </div>
        <form action="signup.php" method="POST">
          <div class="input-container">
            <input type="text" name="username" placeholder="Username" required>
          </div>
          <div class="input-container">
            <input type="password"  name="password" placeholder="Password" required>
          </div>
          <button type="submit" class="btn"  name= "signup">Create Account</button>
        </form>
        <p class="link">
          Already Have An Account? <a href="login.php">Login</a>
        </p>
</div>


</body>
</html>