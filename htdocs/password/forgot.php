<?php
  $current_page = "forgot";

  $path_parts = explode('htdocs', __DIR__);
  $path_deep = substr_count($path_parts[1], "/");
  $file_root = "";

  for ($i=0; $i < $path_deep; $i++) {
    $file_root .= "../";
  }

  include "{$file_root}templates/php_init.php";

  // Overwrite Page Title
  $current_title = $main_strings['forgot_title'];

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

  $showErrorMessage = FALSE;
  $showDatabaseError = FALSE;
  $errorMessage = $main_strings['error_account_no_user'];

  function sendRecoverMail($user, $token) {
    global $file_root, $lang, $main_strings;

    $email = "$user@uclan.ac.uk";

    $user_mail_body = file_get_contents($file_root . 'templates/mail.php');
    $user_mail_body = str_replace('MAIN_TEXT', file_get_contents($file_root . 'texts/mail/' . $lang . '_recovery.txt'), $user_mail_body);
    $user_mail_body = str_replace('DISCLAIMER_TEXT', file_get_contents($file_root . 'texts/mail/' . $lang .'_disclaimer.txt'), $user_mail_body);
    $user_mail_body = str_replace('MAIL', $email, $user_mail_body);
    $user_mail_body = str_replace('TOKEN', "$token", $user_mail_body);
    $user_mail_body = str_replace('USER', "$user", $user_mail_body);

    $user_mail_reply_to = 'no-reply@youclan.uk';
    $user_mail_sender = 'no-reply@youclan.uk';

    $user_mail_subject = $main_strings['mail_recover_subject'];

    // To send HTML mail, the Content-type header must be set
    $mail_headers  = 'MIME-Version: 1.0' . "\r\n";
    $mail_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $mail_headers .= 'From: youclan <' . $user_mail_sender . "> \r\n" . 'Reply-To: ' . $user_mail_reply_to . "\r\n";
    $mail_headers .= 'X-Mailer: PHP/' . phpversion();

    $mailOP = mail($email, $user_mail_subject, $user_mail_body, $mail_headers);
    return $mailOP;
  }

  // We begin to check lost user provided
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $showErrorMessage = TRUE;

    $phpErrorMessage .= "Form Method is POST<br>";

    require_once $file_root . "database/config.php";

    // Set Variables
    $Username_Request = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));

    $phpErrorMessage .= "Variables From Request Read<br>";

    // If variable present
    if (!empty($Username_Request)) {
      // Check if it matches database
      $phpErrorMessage .= "User is not empty<br>";

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
        $userLookup_Query = "SELECT * FROM users WHERE Username = ?";

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
                $New_Lost_Token = md5(uniqid(rand(), true));

                // User Was Found. Create Random Token, Update it, and send Mail. Redirect To password/new
                $updateToken_Query = "UPDATE users SET LostAccount = 1, LostToken = '$New_Lost_Token' WHERE Username = '$Username_Request'";
                $dbTokenUpdate = mysqli_query($Connection_SQL, $updateToken_Query);

                if ($dbTokenUpdate) {
                  $phpErrorMessage .= "Token Generated and Account Marked as Lost in DB";

                  $sendMailOP = FALSE;
                  $mailAttempts = 1;

                  do {
                    $sendMailOP = sendRecoverMail($Username_Request, $New_Lost_Token);
                    $mailAttempts++;
                  } while (!$sendMailOP && $mailAttempts <= 3);

                  if (!$sendMailOP) {
                    // Token could not be sent. HELP!
                    // Email Send Error
                    $showDatabaseError = TRUE;
                  } else {
                    header("location: {$file_root}account/recover.php");
                    exit;
                  }
                } else {
                  $showDatabaseError = TRUE;
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
  } if (!$showErrorMessage) {
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
          <form class="" action="" method="post">
            <h4><?php echo $main_strings['account_recover_user']; ?></h4>
            <h4 id="login-error-message"><?php echo $errorMessage; ?></h4>
            <input type="text" class="small-form-input" name="username" value="">
            <input type="submit" class="header-submit small-form-submit" name="" value="<?php echo $main_strings['account_recover']; ?>">
          </form>
      </div>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
