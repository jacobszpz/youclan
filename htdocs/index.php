<?php
  $current_page = "index";

  $path_parts = explode('htdocs', __DIR__);
  $path_deep = substr_count($path_parts[1], "/");
  $file_root = "";

  for ($i=0; $i < $path_deep; $i++) {
    $file_root .= "../";
  }

  include "{$file_root}templates/php_init.php";
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="ltr">
  <head>
    <?php include "{$file_root}templates/head.php"; ?>
    <script src="<?php print $file_root; ?>scripts/index.js"></script>
  </head>
  <body>
    <header>
      <?php include "{$file_root}templates/header.php"; ?>
    </header>
    <main class="main-no-padding">
      <div class="main-hero">
        <div class="hero-hover">
          <h2 class="">Meet friends at uni</h2>
          <img class="main-leaves" src="<?php echo $file_root; ?>assets/leaves.png" alt="">
          <a class="no-underline" href="<?php echo $file_root; ?>account/registration.php">
            <div class="header-registration">
              Create an account
            </div>
          </a>
        </div>
      </div>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
