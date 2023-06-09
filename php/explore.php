<?php
session_start();
require 'connect.php';
if (!isset($_SESSION['id'])) {
  header("Location: ../index.php");
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/VV.png">
    <link rel="stylesheet" href="../scss-css/explore.css">
    <title>Explore Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/3a5712755a.js" crossorigin="anonymous"></script>
    <script src="../js/modal.js"></script>
    <script src="../js/showin-pic.js"></script>
    <script src="../js/like-unlike.js"></script>
    <script src="../js/show-more-less.js"></script>
    <script src="../js/video-control.js"></script>
    <script src="../js/tag-button.js"></script>
</head>

<body>
    <!--Nav bar-->
    <section id="top-nav">
        <div class="logo">
            <img src="../images/VV.png" />
            <a href="home.php">VisualVerse</a>
        </div>
        <input id="burger-toggle" type="checkbox" />
        <label class='burger-button-container' for="burger-toggle">
            <div class='burger-button'></div><!--This will be the Hamburger Icon-->
        </label>
        <ul class="menu">
            <li><a href="home.php">Home</a></li>
            <li><a href="explore.php">Explore</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="../includes/logout-includes.php" style="color:#46CDCF"><span>Logout</span></a></li>
        </ul>
    </section>

    <div id="main">

        <!-- Content on the explore page -->
        <div id="explore-content">
            <h1>Explore</h1>

            <!-- Search form -->
            <div id = "search">
                <form id="forms" action="" method="GET">
                    <input id="searchbar" type="text" name="search_query" placeholder="Search...">
                    <input id="enter" type="submit" value="Search">
                </form>
                <div id="tags">
                  <button class="tag-button" data-tag="music">MUSIC</button>
                  <button class="tag-button" data-tag="art">ART</button>
                  <button class="tag-button" data-tag="movie">MOVIE</button>
                  <button class="tag-button" data-tag="anime">ANIME</button>
                  <button class="tag-button" data-tag="kpop">KPOP</button>
                  <button class="tag-button" data-tag="sports">SPORTS</button>
                  <button class="tag-button" data-tag="technology">TECHNOLOGY</button>
                  <button class="tag-button" data-tag="blog">BLOG</button>
              </div>
            </div>
           
            <?php
    // Retrieve the tag from the button clicked
    if (isset($_GET['tag'])) {
      $clickedTag = $_GET['tag'];
      
      // Prepare the SQL query with the clicked tag
      $stmt = $conn->prepare("SELECT * FROM uploads WHERE tag LIKE :tag ORDER BY uploads_id DESC");
      $stmt->bindValue(':tag', '%' . $clickedTag . '%');
      $stmt->execute();
    }

               // Check if search query is submitted
    elseif (isset($_GET['search_query'])) {
        $search_query = $_GET['search_query'];

        // Retrieve posts from the database based on the search query
        $stmt = $conn->prepare('SELECT * FROM uploads WHERE caption LIKE :search ORDER BY uploads_id DESC');
        $stmt->bindValue(':search', '%' . $search_query . '%');
        $stmt->execute();
    } else {
        // Retrieve all posts from the database
        $stmt = $conn->prepare('SELECT * FROM uploads ORDER BY RAND()');
        $stmt->execute();
    }

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $file_name = $row['name'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $tags = json_decode($row['tag'], true);  // Convert the comma-separated string back to an array
?>
        <div id="myModal" class="modal">
        <img class="modal-content" id="imgModal">
        <span class="close">&times;</span>
        <div id="caption"></div>
        </div>
            <div id="Posts">
                <div id="media">
                    <div id="username"><h3><?=$row['user_name']?></h3></div>
                    <div id="caption"><p><?=$row['caption']?></p></div>
                    <div id = "tag-contain">
                <?php 
                     if (!is_null($tags)) {
                      echo '<span id="headtag">Tags: &nbsp</span>';
                      foreach ($tags as $tag) {
                          echo '<span id="tag"> | ' . $tag . ' |  &nbsp</span>';
                      }
                  }
                   ?>
                   </div>
                   <?php
                        if ($file_extension == 'mp4' || $file_extension == 'webm') {?>
                        <div class="video-container">
                        <video id="vid" muted autoplay><source src='uploads/<?=$file_name?>' type='video/<?=$file_extension?>'></video>
                          <div class="controls">
                                <button class="playButton"><i class="fas fa-pause"></i></button>
                                <input class="seek-bar" type="range" min="0" max="100" value="0">

                                <div class="volume-container">
                                <input type="range" class="volumeControl" min="0" max="1" step="0.01" value="1">
                                </div>
                                <button class="muteButton"><i class="fas fa-volume-off"></i></button>
                                <button id="fullscreenButton" onclick="toggleFullscreen()"><i class="fas fa-expand"></i></button>
                            </div>
                          </div>
                       <?php } 
                        elseif ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png' || $file_extension == 'gif'|| $file_extension == 'webp') {?>
                        <img class="post-img" width=600 src="uploads/<?=$file_name?>" alt='{$file_name}'>
                        <?php } 
                    ?>
                    <br>
                    <div id="bottom_part">
                    <div id="comment-in">
                    <form method="post" action="../inc/comments-includes.php">
                        <input type="hidden" name="post_id" value="<?php echo $row['uploads_id']; ?>">
                        <input type="text" name="comment" id="comment" placeholder="Comment.." autocomplete="off" style="color:white"></input>
                        <button id="submit_comment" type="submit" placeholder="" name="submit_comment"><i class="fa-regular fa-paper-plane" style="color:#28AFB0;font-size:1.3vw;"></i></button>
                    </form>
                    </div>
                    <?php
                    
                    $liked = $conn->query("SELECT * FROM likes WHERE liked_user = ".$_SESSION['id']." AND post_like = ".$row['uploads_id']."");
                    if($liked->rowCount() == 1) {?>
                       <div id="buttonliked">
                            <img style='height:2.7vh;display:flex;' class="unlike" id="<?=$row['uploads_id']?>" src='../images/filled.png'></img> &nbsp;
                            <span style="color:white"><?=$row['likes_count']?></span>
                        </div>
                        <?php
                    }else{ ?>
                        <div id="buttonunliked">
                        <img  style='height:2.7vh;display:flex;' class="like" id="<?=$row['uploads_id']?>"src='../images/unfilled.png'></img> &nbsp;
                        <span style="color:white"><?=$row['likes_count']?></span>
                         </div>
                   <?php }?>

                   
                    </div>
                    <div id= "comments">
                    <?php
                    $post_id = $row['uploads_id'];

                    // prepare a SQL statement to select all media files uploaded by the user
                    $stmt_comm = $conn->prepare("SELECT comments.comments_id, comments.post_id, comments.comments, comments.user_id, comments.user_name, comments.created_at FROM comments 
                                            INNER JOIN uploads ON comments.post_id = uploads.uploads_id WHERE uploads.uploads_id = :post_id ORDER BY created_at DESC");
                    $stmt_comm->bindParam(':post_id', $post_id);
                    $stmt_comm->execute();

                    $counter = 0;
                        if ($stmt_comm->rowCount() > 0) {
                            while ($result = $stmt_comm->fetch(PDO::FETCH_ASSOC)) {
                                $commentClass = ($counter < 3) ? 'comment' : 'comment hidden-comment';
                        ?>
                                <div class="<?= $commentClass ?>">
                                    <h3><?= $result['user_name'] ?></h3>
                                    <p><?= $result['comments'] ?></p>
                                    <p><?= $result['created_at'] ?></p>
                                </div>
                        <?php
                                $counter++;
                            }
                            if ($stmt_comm->rowCount() > 3) {
                        ?>
                            <div id="showmoreless">
                              <button class="show-more-button"><i class="fa-solid fa-angles-down" style="color:#28AFB0"></i> Show More</button>
                              <button class="show-less-button hidden-comment"><i class="fa-solid fa-angles-up" style="color:#28AFB0"></i> Show Less</button>
                            </div>
                        <?php
                            }
                        }
                        ?>
                         </div>
                    </div>
                </div>   

            <?php
        }
    } else {
        echo '<p id="nopost">No posts found.</p>';
    }
    ?>
        </div>
    </div>
</body>
</html>
