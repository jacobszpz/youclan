<?php
  $current_page = "new_password";

  $file_root = $_SERVER['DOCUMENT_ROOT'] . '/';
  include "{$file_root}templates/php_init.php";

  // Overwrite Page Title
  $current_title = $main_strings['new_pass_title'];

  $debuggingActivated = FALSE;
  $phpErrorMessage = "Debugging Activated<br>";

  if (!$loggedIn) {
    header("location: /login");
    exit;
  } else {
    if (!$Lost_Session) {
      header("location: /");
      exit;
    }
  }

  $showErrorMessage = FALSE;
  $showDatabaseError = FALSE;
  $showLengthError = FALSE;
  $showMatchError = FALSE;

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $phpErrorMessage .= "Form Method is POST<br>";

    require_once "{$file_root}database/config.php";

    // Set Variables
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
    $password_confirm = trim(filter_input(INPUT_POST, 'password_confirm', FILTER_SANITIZE_STRING));

    if (strlen($password) < 10) {
      $showErrorMessage = TRUE;
      $showLengthError = TRUE;
    } else if ($password !== $password_confirm) {
      $showErrorMessage = TRUE;
      $showMatchError = TRUE;
    }

    // If both variables present
    if (!$showLengthError && !$showMatchError) {
      $phpErrorMessage .= "Passcodes are appropriate<br>";

      require_once "{$file_root}database/conn.php";

      // Check connection
      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";

        $New_Password_Hash = password_hash($password, PASSWORD_DEFAULT);

        // Update User Password Query
        $passcodeUpdate_Query = "UPDATE users SET Password = '$New_Password_Hash' WHERE Username = '$Username_Session'";
        $Query_SQL = mysqli_query($Connection_SQL, $passcodeUpdate_Query);

        if ($Query_SQL) {
          $phpErrorMessage .= "Password Updated";
          $_SESSION['lost_account'] = FALSE;

          header("location: /");
        } else {
          $showDatabaseError = TRUE;
        }
      } else {
        $showDatabaseError = TRUE;
      }
    }
  }

  $errorMessage = "Error";

  # ERROR HANDLING #
  if ($showMatchError) {
    $errorMessage = $main_strings['error_password_match'];
  }

  if ($showLengthError) {
    $errorMessage = $main_strings['error_password_length'];
  }
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="ltr">
  <head>
    <?php include "{$file_root}templates/head.php"; ?>
    <script src="/scripts/login.js"></script>
  </head>
  <body>
    <header>
      <?php include "{$file_root}templates/header.php"; ?>
    </header>
    <main>
      <form class="login-form" action="" method="post">
        <div class="main-center new-pass-center">
          <?php if ($debuggingActivated) {
              echo '<p class="php-debug">' . $phpErrorMessage . '</p>';
          } ?>
          <h1><?php echo $main_strings['account_new_pass']; ?></h1>

          <?php
            if ($showDatabaseError) {
              $errorMessage = $main_strings['error_database_connection'];
            }

            if ($showErrorMessage){
              echo '<span id="login-error-message">' . $errorMessage . '</span>';
            }
          ?>

            <div class="login-form-div">
              <div class="login-username">
                <h4><?php echo $main_strings['account_new_password']; ?></h4>
                <input type="password" name="password" class="login-input set-password" value="" placeholder="" required>
              </div>
              <div class="login-password">
                <h4><?php echo $main_strings['account_np_confirm']; ?></h4>
                <input type="password" id="password-input" name="password_confirm" class="login-input set-password" value="" placeholder="" required>
              </div>
              <div class="password-show">
                <span id="password-show-span"><?php echo $main_strings['password_show']; ?></span>
                <input type="checkbox" name="show" value="" onclick="togglePasswordShow()" class="site-login-checkbox">
              </div>
              <input type="submit" class="header-submit" name="" value="<?php echo $main_strings['account_save']; ?>">
            </div>
        </div>
      </form>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
