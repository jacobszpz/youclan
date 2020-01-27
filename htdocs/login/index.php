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

  // To Test Logged In Redirect
  // $loggedIn = TRUE;

  $debuggingActivated = FALSE;
  $phpErrorMessage = "Debugging Activated<br>";

  # USER LOGGED IN REDIRECT #

  if ($loggedIn) {
    if ($Lost_Session) {
      header("location: " . $file_root . "password/new.php");
      exit;
    } else if (!$Verified_Session) {
      header("location: " . $file_root . "account/verify.php");
      exit;
    } else {
      header("location: " . $file_root);
      exit;
    }
  }

  $showErrorMessage = FALSE;

  $showUserError = FALSE;
  $showLoginError = FALSE;
  $showDatabaseError = FALSE;

  // Login Function
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $phpErrorMessage .= "Form Method is POST<br>";
    $showLoginError = TRUE;

    require_once $file_root . "database/config.php";

    // Set Variables
    $Username_Request = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $Password_Request = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    $phpErrorMessage .= "Variables From Request Read<br>";

    # EMPTY REQUEST DETECTION #
    if (!empty($Username_Request) && !empty($Password_Request)) {
      $phpErrorMessage .= "Login is not empty<br>";

      if (function_exists('mysqli_connect')) {
        $Connection_SQL = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
      } else {
        $Connection_SQL = FALSE;
      }

      // Check connection
      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";
        mysqli_set_charset($Connection_SQL, "utf8");

        // Lookup Username in DB
        $userLookup_Query = "SELECT Username, Password, Name, Surnames, VerifiedAccount, LostAccount FROM users WHERE Username = ?";

        if ($Statement_SQL = mysqli_prepare($Connection_SQL, $userLookup_Query)) {
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($Statement_SQL, "s", $User_Parameter);

          // Set parameters
          $User_Parameter = $Username_Request;

          // Attempt to execute the prepared statement
          if (mysqli_stmt_execute($Statement_SQL)) {
            $phpErrorMessage .= "Retrieved Users<br>";

              // Store result
              mysqli_stmt_store_result($Statement_SQL);

              // Check if username exists, if yes then verify password
              $Rows_Result = mysqli_stmt_num_rows($Statement_SQL);
              if ($Rows_Result == 1){
                $phpErrorMessage .= "One User Was Found<br>";

                // Bind result variables
                mysqli_stmt_bind_result($Statement_SQL, $Username_Result, $Password_Result,
                $Name_Result, $Surnames_Result, $VerifiedAccount_Result, $LostAccount_Result);

                if (mysqli_stmt_fetch($Statement_SQL)){
                  $phpErrorMessage .= "Results Fetched<br>";
                  $phpErrorMessage .= $NewAccount_Result . "<br>";

                  // Check password
                  if(password_verify($Password_Request, $Password_Result)){
                    $phpErrorMessage .= "Password Matches<br>";

                    // Password is correct
                    // Store data in session variables
                    $_SESSION['logged_in'] = TRUE;
                    $_SESSION['username'] = $Username_Result;
                    $_SESSION['name'] = $Name_Result;
                    $_SESSION['surnames'] = $Surnames_Result;

                    // Account Had Been Lost At Some Point Before
                    // User Has Been Able To Log Back In
                    // Therefore, Delete Token And Account Flag
                    if ($LostAccount_Result === 1) {
                      $lostStatusReset_Query = "UPDATE users SET LostAccount = 0, LostToken = NULL WHERE Username = '$Username_Result'";
                      $dbLostUpdate = mysqli_query($Connection_SQL, $lostStatusReset_Query);
                    }

                    if ($VerifiedAccount_Result === 0) {
                      $_SESSION['verified'] = FALSE;
                      header("location: " . $file_root . "login/verify.php");
                      exit;
                    } else {
                      $_SESSION['verified'] = TRUE;
                      // Redirect user to welcome page
                      header("location: " . $file_root);
                      exit;
                    }
                  }
                }
              } else if ($Rows_Result == 0) {
                $showLoginError = FALSE;
                $showUserError = TRUE;
              }
          } else {
            $showDatabaseError = TRUE;
          }
        }
        mysqli_stmt_close($Statement_SQL);
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
              <a href="<?php echo "{$file_root}account/recover.php" ?>"><?php echo $main_strings['account_forgot']; ?></a>
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
