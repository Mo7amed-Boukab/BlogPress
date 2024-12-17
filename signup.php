<?php
    // include('index.php');
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $username = $_POST['username'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

      $conn = mysqli_connect("localhost", "mohamed", "", "blogpress");
      if (!$conn){
          echo "Connection failed: " . mysqli_connect_error();
      }
      else{
        $stmt = $conn->prepare("INSERT INTO author (username, password) VALUES(?,?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->close();
         echo "Account Created Successfully!";
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
          <h1 id="title">Create An Account</h1>
          <p id="message">Join us and start your journey as an author - for free!</p>
        </div>
        <form action="signup.php" method="POST">
          <div class="input-container">
            <input type="text" name="username" placeholder="Username" required>
          </div>
          <div class="input-container">
            <input type="password" name="password" placeholder="Password" required>
          </div>
          <button type="submit" class="btn"  name= "signup">Create Account</button>
        </form>
        <p class="link">
          Already Have An Account? <a href="login.php">Login</a>
        </p>
</div>


</body>
</html>