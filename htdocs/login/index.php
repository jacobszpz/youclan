<?php
  $current_page = "login";

  $path_parts = explode('htdocs', __DIR__);
  $path_deep = substr_count($path_parts[1], "/");
  $file_root = "";

  for ($i=0; $i < $path_deep; $i++) {
    $file_root .= "../";
  }

  include "{$file_root}templates/php_init.php";

  // Overwrite Page Title
  $current_title = $main_strings['login_title'];

  // Since there is another Login form anyway
  $loginHeader = FALSE;
  $registerHeader = TRUE;

  // To Test Logged In Redirect
  // $loggedIn = TRUE;

  $debuggingActivated = FALSE;
  $phpErrorMessage = "Debugging Activated<br>";

  # USER LOGGED IN REDIRECT #

  if ($loggedIn) {
    header("location: {$file_root}");
    exit;
  }

  $showErrorMessage = FALSE;

  $showUserError = FALSE;
  $showLoginError = FALSE;
  $showDatabaseError = FALSE;

  // Login Function
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $phpErrorMessage .= "Form Method is POST<br>";
    $showLoginError = TRUE;

    require_once "{$file_root}database/config.php";

    // Set Variables
    $Username_Request = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $Password_Request = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    $phpErrorMessage .= "Variables From Request Read<br>";

    # EMPTY REQUEST DETECTION #
    if (!empty($Username_Request) && !empty($Password_Request)) {
      $phpErrorMessage .= "Login is not empty<br>";

      require_once "{$file_root}database/conn.php";

      // Check connection
      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";
        mysqli_set_charset($Connection_SQL, "utf8");

        // Lookup Username in DB
        $userLookup_Query = "SELECT * FROM users WHERE Username = '$Username_Request'";
        $Query_SQL = mysqli_query($Connection_SQL, $userLookup_Query);

        $phpErrorMessage .= "Retrieved Users<br>";

        // Check if username exists, if yes then verify password
        $Rows_Result = mysqli_num_rows($Query_SQL);

        if ($Rows_Result == 1) {
          $phpErrorMessage .= "One User Was Found<br>";

          if ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)) {
            /*
              // Bind result variables
              mysqli_bind_result($Query_SQL, $Username_Result, $Password_Result,
              $Name_Result, $Surnames_Result, $VerifiedAccount_Result,
              $VerifyToken_Result, $LostAccount_Result);

              Fix for new, simpler approach to this shit
            */

            $ID_Result = $Row_SQL['ID'];
            $Username_Result = $Row_SQL['Username'];
            $Password_Result = $Row_SQL['Password'];
            $Name_Result = $Row_SQL['Name'];
            $Surnames_Result = $Row_SQL['Surnames'];
            $VerifiedAccount_Result = $Row_SQL['VerifiedAccount'];
            $VerifyToken_Result = $Row_SQL['VerifyToken'];
            $LostAccount_Result = $Row_SQL['LostAccount'];
            $SetupComplete_Result = $Row_SQL['SetupComplete'];

            $phpErrorMessage .= "Results Fetched<br>";
            $phpErrorMessage .= $NewAccount_Result . "<br>";

            // Check password
            if(password_verify($Password_Request, $Password_Result)){
              $phpErrorMessage .= "Password Matches<br>";

              // Password is correct
              // Store data in session variables
              $_SESSION['user_id'] = $ID_Result;
              $_SESSION['logged_in'] = TRUE;
              $_SESSION['username'] = $Username_Result;
              $_SESSION['name'] = $Name_Result;
              $_SESSION['surnames'] = $Surnames_Result;

              if ($SetupComplete_Result == 0) {
                $_SESSION['setup_account'] = FALSE;
              } else {
                $_SESSION['setup_account'] = TRUE;
              }

              // Account Had Been Lost At Some Point Before
              // User Has Been Able To Log Back In
              // Therefore, Delete Token And Account Flag
              if ($LostAccount_Result == 1) {
                $lostStatusReset_Query = "UPDATE users SET LostAccount = 0, LostToken = NULL WHERE Username = '$Username_Result'";
                $dbLostUpdate = mysqli_query($Connection_SQL, $lostStatusReset_Query);
              }

              if ($VerifiedAccount_Result == 0) {
                $_SESSION['verified'] = FALSE;
                $_SESSION['verify_token'] = $VerifyToken_Result;
                header("location: {$file_root}account/verify.php");
                exit;
              } else {
                $_SESSION['verified'] = TRUE;
                // Redirect user to welcome page
                //header("location: {$file_root}");
                //exit;
              }
            }
          }
        } else if ($Rows_Result == 0) {
          $showLoginError = FALSE;
          $showUserError = TRUE;
        }

        mysqli_close($Connection_SQL);
      } else {
        $showDatabaseError = TRUE;
      }
    }
  }

  if ($showUserError || $showLoginError || $showDatabaseError) {
    $showErrorMessage = TRUE;
  }
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="ltr">
  <head>
    <?php include "{$file_root}templates/head.php"; ?>
    <script src="<?php print $file_root; ?>scripts/login.js"></script>
  </head>
  <body>
    <header>
      <?php include "{$file_root}templates/header.php"; ?>
    </header>
    <main>
      <form class="login-form" action="" method="post">
        <div class="main-center">
          <?php if ($debuggingActivated) {
              echo '<p class="php-debug">' . $phpErrorMessage . '</p>';
          } ?>
          <h1><?php echo $main_strings['account_login']; ?></h1>

          <?php
            $errorMessage = $main_strings['error_account_wrong_login'];

            if ($showUserError) {
              $errorMessage = $main_strings['error_account_no_user'];
            }

            if ($showDatabaseError) {
              $errorMessage = $main_strings['error_database_connection'];
            }

            if ($showErrorMessage){
              echo '<span id="login-error-message">' . $errorMessage . '</span>';
            }
          ?>

            <div class="login-form-div">
              <div class="login-username">
                <h4><?php echo $main_strings['login_user']; ?></h4>
                <input type="text" name="username" class="login-input" value="" placeholder="" required>
              </div>
              <div class="login-password">
                <h4><?php echo $main_strings['login_pass']; ?></h4>
                <input type="password" id="password-input" name="password" class="login-input" value="" placeholder="" required>
              </div>
              <div class="password-show">
                <span id="password-show-span"><?php echo $main_strings['password_show']; ?></span>
                <input type="checkbox" name="show" value="" onclick="togglePasswordShow()" class="site-login-checkbox">
              </div>
              <a href="<?php echo "{$file_root}password/forgot.php" ?>"><?php echo $main_strings['account_forgot']; ?></a>
              <input type="submit" class="header-submit" name="" value="<?php echo $main_strings['account_login']; ?>">
            </div>
        </div>
      </form>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
