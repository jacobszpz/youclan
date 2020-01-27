<?php
  $current_page = "recover";

  $path_parts = explode('htdocs', __DIR__);
  $path_deep = substr_count($path_parts[1], "/");
  $file_root = "";

  for ($i=0; $i < $path_deep; $i++) {
    $file_root .= "../";
  }

  include "{$file_root}templates/php_init.php";

  // Overwrite Page Title
  $current_title = $main_strings['recover_title'];

  // To Test Logged In Redirect
  // $loggedIn = TRUE;

  $debuggingActivated = FALSE;
  $phpErrorMessage = "Debugging Activated<br>";

  // Do not show page if user is already logged in
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

  $showForm = TRUE;
  $showErrorMessage = TRUE;
  $showDatabaseError = FALSE;

  $errorType = 0;

  /*
    ERROR DEFINITIONS:

    0: No Errors



  */

  $errorMessage = $main_strings['error_verify_token'];

  // We begin to check token provided
  if (!empty($_GET)) {
    $showForm = FALSE;
    $phpErrorMessage .= "Form Method is GET<br>";

    require_once $file_root . "database/config.php";

    // Set Variables
    $Username_Request = trim(filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING));
    $Token_Request = trim(filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING));

    $phpErrorMessage .= "Variables From Request Read<br>";

    // If both variables present
    if (!empty($Token_Request)) {
      // Check if it matches database
      $phpErrorMessage .= "Token is not empty<br>";

      if (function_exists('mysqli_connect')) {
        $Connection_SQL = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
      } else {
        $Connection_SQL = FALSE;
      }

      // Check connection
      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";

        // Lookup Username in DB
        $userLookup_Query = "SELECT LostToken FROM users WHERE Username = ?";

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
                mysqli_stmt_bind_result($Statement_SQL, $Token_Result);

                if (mysqli_stmt_fetch($Statement_SQL)){
                  $phpErrorMessage .= "Results Fetched<br>";

                  // Check token
                  if($Token_Request = $Token_Result){
                    $phpErrorMessage .= "Token Matches (yay)<br>";

                    // Token is correct
                    // Store data in session variables
                    // FINALLY, UPDATE SQL DB

                    $updateToken_Query = "UPDATE users SET LostAccount = 0, LostToken = NULL WHERE Username = '$Username_Request'";
                    $dbTokenUpdate = mysqli_query($Connection_SQL, $updateToken_Query);

                    if ($dbTokenUpdate) {
                      $phpErrorMessage .= "Token Deleted and Account Verified in DB";
                      $_SESSION['lost_account'] = TRUE;
                      $_SESSION['username'] = $Username_Request;
                      $_SESSION['verified'] = TRUE;

                      header("location: {$file_root}password/new.php");
                    } else {
                      $showDatabaseError = TRUE;
                    }
                  }
                }
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

  if ($showDatabaseError) {
    $errorMessage = $main_strings['error_database_connection'];
  } if ($showForm) {
    $errorMessage = "";
  }
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="ltr">
  <head>
    <?php include "{$file_root}templates/head.php"; ?>
  </head>
  <body>
    <header>
      <?php include "{$file_root}templates/header.php"; ?>
    </header>
    <main>
      <div class="main-center">
        <h2><?php echo $errorMessage; ?></h2>
        <?php if ($showForm) {?>
          <form class="" action="" method="get">
            <h4><?php echo $main_strings['account_verify_token']; ?></h4>
            <input type="text" class="small-form-input" name="token" value="">
            <input type="submit" name="" class="header-submit small-form-submit" value="<?php echo $main_strings['account_verify']; ?>">
          </form>
        <?php } ?>
      </div>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
