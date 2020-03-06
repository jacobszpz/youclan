<?php
  $current_page = "index";

  $file_root = substr(__FILE__, 0, strpos(__FILE__, 'htdocs') + 7);
  include "{$file_root}templates/php_init.php";

  // Do not show page if user is already verified or not logged in
  if ($loggedIn) {
    if ($Lost_Session) {
      header("location: /password/new.php");
      exit;
    } else if (!$Verified_Session) {
      header("location: /account/verify.php");
      exit;
    } else if (!$Setup_Session) {
      header("location: /account/setup.php");
      exit;
    } else {
      header("location: /home.php");
      exit;
    }
  }
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="ltr">
  <head>
    <?php include "{$file_root}templates/head.php"; ?>
    <script src="/scripts/index.js"></script>
  </head>
  <body onscroll="showCounter()">
    <header>
      <?php include "{$file_root}templates/header.php"; ?>
    </header>
    <main class="main-no-padding">
      <div class="main-hero">
        <div class="hero-hover">
          <h2 class=""><?php echo $main_strings['website_slogan']; ?></h2>
          <img class="main-leaves" src="/assets/leaves.png" alt="">
          <a class="no-underline" href="/account/registration.php">
            <div class="header-registration">
              <?php echo $main_strings['register_title']; ?>
            </div>
          </a>
        </div>
      </div>
      <div class="main-counter">
        <h2 id="main-counter">999</h2>
        <span class="">students can't be wrong</span>
      </div>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
