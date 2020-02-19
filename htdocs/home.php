<?php
  $current_page = "home";

  function getRoot($DIR) {
    $path_parts = explode('htdocs', $DIR);
    $path_deep = substr_count($path_parts[1], "/");
    $file_root = "";

    for ($i=0; $i < $path_deep; $i++) {
      $file_root .= "../";
    }

    return $file_root;
  }

  $file_root = getRoot(__DIR__);

  include "{$file_root}templates/php_init.php";

  // Do not show page if user is already verified or not logged in
  if (!$loggedIn) {
    header("location: {$file_root}login");
    exit;
  } else {
    if ($Lost_Session) {
      header("location: {$file_root}password/new.php");
      exit;
    } else if (!$Verified_Session) {
      header("location: {$file_root}account/verify.php");
      exit;
    } else if (!$Setup_Session) {
      header("location: {$file_root}account/setup.php");
      exit;
    }
  }

  require "{$file_root}objects/post.php";
  require "{$file_root}objects/contact.php";

  require_once "{$file_root}database/config.php";
  require_once "{$file_root}database/conn.php";

  $contactArray = [];
  $postArray = [];

  $getPostsQuery = "SELECT ps.*,
  CONCAT(us.Name, ' ', us.Surnames) AS Author,
  us.Username AS Username,
  up.Filename AS PostPicture,
  ul.Filename AS ProfilePicture
  FROM posts AS ps LEFT JOIN users as us ON (ps.PosterID = us.ID)
  LEFT JOIN uploads AS up ON (ps.ImageID = up.ID)
  LEFT JOIN uploads AS ul ON (us.ProfilePicture = ul.ID)
  ORDER BY PostTime DESC";

  $Query_SQL = mysqli_query($Connection_SQL, $getPostsQuery);

  if($Query_SQL) {
    while ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)) {
      $post = new Post;

      $post->id = $Row_SQL["ID"];
      $post->author = $Row_SQL["Author"];
      $post->authorUN = $Row_SQL["Username"];
      $post->authorPicture = $Row_SQL["ProfilePicture"];

      $post->content = $Row_SQL["Content"];
      $post->picture = $Row_SQL["PostPicture"];
      $post->time = $Row_SQL["PostTime"];
      $post->roses = $Row_SQL["Roses"];

      $postArray[] = $post;
    }

    mysqli_free_result($Query_SQL);
  }

  $getUsersQuery = "SELECT u.Username, u.Name, u.Surnames, p.Filename FROM users AS u
  LEFT JOIN uploads AS p ON (u.ProfilePicture = p.ID)";

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
    mysqli_close($Connection_SQL);
  }


?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="ltr">
  <head>
    <?php include "{$file_root}templates/head.php"; ?>
    <script src="<?php print $file_root; ?>scripts/home.js"></script>
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
                  print $contactObj->createContactHTML($file_root, "uploads/");
                }
              ?>
              <li>
                <a href="<?php echo $file_root; ?>">
                  <div class="contact-instance">
                    <img class="contact-image" src="<?php echo $file_root; ?>assets/defaultProfile.svg" alt="">
                    <span class="contact-name">Braulio Pérez</span>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="unselectable menu-close">
          <img id="sidebar-close-arrow" src="<?php echo $file_root; ?>assets/icons/arrow_dropdown_white.svg" alt="">
        </div>
      </div>
      <div class="home-feed">
        <div class="new-post">
          <form id="new-post-form" class="new-post-form" action="<?php echo $file_root; ?>ajax/new_post.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="12582912">
            <div class="new-post-inside">
              <h2 class="new-post-title">Share New Post</h2>
              <textarea id="new-post-textarea" name="post_text" rows="6" placeholder="Tell the world..." required></textarea>
              <div class="new-post-buttons">
                <img id="post-picture-preview" src="" alt="">
                <div class="new-post-picture new-post-button">
                  <img src="<?php echo $file_root; ?>assets/icons/add_photo.svg" alt="">
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
                print $postObj->createPostHTML($file_root, "uploads/");
              }
            ?>
            <li>
              <div class="post">
                <div class="post-info">
                  <img class="post-user-img" src="<?php echo $file_root; ?>assets/defaultProfile.svg" alt="">
                  <div class="post-user-info">
                    <span class="post-user-name"><a href="#">Braulio Pérez</a></span>
                    <span class="post-time">2 hours</span>
                  </div>
                </div>
                <div class="post-content">
                  <div class="post-text">
                    <span>What the fuck did you just fucking say about me, you little bitch?
                      I'll have you know I graduated top of my class in the Navy Seals,
                      and I've been involved in numerous secret raids on Al-Quaeda,
                      and I have over 300 confirmed kills. I am trained in gorilla warfare
                      and I'm the top sniper in the entire US armed forces. You are nothing
                      to me but just another target. I will wipe you the fuck out with precision
                      the likes of which has never been seen before on this Earth, mark my fucking words.
                      You think you can get away with saying that shit to me over the Internet?
                      Think again, fucker. As we speak I am contacting my secret network of
                      spies across the USA and your IP is being traced right now so you better prepare for the storm, maggot.
                      The storm that wipes out the pathetic little thing you call your life.
                      You're fucking dead, kid. I can be anywhere, anytime, and I can kill you in over seven hundred ways,
                      and that's just with my bare hands. Not only am I extensively trained in unarmed combat,
                      but I have access to the entire arsenal of the United States Marine Corps and
                      I will use it to its full extent to wipe your miserable ass off the face of the continent,
                      you little shit. If only you could have known what unholy retribution your little "clever"
                      comment was about to bring down upon you, maybe you would have held your fucking tongue.
                      But you couldn't, you didn't, and now you're paying the price, you goddamn idiot.
                      I will shit fury all over you and you will drown in it. You're fucking dead, kiddo.</span>
                  </div>
                  <div class="post-image">
                    <a href="#">
                      <img class="post-img" src="<?php echo $file_root; ?>assets/defaultPost.jpg" alt="">
                    </a>
                  </div>
                  <div class="post-roses">
                    <img class="post-rose-icon" src="<?php echo $file_root; ?>assets/icons/rose.svg" alt="">
                    <span class="post-roses-no">90</span>
                  </div>
                </div>
                <div class="post-comments">
                  <span class="comments-title">COMMENTS</span>
                  <div class="comment">
                    <div class="comment-inside">
                      <img class="comment-user-img" src="<?php echo $file_root; ?>assets/defaultProfile.svg" alt="">
                      <div class="comment-body">
                        <div class="comment-content">
                          <span class="comment-author"><a href="#">Braulio Pérez</a></span>
                          <span class="comment-text">Hello, world</span>
                        </div>
                        <div class="comment-info">
                          <span class="comment-time">3 minutes</span>
                          <a href="#">
                            <div class="comment-roses">
                              <img class="comment-rose-icon" src="<?php echo $file_root; ?>assets/icons/rose.svg" alt="">
                              <span class="comment-roses-no">9</span>
                            </div>
                          </a>
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
