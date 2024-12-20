<?php
include('db.php');
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$title = $content = $image = '';

  if(isset($_POST['add'])) {
      $title = trim($_POST['title']);
      $content = trim($_POST['content']);
      $image = trim($_POST['image']);
      $stmt = $conn->prepare("INSERT INTO articles (title, content, image, author_id) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("sssi", $title, $content, $image, $userId);
      $stmt->execute();
      $stmt->close();
      $_SESSION['article_id'] = mysqli_insert_id($conn);
      $title = $content = $image = '';
  }
  if (isset($_GET['edit_id'])) {
        $editId = $_GET['edit_id'];
        $stmt = $conn->prepare("SELECT title, content, image FROM articles WHERE id = ?");
        $stmt->bind_param("i", $editId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $title_ = $data['title'];
            $content_ = $data['content'];
            $image_ = $data['image'];
        }
        $stmt->close();

  if(isset($_POST['update'])) {
    $title_ =htmlspecialchars(trim($_POST['title']));
    $content_ = htmlspecialchars(trim($_POST['content']));   
    $image_ = htmlspecialchars(trim($_POST['image']));
    
    $stmt = $conn->prepare("UPDATE articles SET title = ?, content = ?, image = ? WHERE id = $editId");
    $stmt->bind_param("sss", $title_, $content_, $image_); 
    $stmt->execute();
    $stmt->close(); 
    $title_ = $content_ = $image_ = '';
    header("Location: dashboard.php");
  }
} 
  if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->bind_param("i", $deleteId); 
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php"); 
    exit();
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
  <title>dashboard</title>
</head>
<body>
  <div class="container">
          <div class="sidebar">
            <h1 class="dashboardTitle">Dashboard</h1>
            <div class="buttons">  
                <button class="PostBtn" id="statisticsBtn"><i class="fa-solid fa-chart-simple sidebarIcon"></i>Statistique</button>
                <button class="PostBtn" id="showAddForm"><i class="fa-solid fa-circle-plus sidebarIcon"></i> New Article</button>
                <button class="PostBtn" id="managePostBtn"><i class="fa-solid fa-sliders sidebarIcon"></i> All Articles</button>
                <button class="PostBtn" id="logoutBtn"> <i class="fa-solid fa-right-from-bracket sidebarIcon"></i>Logout</button>
            </div>  
          </div>
      <div class="content-container">
        <div class="statistique">
            <div class="stat vues"></div>
            <div class="stat commentaires"></div>
            <div class="stat likes"></div>
        </div>
          <div class="formContainer" style="display: none;" >
              <form action="" method="POST">
              <i class="fa-solid fa-rectangle-xmark close"></i>
                  <h1 class="formTitle">Article From</h1>
                  <input type="text" name="title" value="<?php echo  htmlspecialchars(trim($title)); ?>" placeholder="Title" required>
                  <textarea  name="content" rows="6" placeholder="Post content" required><?php echo htmlspecialchars(trim($content)); ?></textarea>
                  <input type="text" name="image" value="<?php echo htmlspecialchars(trim($image)); ?>" placeholder="Image URL" required>
                  <button type="submit" name="add" class="addBtn">Add Article</button>
              </form>   
          </div>
          <div class="formContainer" style="<?php echo isset($_GET['edit_id']) ? 'display: block;' : 'display: none;'; ?>">
              <form action="" method="POST">
                  <i class="fa-solid fa-rectangle-xmark close"></i>
                  <h1 class="formTitle">Edit Article</h1>
                  <input type="text" name="title" value="<?php echo htmlspecialchars($title_); ?>" placeholder="Title" required>
                  <textarea name="content" rows="6" placeholder="Post content" required><?php echo htmlspecialchars($content_); ?></textarea>
                  <input type="text" name="image" value="<?php echo htmlspecialchars($image_); ?>" placeholder="Image URL" required>
                  <button type="submit" name="update" class="addBtn">Update Article</button>
              </form>
          </div>
          <div class="content">
          <div class="allArticles" style="display: none;">
          <h2 class="tableTitle">All Articles</h2>
            <table>
                <thead>
                    <tr>
                        <th>Article title</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  <?php 
                    if($conn) {
                      $query = "SELECT id, title FROM articles WHERE author_id = ?";
                      $stmt = $conn->prepare($query);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      if ($result->num_rows > 0) {
                        while ($data = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($data['title']) . "</td>";
                            echo "<td class='btns'>";
                            echo "<a href='dashboard.php?edit_id=" . $data['id'] . "' class='btnTable edit' id='showEditForm'>Edit</a>";
                            echo "<a href='dashboard.php?delete_id=" . $data['id'] . "' class='btnTable delete'>Delete</a>";                            
                            echo "</td>";
                            echo "</tr>";
                        }
                      } else {
                          echo "<tr><td colspan='2'>No articles found</td></tr>";
                      }                  
                      $stmt->close();
                      $conn->close();
                    }                    
                  ?>                      
                </tbody>
            </table>    
          </div>        
          </div>
      </div>
  </div>
  <script>
      let formContainer = document.querySelector(".formContainer");
      let AddFormBtn = document.getElementById("showAddForm");
      let EditFormBtn = document.getElementById("showEditForm");

      AddFormBtn.addEventListener("click", () => {
        console.log('click');
        
        formContainer.style.display = "block";

        });
    
      let closeForm = document.querySelectorAll(".close");
      closeForm.forEach(btn => {
        btn.addEventListener("click", () => {
          formContainer.style.display = "none";
        });
      });
      
    
      let logoutBtn = document.getElementById("logoutBtn");
              logoutBtn.addEventListener("click", () => {;
                  window.location.href = "logout.php";
              })
    

let AllArticlesBtn = document.getElementById("managePostBtn");
let allArticles = document.querySelector(".allArticles");

    AllArticlesBtn.addEventListener("click", () => {
      console.log('click sur all article');
       allArticles.classList.toggle("showArticles");
});
  </script>
</body>
</html>