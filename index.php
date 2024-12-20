<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    
    <title>BlogPress</title>
</head>
<body>
<header>
          <nav>
            <div class="logo"><span>Blog</span> Press</div>
            <div class="links">
                <ul>
                    <li> <a href="#">Home</a> </li>
                    <li> <a href="#">Articles</a> </li>
                    <li> <a href="#">Popular</a> </li>
                    <li> <a href="#">categories</a></li>
                </ul>
                <button class="btn" id="LoginBtn" type="submit">Join Us</button>
          
            </div>
           
        </nav>
    </header>
<!-- ------------------------------------------------------------------------------------------------- -->

<!-- ------------------------------------------------------------------------------------------------- -->
      <section class="hero">
        <div class="main">
          <h1>The place where words come to life and ideas take flight</h1>
          <p>Discover our articles, uncover unique ideas, and let them inspire you to craft your own unforgettable story</p>
          <button class="button">Explore our blogs</button>
        </div>
    </section>
    <section class="Popular-blogs">
      <h2>Popular blogs</h2>
      <div class="blogs-container">
          <div class="blogs">
        
                <div class="blog-card">
                    <div class="blog-image">
                        <img src="./images/img1.jpg" alt="blog Image">
                    </div>
                    <div class="blog-content">
                        <div class="blog-title">10 Tips to Stay Productive All Day</div>
                    </div>
                </div>

                <div class="blog-card">
                    <div class="blog-image">
                        <img src="./images/img3.jpg" alt="blog Image">
                    </div>
                    <div class="blog-content">
                      <div class="blog-title">5 Habits of Highly Successful People</div>
                    </div>
                </div>
          
                <div class="blog-card">
                    <div class="blog-image">
                        <img src="./images/img4.jpg" alt="blog Image">
                    </div>
                    <div class="blog-content">
                        <div class="blog-title">Save Time, Achieve Your Goals!</div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
    </section>
  

    <section class="Popular-blogs">
            <h2>All Blogs</h2>
            <?php 
            include('db.php');

            if ($conn) {

                $stmt = $conn->prepare("SELECT * FROM articles AS art INNER JOIN author AS auth ON art.author_id = auth.id;");
                
                if ($stmt) {
                    $stmt->execute();
                    $result = $stmt->get_result();
            
                    if ($result->num_rows > 0) {
                        while ($data = $result->fetch_assoc()) {
                            echo "<img src='" . htmlspecialchars($data['image']) . "' alt='Image du post' class='post-image'> ";
                            echo "<h1 class='post-title'>" . htmlspecialchars($data['title']) . "</h1> ";
                            echo "<div class='post-desc'>
                                      <span class='author'>Par <strong>" . htmlspecialchars($data['username']) . "</strong></span>
                                      <span class='date'>Publié " . htmlspecialchars($data['created_at']) . "</span>
                                  </div>";
                        }
                    } else {
                        echo "<p>Aucun article trouvé.</p>";
                    }
            
                    $stmt->close();
                } else {
                    echo "<p>Erreur lors de la préparation de la requête.</p>";
                }
            
                $conn->close();
            } else {
                echo "<p>Erreur de connexion à la base de données.</p>";
            }
            
            ?>
            <div class="post-container">
                    <div class="post-header">
                        <img src="./images/img2.jpg" alt="Image du post" class="post-image">
                        <h1 class="post-title">10 Tips to Stay Productive All Day</h1>
                        <div class="post-desc">
                            <span class="author">Par <strong>John Doe</strong></span>
                            <span class="date">Publié le 20 décembre 2024</span>
                        </div>
                    </div>
                    
                    <div class="post-body">
                        <p class="post-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid eum culpa voluptatibus aliquam ratione sit quas magni placeat enim quibusdam, est officiis odit porro, quasi id et assumenda incidunt minima Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione consequuntur ad cupiditate cumque blanditiis, fugiat nobis porro, obcaecati, reprehenderit mollitia provident ipsum non dolores et magnam. Nesciunt accusamus perspiciatis nam repellendus hic ratione reiciendis dignissimos, autem, cum explicabo reprehenderit deleniti aliquid similique totam numquam, deserunt nihil eos consectetur iste dolorem. Nobis, consectetur. Sed placeat cumque aut esse qui asperiores! Incidunt aut iste at aliquam odit labore ex consequuntur maiores ullam dolores voluptatem iusto odio nihil eos sapiente, nulla nam qui pariatur adipisci, vero eligendi deleniti necessitatibus tempora. Culpa est rerum error velit, quo illo deleniti, maiores nobis, atque fuga qui!
                        </p>
                    </div>

                    <div class="post-footer">
                        <div class="like_comt">
                            <span class="like-icon">
                                <i class="fa fa-thumbs-up"></i> 0 J'aime
                            </span>
                            <span class="comment-icon">
                                <i class="fa fa-comment"></i> Commenter
                            </span>
                        </div>
                    </div>

                    <div class="comments">
                      
                    </div>
             </div>

            <div class="post-container">
                    <div class="post-header">
                        <img src="./images/img3.jpg" alt="Image du post" class="post-image">
                        <h1 class="post-title">10 Tips to Stay Productive All Day</h1>
                        <div class="post-desc">
                            <span class="author">Par <strong>John Doe</strong></span>
                            <span class="date">Publié le 20 décembre 2024</span>
                        </div>
                    </div>
                    
                    <div class="post-body">
                        <p class="post-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid eum culpa voluptatibus aliquam ratione sit quas magni placeat enim quibusdam, est officiis odit porro, quasi id et assumenda incidunt minima Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione consequuntur ad cupiditate cumque blanditiis, fugiat nobis porro, obcaecati, reprehenderit mollitia provident ipsum non dolores et magnam. Nesciunt accusamus perspiciatis nam repellendus hic ratione reiciendis dignissimos, autem, cum explicabo reprehenderit deleniti aliquid similique totam numquam, deserunt nihil eos consectetur iste dolorem. Nobis, consectetur. Sed placeat cumque aut esse qui asperiores! Incidunt aut iste at aliquam odit labore ex consequuntur maiores ullam dolores voluptatem iusto odio nihil eos sapiente, nulla nam qui pariatur adipisci, vero eligendi deleniti necessitatibus tempora. Culpa est rerum error velit, quo illo deleniti, maiores nobis, atque fuga qui!
                        </p>
                    </div>

                    <div class="post-footer">
                        <div class="like_comt">
                            <span class="like-icon">
                                <i class="fa fa-thumbs-up"></i> 0 J'aime
                            </span>
                            <span class="comment-icon">
                                <i class="fa fa-comment"></i> Commenter
                            </span>
                        </div>
                    </div>

                    <div class="comments">
                      
                    </div>
             </div>
        </section>

      

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
                      <input type="email" placeholder="Your email address" required>
                      <textarea placeholder="Message..." rows="3"></textarea>
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
     </script>
  </body>
  </html>

  


