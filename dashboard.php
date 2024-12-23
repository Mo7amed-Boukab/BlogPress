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
            $title = $content = $image = '';
        }
        // ------------- update -----------------------------------------------------
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
      // ---------- delete -------------------------------------------
        if (isset($_GET['delete_id'])) {
          $deleteId = $_GET['delete_id'];
          $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
          $stmt->bind_param("i", $deleteId); 
          $stmt->execute();
          $stmt->close();
          header("Location: dashboard.php"); 
          exit();
        }
      // ----------- statistique --------------------------------------------------------------------------------------
      $total_views = $conn->query("SELECT SUM(views) AS total_views FROM articles WHERE author_id = $userId")->fetch_assoc()['total_views'] ?? 0;
      $total_likes = $conn->query("SELECT SUM(likes) AS total_likes FROM articles WHERE author_id = $userId")->fetch_assoc()['total_likes'] ?? 0;
      $total_comments = $conn->query("SELECT COUNT(com.id) AS total_comments 
                                      FROM comments AS com 
                                      INNER JOIN articles AS art ON com.article_id = art.id 
                                      WHERE art.author_id = $userId")->fetch_assoc()['total_comments'] ?? 0;
      $total_blogs = $conn->query("SELECT COUNT(*) AS total_blogs FROM articles WHERE author_id = $userId")->fetch_assoc()['total_blogs'] ?? 0;
    //---------------- chart js 
    $sql = " SELECT category, COUNT(*) AS total_articles 
              FROM articles 
              WHERE author_id = $userId
              GROUP BY category;" 

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
    <title>Dashboard</title>
  </head>
  <body>
    <div class="grid-container">

      <!----------------------------------- header ------------------------------------>
      <header class="header_">
        <div class="menu-icon" onclick="openSidebar()">
          <span class="material-icons-outlined">menu</span>
        </div>
        <div class="searchTopBar">
          <span class="material-icons-outlined" style="margin-right: 5px; color: #888;">search</span>
          <input type="text" placeholder="Search" style="border: none; outline: none; flex: 1;" />
        </div>
      </header>
      <!----------------------------------------------------------------------------------->
      <!----------------------------------- sidebar ---------------------------------------->
      <aside id="sidebar">
          <div class="sidebar-title">
            <div class="sidebar-logo"> <span class="material-icons-outlined">dashboard</span> Dashboard          
            </div>
            <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
          </div>

        <ul class="sidebar-list">

              <li class="sidebar-list-item" id="Statistics">
                <a href="#">
                  <span class="material-icons-outlined">analytics</span> Statistics
                </a>
              </li>

              <li class="sidebar-list-item" id="addBlog">
                <a href="#">
                  <span class="material-icons-outlined">add_box</span> New Blog
                </a>
              </li>
              
              <li class="sidebar-list-item" id="manageAllBlogs">
                <a href="#">
                  <span class="material-icons-outlined">settings</span> Manage 
                </a>
              </li>
        
              <li class="sidebar-list-item" id="logoutBtn">
                <a href="#">
                  <span class="material-icons-outlined">logout</span> Log Out
                </a>
              </li>
        </ul>
      </aside>
  <!-- -------------------------------------------------------------------------------------------- -->
         
      <main class="main-container">
          <div class="mainTitle">
            <p class="font-weight-bold">
              <span class="material-icons-outlined large-icon">person</span><?php echo $username ?>
            </p>
          </div>    
        
        <div class="main-cards">
          <!-- -------------------------- statistique -------------------------- -->
          <div class="card">
            <div class="card-inner">
              <p class="color-icons">Vues</p>
              <span class="material-icons-outlined color-icons">remove_red_eye</span>
            </div>
            <span class="color-icons font-weight-bold"> <?php echo $total_views ?? 0; ?> </span>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="color-icons">Likes</p>
              <span class="material-icons-outlined color-icons">thumb_up</span>
            </div>
            <span class="color-icons font-weight-bold"> <?php echo $total_likes ?? 0; ?> </span>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="color-icons">comment</p>
              <span class="material-icons-outlined color-icons">comment</span>
            </div>
            <span class="color-icons font-weight-bold">  <?php echo $total_comments ?? 0; ?> </span>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="color-icons">blogs</p>
              <span class="material-icons-outlined color-icons">article</span>
            </div>
            <span class="color-icons font-weight-bold">  <?php echo $total_blogs ?? 0; ?> </span>
          </div>
        </div>
        <!-- --------------------- forms ---------------------- -->
        <div class="formContainer" id="formContainer"style="display:none" >
              <form action="" method="POST">
              <i class="fa-solid fa-rectangle-xmark close"></i>
                  <h1 class="formTitle">Article From</h1>
                  <input type="text" class="input" name="title" value="<?php echo  htmlspecialchars(trim($title)); ?>" placeholder="Title" required>
                  <textarea  name="content" class="textarea" rows="6" placeholder="Post content" required><?php echo  htmlspecialchars(trim($content)); ?></textarea>
                  <input type="text" class="input" name="image" value="<?php echo  htmlspecialchars(trim($image)); ?>" placeholder="Image URL" required>
                  <button type="submit" name="add" class="addBtn">Add Article</button>
              </form>   
        </div>
        <div class="formContainer" style="<?php echo isset($_GET['edit_id']) ? 'display: block;' : 'display: none;'; ?>">
              <form action="" method="POST">
                  <i class="fa-solid fa-rectangle-xmark close"></i>
                  <h1 class="formTitle">Edit Article</h1>
                  <input type="text"  class="input" name="title" value="<?php echo htmlspecialchars($title_); ?>" placeholder="Title" required>
                  <textarea name="content" class="textarea" rows="6" placeholder="Post content" required><?php echo htmlspecialchars($content_); ?></textarea>
                  <input type="text"  class="input" name="image" value="<?php echo htmlspecialchars($image_); ?>" placeholder="Image URL" required>
                  <button type="submit" name="update" class="addBtn">Update Article</button>
              </form>
          </div>
        <!-- ---------------------------------------- -->
        <div class="table-container" id="tableAllBlogs" style="display: none;">
                  <?php
                        $sql = "SELECT art.id, art.title, art.views, art.likes, 
                                       COUNT(com.id) AS total_comments    
                                FROM 
                                    articles AS art
                                LEFT JOIN 
                                    comments AS com ON art.id = com.article_id
                                 WHERE 
                                    art.author_id = $userId   
                                GROUP BY 
                                    art.id;" ;
                        $result = $conn->query($sql);
                  ?>
        <table>
    <thead>
        <tr>
            <td>Id</td>
            <td>Title</td>
            <td>View</td>
            <td>Likes</td>
            <td>Comments</td>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($data['id']) . "</td>";
                echo "<td>" . htmlspecialchars($data['title']) . "</td>";
                echo "<td>" . htmlspecialchars($data['views']) . "</td>";
                echo "<td>" . htmlspecialchars($data['likes']) . "</td>";
                echo "<td>" . htmlspecialchars($data['total_comments']) . "</td>";
                echo "<td class='btns'>
                        <a href='dashboard.php?edit_id=" . $data['id'] . "' class='btnTable edit'>Edit</a>
                        <a href='dashboard.php?delete_id=" . $data['id'] . "' class='btnTable delete'>Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Nothing is found</td></tr>";
        }
        ?>
    </tbody>
</table>

  </div>

        <!-- ------------------- chart js --------------------- -->

        <div class="charts" id="charts" >

          <div class="charts-card">
            <p class="chart-title">Vues By Article</p>
            <div id="bar-chart"></div>
          </div>

          <div class="charts-card">
            <p class="chart-title">Likes By Vues</p>
            <div id="area-chart"></div>
          </div>

        </div>
      </main>


    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
    <script>
            let formContainer = document.getElementById('formContainer');
            let addBlog = document.getElementById('addBlog');
            let closeForm = document.querySelector('.close');

            addBlog.addEventListener('click',()=>{
              console.log('click add');
              
              formContainer.style.display = "flex";
            })

            closeForm.addEventListener('click', ()=>{
              console.log('click close');
              
              formContainer.style.display= "none";
            })

            let tableAllBlogs = document.getElementById('tableAllBlogs');
            let charts = document.getElementById('charts');
            let manageAllBlogs = document.getElementById('manageAllBlogs');
            let Statistics = document.getElementById('Statistics');

            manageAllBlogs.addEventListener('click',()=>{
              tableAllBlogs.style.display = "block";
              charts.style.display = "none";
            })

            Statistics.addEventListener('click',()=>{
              tableAllBlogs.style.display = "none";
              charts.style.display = "grid";
            })
            let logoutBtn = document.getElementById("logoutBtn");
              logoutBtn.addEventListener("click", () => {;
                  window.location.href = "logout.php";
              })
// SIDEBAR --------------------------------------------------------------------------------
            let sidebarOpen = false;
            const sidebar = document.getElementById('sidebar');

            function openSidebar() {
              if (!sidebarOpen) {
                sidebar.classList.add('sidebar-responsive');
                sidebarOpen = true;
              }
            }

            function closeSidebar() {
              if (sidebarOpen) {
                sidebar.classList.remove('sidebar-responsive');
                sidebarOpen = false;
              }
            }

// BAR CHART ------------------------------------------------------------------------------
            const barChartOptions = {
                  series: [
                    {
                      data: [120, 95, 78, 56, 34], 
                    },
                  ],
                  chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                  },
                  colors: ['#21232d', '#9ea59c', '#21232d', '#9ea59c', '#21232d'],
                  plotOptions: {
                    bar: {
                      distributed: true,
                      borderRadius: 4,
                      horizontal: false,
                      columnWidth: '40%',
                    },
                  },
                  dataLabels: { enabled: false },
                  legend: { show: false },
                  xaxis: {
                    categories: ['Article 1', 'Article 2', 'Article 3', 'Article 4', 'Article 5'], 
                  },
                  yaxis: {
                    title: { text: 'Vues' },
                  },
                };

                const barChart = new ApexCharts(
                  document.querySelector('#bar-chart'),
                  barChartOptions
                );
                barChart.render();

                            // AREA CHART
                            const areaChartOptions = {
                  series: [
                    {
                      name: 'Vues',
                      data: [300, 400, 500, 350, 600], // Remplacez par les données des vues mensuelles.
                    },
                    {
                      name: 'Likes',
                      data: [200, 300, 250, 400, 450], // Remplacez par les données des likes mensuels.
                    },
                  ],
                  chart: {
                    height: 350,
                    type: 'area',
                    toolbar: { show: false },
                  },
                  colors: ['#246dec', '#cc3c43'],
                  dataLabels: { enabled: false },
                  stroke: { curve: 'smooth' },
                  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'], // Remplacez par les mois extraits dynamiquement.
                  markers: { size: 0 },
                  yaxis: [
                    {
                      title: { text: 'Vues' },
                    },
                    {
                      opposite: true,
                      title: { text: 'Likes' },
                    },
                  ],
                  tooltip: {
                    shared: true,
                    intersect: false,
                  },
                };

                const areaChart = new ApexCharts(
                  document.querySelector('#area-chart'),
                  areaChartOptions
                );
                areaChart.render();

    </script>
  </body>
</html>