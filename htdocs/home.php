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
      <div class="menu-contacts">
        
      </div>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
