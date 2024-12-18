<?php
session_start();
  if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
 }
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>dashboard</title>
</head>
<body>
<div class="container">
        <div class="sidebar">
          <h1 class="dashboardTitle">Dashboard</h1>
          <div class="buttons">  
              <button class="PostBtn" id="addPostBtn">Add Post</button>
              <button class="PostBtn" id="managePostBtn">Manage Posts</button>
              <button class="PostBtn" id="statisticsBtn">Statistiques</button>
              <button class="PostBtn" id="logoutBtn">Logout</button>
          </div>  
        </div>
    <div class="content-container">
        <h1 class="contentTitle"><?php echo "Welcome <span class='user'> $username </span>"; ?></h1>
        <div class="content">
      
        </div>
    </div>
</div>
  <script>
    let logoutBtn = document.getElementById("logoutBtn");
        logoutBtn.addEventListener("click", () => {
            console.log("clicked");
            window.location.href = "logout.php";
        })
    let addPostBtn = document.getElementById("addPostBtn");
    let content = document.querySelector(".content");

    addPostBtn.addEventListener("click", () => {
        content.innerHTML = `
          <div class="formAddPostContainer">
              <form action="#" method="POST">
                  <h1 class="formPostTitle">Post From</h1>
                  <input type="text" name="title"  placeholder="Title" required>
                  <textarea  name="content" rows="6" placeholder="Post content" required></textarea>
                  <input type="text" name="image" placeholder="Image URL">
                  <button type="submit" class="addBtn">Add Post</button>
              </form> 
          </div>
        `
    });
let managePostBtn = document.getElementById("managePostBtn");
    managePostBtn.addEventListener("click", () => {
      content.innerHTML = `
            <h2 class="tableTitle">Manage Posts</h2>
            <table>
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  <?php 
                    include('db.php');
                    if($conn) {
                      $stmt = $conn->prepare("SELECT * FROM articles join author on articles.author_id = author.id");
                      $stmt->execute();
                      $result = $stmt->get_result();
                      while($data = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $data['id'] . "</td>";
                        echo "<td>" . $data['title'] . "</td>";
                        echo "<td>" . $data['username'] . "</td>";
                        echo "<td class='btns'>";
                        echo "<button class='btnTable edit'>Edit</button>";
                        echo "<button class='btnTable delete'>Delete</button>";
                        echo "<button class='btnTable publish'>Publish</button>";
                        echo "</td>";
                        echo "</tr>";
                      }
                    }
                  ?>
                    
                    
                </tbody>
            </table>
      `
    });
  </script>
</body>
</html>