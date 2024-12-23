

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">

    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <title>BlogPress</title>
</head>
<body>
<header>
          <nav>
            <div class="logo_">Blog <span>Press</span> </div>
            <button class="toggle-btn openIcon" onclick="toggleSidebar()"><span class="material-icons-outlined">menu</span></button>
            <button class="toggle-btn closeIcon" onclick="toggleSidebar()"> <span class="material-icons-outlined" >close</span></button>
            <div class="links">
                <ul>
                    <li> <a href="#">Home</a> </li>
                    <li> <a href="#allBlogs">Blogs</a> </li>
                    <li> <a href="#popular">Popular</a> </li>
                    <li> <a href="#">categories</a></li>
                </ul>
                <button class="btn" id="LoginBtn" type="submit">Join Us</button>
              
            </div>
            <div class="sidebar" id="sidebar_">
                <ul>
                    <li> <a href="#">Home</a> </li>
                    <li> <a href="#allBlogs">Blogs</a> </li>
                    <li> <a href="#popular">Popular</a> </li>
                    <li> <a href="#">categories</a></li>
                    <button class="btn" id="LoginBtn" type="submit">Join Us</button>
                </ul>
            </div>
           
        </nav>
    </header>
  
<!-- ------------------------------------------------------------------------------------------------- -->

<!-- ------------------------------------------------------------------------------------------------- -->
      <section class="hero">
        <div class="hero-content">
          <h1>The place where words come to life and ideas take flight</h1>
          <p>Discover our articles, uncover unique ideas, and let them inspire you to craft your own unforgettable story</p>
          <button class="button">Explore our blogs</button>
        </div>
    </section>
    <section class="Popular-blogs" id="popular">
      <h2>Popular blogs</h2>
      <div class="blogs-container">  
            <?php
                include('db.php');

                $sql = "SELECT  id, title, image, likes FROM articles ORDER BY likes DESC LIMIT 3";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                while($data = $result->fetch_assoc()){
                  $article_id = $data['id'];
            ?>
                  <div class="blog-card">
                      <div class="blog-image">
                          <img src="<?php echo htmlspecialchars($data['image']); ?>" alt="blog Image">
                      </div>
                      <div class="blog-content">
                          <div class="blog-title"><?php echo htmlspecialchars($data['title']); ?></div>
                      </div>
                  </div>
            <?php 
                }
            ?>

           </div>
           <?php
                 $conn->close();
            ?>
    </section>

  
    <section class="Popular-blogs" id="allBlogs">
            <h2>All Blogs</h2>
      <div class="allblogs-container">
                
  <?php
     include('db.php');

          $sql = "SELECT a.id, a.title, a.content, a.image, a.likes, a.views, a.created_at, au.username FROM articles AS a 
                  INNER JOIN author AS au ON a.author_id = au.id";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->get_result();
          while ($data_article = $result->fetch_assoc()) {

          $article_id = $data_article['id'];
          // ----------------------------------------------------
          $sql_comments = "SELECT COUNT(*) AS count_comment FROM comments WHERE article_id = ?";
          $stmt_comments = $conn->prepare($sql_comments);
          $stmt_comments->bind_param("i", $article_id);
          $stmt_comments->execute();
          $result_comments = $stmt_comments->get_result();
          $count_comment = $result_comments->fetch_assoc()['count_comment'];
      
          if ($_GET['viewArticleSelected'] = $article_id) {
              $query = "UPDATE articles SET views = views + 1 WHERE id = ?";
              $stmt = $conn->prepare($query);
              $stmt->bind_param("i", $article_id);
              $stmt->execute();
        }
  ?>

          <div class="allBlog-item">
              <div class="square">
                  <img src="<?php echo htmlspecialchars($data_article['image']); ?>" class="blogImage" alt="Article Image">

                  <div class="titleBlogBox">
                      <div class="titleBlog"><?php echo htmlspecialchars($data_article['title']); ?></div>
                      <div class="iconAime">
                          <i class="fa-solid fa-heart like" data-articleId="<?php echo $article_id; ?>"></i>
                          <span id="aimeCount"><?php echo $data_article['likes']; ?></span>
                      </div>
                  </div>

                  <div class="more display">
                      <div class="author-date">
                          <span class="author">By <?php echo htmlspecialchars($data_article['username']); ?></span>
                          <span class="date">Published: <?php echo date('M d, Y', strtotime($data_article['created_at'])); ?></span>
                          <input type="hidden" id="viewArticleSelected" name="viewArticle_id" value="<?php echo $article_id; ?>">
                          <p id="view">Views: <?php echo $data_article['views']; ?></p>
                      </div>
                      <div class="comments">
                          <span class="commentCount"><?php echo $count_comment; ?> Comments</span>
                          <button class="commentButton" data-articleId="<?php echo $article_id; ?>" name="addcomment">Add Comment</button>
                      </div>
                  </div>
                  <p class="contentBlog"><?php echo nl2br(htmlspecialchars($data_article['content'])); ?></p>
                  <button class="buttonReadMore" id="buttonReadMore" data-articleId="<?php echo $article_id; ?>">Read More</button>
          
              </div>
          </div>
      
          <?php
        }
          $conn->close();
      ?>

      </div>
    
        </section>
<?php
        include('db.php');
        if (isset($_POST['sendComment'])) {
                $comment = $_POST['comment'];
                $name = $_POST['nameUser']; 
                $articleId = $_POST['article_id'];
                
                $stmt = $conn->prepare("INSERT INTO visitors (username) VALUES (?)");
                $stmt->bind_param("s", $name);
                $stmt->execute();
                $visitorId = $stmt->insert_id; 
                $stmt->close();

                $comment = htmlspecialchars($comment);
                $sql = "INSERT INTO comments (content, article_id, visitor_id) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sii", $comment, $articleId, $visitorId);
                $stmt->execute();
                $conn->close();   
            }
        
?>
        <div class="formComment" id="formComment" style="display:none">

            <span class="material-icons-outlined close" id="closeCmt">close</span>
              <div class="comments-container">
                <h3>Comments</h3>
                <div class="comments-section">
                  <!-- -------------------- comments ----------------------- -->
                  <?php
                    include('db.php');
                    $sql = "SELECT v.username, c.content, c.created_at 
                            FROM comments AS c 
                            INNER JOIN visitors AS v ON c.visitor_id = v.id 
                            WHERE c.article_id = ? 
                            ORDER BY c.created_at DESC";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $articleId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($dataComment = $result->fetch_assoc()) {
                            echo '<div class="comment">';
                            echo '<p><strong>' . htmlspecialchars($dataComment['username']) . ':</strong> ' . htmlspecialchars($dataComment['content']) . '</p>';
                            echo '<p class="comment-date">' . date('M d, Y H:i', strtotime($dataComment['created_at'])) . '</p>';
                            echo '</div>';
                        }
                      }
                    $stmt->close();
                    $conn->close();
                ?>
              
              </div>
                
              </div>

              <form action="" method="POST">  
                <textarea class="inputComment" name="comment" placeholder="Write your comment here..." required></textarea>
                <div>
                  <input type="text" class="inputComment nameInput" name="nameUser" placeholder="Your name" required>
                  <input type="hidden" id="idArticleSelected" name="article_id" value="<?php echo $article_id; ?>">
                  <button type="submit" name="sendComment" class="addcommentBtn">Add comment</button>
                </div>
              </form>
        
        </div>
       

        <footer>
          <div class="footer-container">
              <div class="footer-left">
                  <h2><span class="title">Blog </span>Press</h2>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas beatae ea, aliquam vel dolores esse voluptas doloribus neque maiores! Dolore praesentium quod laboriosam culpa modi maiores. Nobis laudantium tempore eligendi.
                  </p>
                  <p class="contact-info">
                      &#9742; 123-456-789 <br>
                      &#9993; info@mywebsite.com
                  </p>
                  <div class="social-icons">
                      <a href="#"><i class="fab fa-facebook"></i></a>
                      <a href="#"><i class="fab fa-instagram"></i></a>
                      <a href="#"><i class="fab fa-twitter"></i></a>
                      <a href="#"><i class="fab fa-youtube"></i></a>
                  </div>
              </div>
              
              <div class="footer-middle">
                  <h3> links</h3>
                  <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Articles</a></li>
                    <li><a href="#">Popular</a></li>
                    <li><a href="#">Terms and conditions</a></li>
                  </ul>
              </div>

              <div class="footer-right">
                  <h3>Contact Us</h3>
                  <form>
                      <input type="email"  placeholder="Your email address" required>
                      <textarea placeholder="Message..."  rows="3"></textarea>
                      <button type="submit">Send</button>
                  </form>
              </div>
          </div>
      </footer>
      <script>

      let Login = document.getElementById("LoginBtn");
          Login.addEventListener("click", () => {
          window.location.href = "signup.php";      
        });
      
        let buttonReadMore = document.querySelectorAll('.buttonReadMore'); 
        let viewArticleSelected = document.getElementById('viewArticleSelected');

        buttonReadMore.forEach((btn) => {
            btn.addEventListener('click', (e) => {
  
                let text = e.target.closest('.allBlog-item').querySelector('.contentBlog'); 
                let more = e.target.closest('.allBlog-item').querySelector('.more'); 
                if (text.classList.contains('expanded')) { 
                    text.classList.remove('expanded');
                    more.classList.add('display');
                    e.target.textContent = 'Read More';
  
                } else {
                    text.classList.add('expanded');
                    more.classList.remove('display');
                    e.target.textContent = 'Read Less'; 
                    console.log(btn.getAttribute('data-articleId'));
                    viewArticleSelected.value = btn.getAttribute('data-articleId');
                }
            });
        });
        let commentButtons = document.querySelectorAll('.commentButton');
        let commentForm = document.getElementById('formComment');
        let idArticleSelected = document.getElementById('idArticleSelected');
            commentButtons.forEach((btn) => {
                    
                      btn.addEventListener('click', () => {  
                        let articleId = btn.getAttribute('data-articleId');
                         console.log(articleId);
                         idArticleSelected.value = articleId;
                         commentForm.style.display = 'block';     
                      });
                    });

      let closeCmt = document.getElementById('closeCmt');
      closeCmt.addEventListener('click',()=>{
        commentForm.style.display = 'none';  
      })

    let  openIcon = document.querySelector('.openIcon');
    let  closeIcon = document.querySelector('.closeIcon');
         closeIcon.style.display = 'none';

         function toggleSidebar() {
    let sidebar = document.getElementById('sidebar_'); 
          
            sidebar.classList.toggle('active'); 

            if (sidebar.classList.contains('active')) { 
                openIcon.style.display = 'none'; 
                closeIcon.style.display = 'block'; 
            } else {
                openIcon.style.display = 'block'; 
                closeIcon.style.display = 'none'; 
            }
        }


     </script>
  </body>
  </html>

  


