<?php
$error = "";
session_start(); 

  include('db.php');
  if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
      $username = $_POST['username'];
      $password = $_POST['password']; 

      if ($conn) {

          $stmt = $conn->prepare("SELECT id, password FROM author WHERE username = ? ");
          $stmt->bind_param("s", $username);
          $stmt->execute();
          $stmt->store_result();
          $stmt->bind_result($user_id, $hashed_password);
          $stmt->fetch();
    
            if ($stmt -> num_rows > 0 && password_verify($password, $hashed_password)) {  
              $_SESSION['user_id'] = $user_id;  
              $_SESSION['username'] = $username;
              if(isset($_SESSION['user_id'])) {
                header("Location: dashboard.php");
                exit();
              }
            }
            else{
              $error = "username or password incorrect";
            }
            $stmt->close();      
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
        <h1 id="titleForm">Login</h1>
        <p id="message">Welcome back! Please login to your account</p>
        <p class="error"><?php echo $error ?></p>
      </div>
      <form action="login.php" method="POST">
        <div class="input-container">
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-container">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn" id="LoginBtnForm" name= "login">Login</button>
      </form>
      <p class="link">
        Need A New Account? <a href='signup.php'>Sign Up</a>
      </p>
    </div> 
</body>
</html>