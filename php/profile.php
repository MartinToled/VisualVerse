<?php
session_start();
require 'connect.php';
// Check if the user is logged in
if (!isset($_SESSION['id'])) {
  header("Location: ../index.php"); // Redirect to the index page if not logged in
  exit();
}

?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/VV.png">
        <link rel="stylesheet" href="../scss-css/profile.css">
        <title>My Profile</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/3a5712755a.js" crossorigin="anonymous"></script>
        <script src="../js/modal.js"></script>
    <script src="../js/showin-pic.js"></script>
    <script src="../js/like-unlike.js"></script>
    <script src="../js/show-more-less.js"></script>
    <script src="../js/video-control.js"></script>
    <script src="../js/edit-post.js"></script>
    <script src="../js/kebab.js"></script>
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
            <li><a href="../inc/logout-includes.php" style="color:#46CDCF"><span>Logout</span></a></li>
        </ul>
        </section>

        <!-- Display current user's information and picture -->
        <?php
            $prof_id = $_SESSION['id'];

            // prepare a SQL statement to select all media files uploaded by the user
            $prof = $conn->prepare("SELECT * FROM users WHERE id= :prof_id");
            $prof->bindParam(':prof_id', $prof_id);
            $prof->execute();
            while ($disp = $prof->fetch(PDO::FETCH_ASSOC)) {
                $prof_pic = $disp['pfp'];
                $file_prof = strtolower(pathinfo($prof_pic, PATHINFO_EXTENSION));
                $header_pic = $disp['header'];
                $file_prof = strtolower(pathinfo($header_pic, PATHINFO_EXTENSION));
        ?> 
        <div id="main">
        <?php 
        // Check if error parameter exists in the URL and display appropriate error message
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            if ($error == "errordelpost") {
                echo '<p class="error-msg">Could not delete post.</p>';
            }
        }
        ?>
        <!-- Modal for displaying full-size image -->
        <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="imgModal">
        <div id="caption"></div>
        </div>
        <div id="profile">
          <div id="upper_images">
            <img id="header" src="<?=$header_pic?>" >  <!-- Display header picture -->
            <img id="pfp"src="<?=$prof_pic?>" alt=<?=$disp['pfp']?>> <!-- Display profile picture -->
          </div>
          <!-- Display user's name, username, ID, and bio -->
            <div id="user_info">
              <div id="indexes">
                <h1><?=$disp['firstname']?> <?=$disp['lastname']?></h1>
                <div id="kebab-menu" class="kebab-menu">
                  <div class="dot"></div>
                  <div class="dot"></div>
                  <div class="dot"></div>
                  <div class="menu-content">
                    <a href="edit-profile.php" style="color:white">Edit Profile</a>
                    <a href="password-reset.php" style="color:white;">Reset Password</a>
                    <a href="delete-acc.php" style="color:#46CDCF">Delete Account</a>
                  </div>
                </div>
              </div>
              <div id="indexes">
              <h3 id="username">@<?=$disp['username'] ?></h3>
              <h3 id="profile_id">#<?=$disp['id'] ?></h3>
              </div>
              <br>
              <h2>Bio</h2>
              <p><?=$disp['bio']?></p>
            </div>
        </div>          
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>

        <?php
            };
        ?>
         <!-- Fetch and display current user's posts-->
        <?php
            $user_id = $_SESSION['id'];

            // prepare a SQL statement to select all media files uploaded by the user
            $stmt = $conn->prepare("SELECT * FROM uploads INNER JOIN users ON uploads.user_id = users.id WHERE users.id = :user_id ORDER BY uploads.uploads_id DESC");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            // display the media files
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $file_name = $row['images'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $tags = json_decode($row['tag'], true);  // Convert the comma-separated string back to an array
        ?>
         <!-- Display post information -->
            <div id="Posts">
                <div id="edit-button">
                  <button class="edit-post" data-postid="<?=$row['uploads_id']?>"><i class="fas fa-edit"></i></button>
                </div>

                <div id="delete-button">
                <form class="delete-post-form" action="../inc/delete-post.php" method="POST">
                  <input type="hidden" name="postid" value="<?=$row['uploads_id']?>">
                  <button class="delete-post" type="submit"><i class="fas fa-trash"></i></button>
                </form>
                </div>

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
                        <video id="vid" muted autoplay><source src='../uploads/<?=$file_name?>' type='video/<?=$file_extension?>'></video>
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
                        <img class="post-img" width=600 src="../uploads/<?=$file_name?>" alt='{$file_name}'>
                        <?php } 
                    ?>
                <br>
                <div id="bottom_part">
                    <div id="comment-in">
                        <!-- Form for submitting comments -->
                    <form method="post" action="../inc/comments-includes.php">
                        <input type="hidden" name="post_id" value="<?php echo $row['uploads_id']; ?>">
                        <input type="text" name="comment" id="comment" placeholder="Comment.." autocomplete="off" style="color: white"></input>
                        <button id="submit_comment" type="submit" placeholder="" name="submit_comment"> <i class="fa-regular fa-paper-plane" style="color:#28AFB0;"></i></button>
                    </form>
                    </div>
                    <?php
                    
                    $liked = $conn->query("SELECT * FROM likes WHERE liked_user =".$_SESSION['id']." AND post_like=".$row['uploads_id']."");
                    if($liked->rowCount() == 1){?>
                        <div id="buttonliked">
                            <img style='height:2.7vh;display:flex;' class="unlike" id="<?=$row['uploads_id']?>" src='../images/filled.png'></img> &nbsp;
                            <span style="color:white;"><?=$row['likes_count']?></span>
                        </div>
                        <?php
                    }else{ ?>
                        <div id="buttonunliked">
                        <img  style='height:2.7vh;display:flex;' class="like" id="<?=$row['uploads_id']?>"src='../images/unfilled.png'></img> &nbsp;
                        <span style="color:white;"><?=$row['likes_count']?></span>
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
                      <!-- Display "Show More" button if there are more comments to show and also "Show Less" if it is wanted to be closed -->
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
                ?>   
        </div>    
        <script src="../js/delete-post.js"></script>
        </body>
    
    </html>