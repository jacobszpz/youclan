<?php
  $current_page = "home";

  $file_root = substr(__FILE__, 0, strpos(__FILE__, 'htdocs') + 7);
  include "{$file_root}templates/php_init.php";

  // Do not show page if user is already verified or not logged in
  if (!$loggedIn) {
    header("location: /login");
    exit;
  } else {
    if ($Lost_Session) {
      header("location: /password/new.php");
      exit;
    } else if (!$Verified_Session) {
      header("location: /account/verify.php");
      exit;
    } else if (!$Setup_Session) {
      header("location: /account/setup.php");
      exit;
    }
  }

  require "{$file_root}objects/post.php";
  require "{$file_root}objects/contact.php";
  require "{$file_root}objects/comment.php";

  require_once "{$file_root}database/config.php";
  require_once "{$file_root}database/conn.php";

  $contactArray = [];
  $postArray = [];

  $getPostsQuery = "SELECT ps.*,
  CONCAT(us.Name, ' ', us.Surnames) AS Author,
  us.Username AS Username,
  up.Filename AS PostPicture,
  ul.Filename AS ProfilePicture
  FROM posts AS ps LEFT JOIN users AS us ON (ps.PosterID = us.ID)
  LEFT JOIN uploads AS up ON (ps.ImageID = up.ID)
  LEFT JOIN uploads AS ul ON (us.ProfilePicture = ul.ID)
  ORDER BY PostTime DESC LIMIT 200";

  $Query_SQL = mysqli_query($Connection_SQL, $getPostsQuery);

  if($Query_SQL) {
    while ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)) {
      $post = new Post;

      $post->authorData($Row_SQL["Username"], $Row_SQL["Author"], $Row_SQL["ProfilePicture"]);
      $post->postData($Row_SQL["ID"], $Row_SQL["Content"], $Row_SQL["PostPicture"], $Row_SQL["PostTime"], $Row_SQL["Roses"]);

      $postArray[] = $post;
    }

    mysqli_free_result($Query_SQL);
  }

  $getUsersQuery = "SELECT u.Username, u.Name, u.Surnames, p.Filename FROM users AS u
  LEFT JOIN uploads AS p ON (u.ProfilePicture = p.ID) ORDER BY RAND() LIMIT 200";

  $Query_SQL = mysqli_query($Connection_SQL, $getUsersQuery);

  if($Query_SQL) {
    while ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)) {
      $contact = new Contact;

      $contact->name = $Row_SQL["Name"];
      $contact->surname = $Row_SQL["Surnames"];
      $contact->picture = $Row_SQL["Filename"];

      $contact->username = $Row_SQL["Username"];

      $contactArray[] = $contact;
    }

    mysqli_free_result($Query_SQL);
    // mysqli_close($Connection_SQL);
  }


?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="ltr">
  <head>
    <?php include "{$file_root}templates/head.php"; ?>
    <script src="/scripts/home.js"></script>
  </head>
  <body>
    <header>
      <?php include "{$file_root}templates/header.php"; ?>
    </header>
    <main class="main-home">
      <div class="menu-sidebar">
        <div class="menu-contacts">
          <h2 id="contacts-title">CONTACTS</h2>
          <div class="contact-list">
            <ul>
              <?php
                foreach ($contactArray as $contactObj) {
                  print $contactObj->createContactHTML("uploads/");
                }
              ?>
              <li>
                <a href="#">
                  <div class="contact-instance">
                    <div class="contact-image-wrapper">
                      <img class="contact-image" src="/assets/defaultProfile.svg" alt="">
                    </div>
                    <span class="contact-name">Fake John</span>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="unselectable menu-close">
          <img id="sidebar-close-arrow" src="/assets/icons/arrow_dropdown_white.svg" alt="">
        </div>
      </div>
      <div class="home-feed">
        <div class="new-post">
          <form id="new-post-form" class="new-post-form" action="/ajax/new_post.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="12582912">
            <div class="new-post-inside">
              <h2 class="new-post-title">Share New Post</h2>
              <textarea id="new-post-textarea" name="post_text" rows="6" placeholder="Tell the world..." required></textarea>
              <div class="new-post-buttons">
                <img id="post-picture-preview" src="" alt="">
                <div class="new-post-picture new-post-button">
                  <img src="/assets/icons/add_photo.svg" alt="">
                  <input id="new-post-picture" type="file" accept="image/*" name="post_picture" class="upload" />
                </div>
                <input id="new-post-submit" class="new-post-button" type="submit" name="" value="Post">
              </div>
            </div>
          </form>
        </div>
        <div id="feed" class="feed">
          <ul>
            <?php
              foreach ($postArray as $postObj) {
                $commentHTML = "";

                $getCommentsQuery = "SELECT cs.*, CONCAT(us.Name, ' ', us.Surnames) AS Author,
                us.Username AS Username, ul.Filename AS ProfilePicture FROM comments AS cs
                LEFT JOIN users as us ON (cs.PosterID = us.ID)
                LEFT JOIN uploads AS ul ON (us.ProfilePicture = ul.ID)
                WHERE ParentID = $postObj->id
                ORDER BY cs.PostTime DESC LIMIT 200";

                $Query_SQL = mysqli_query($Connection_SQL, $getCommentsQuery);

                if($Query_SQL) {
                  while ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)) {
                    $comment = new Comment;

                    $comment->authorData($Row_SQL["Username"], $Row_SQL["Author"], $Row_SQL["ProfilePicture"]);
                    $comment->commentData($Row_SQL["ID"], $Row_SQL["Content"], $Row_SQL["PostTime"], $Row_SQL["Roses"]);

                    $commentHTML .= $comment->createCommentHTML("uploads/");
                  }

                  mysqli_free_result($Query_SQL);
                }

                $postObj->comments = $commentHTML;
                print $postObj->createPostHTML("uploads/");
              }

              mysqli_close($Connection_SQL);
            ?>
            <li>
              <div class="post">
                <div class="post-info">
                  <div class="post-user-img-wrapper">
                    <img class="post-user-img" src="/assets/defaultProfile.svg" alt="">
                  </div>
                  <div class="post-user-info">
                    <span class="post-user-name"><a href="#">Fake John</a></span>
                    <span class="post-time">2 hours</span>
                  </div>
                </div>
                <div class="post-content">
                  <div class="post-text">
                    <span>What did you just say about me?
                      I’ll have you know I graduated top of my class in the Navy Seals, and
                      I’ve been involved in numerous secret raids on Al-Quaeda, and I have
                      over 300 confirmed kills. I am trained in gorilla warfare and I’m the
                      top sniper in the entire US armed forces. You are nothing to me but
                      just another target. I will wipe you out with precision the likes of
                      which has never been seen before on this Earth, mark my words.
                      You think you can get away with saying that to me over the Internet?
                      Think again. As we speak I am contacting my secret network of spies
                      across the USA and your IP is being traced right now so you better
                      prepare for the storm, maggot. The storm that wipes out the pathetic
                      little thing you call your life. You’re dead, kid. I can be anywhere,
                      anytime, and I can kill you in over seven hundred ways, and that’s
                      just with my bare hands. Not only am I extensively trained in unarmed
                      combat, but I have access to the entire arsenal of the United States
                      Marine Corps and I will use it to its full extent to wipe you off the
                      face of the continent. If only you could have known what unholy
                      retribution your little “clever” comment was about to bring down upon
                      you, maybe you would have held your tongue. But you couldn’t, you
                      didn’t, and now you’re paying the price, you idiot. I will rain fury
                      all over you and you will drown in it. You’re dead, kiddo.</span>
                  </div>
                  <div class="post-image">
                    <a href="#">
                      <img class="post-img" src="/assets/defaultPost.jpg" alt="">
                    </a>
                  </div>
                  <div class="post-roses">
                    <img class="post-rose-icon" src="/assets/icons/rose.svg" alt="">
                    <span class="post-roses-no">90</span>
                  </div>
                </div>
                <div class="post-comments">
                  <span class="comments-title">COMMENTS</span>
                  <div class="comment">
                    <div class="comment-inside">
                      <div class="comment-user-img-w">
                        <img class="comment-user-img" src="/assets/defaultProfile.svg" alt="">
                      </div>
                      <div class="comment-body">
                        <div class="comment-content">
                          <span class="comment-author"><a href="#">Fake John</a></span>
                          <span class="comment-text">Hello, world</span>
                        </div>
                        <div class="comment-info">
                          <span class="comment-time">3 minutes</span>
                          <div class="comment-roses">
                            <img class="comment-rose-icon" src="/assets/icons/rose.svg" alt="">
                            <span class="comment-roses-no">9</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="new-comment">
                    <form class="new-comment-form" action="" method="post">
                      <div class="new-comment-inside">
                        <input type="text" class="new-comment-input" name="" value="" placeholder="Add to the conversation..." required>
                        <input type="submit" class="new-post-button new-comment-button" name="" value="SEND">
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
