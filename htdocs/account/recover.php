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
    header("location: {$file_root}");
    exit;
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

    require_once "{$file_root}database/config.php";

    // Set Variables
    $Token_Request = trim(filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING));

    $phpErrorMessage .= "Variables From Request Read<br>";

    // If both variables present
    if (!empty($Token_Request)) {
      // Check if it matches database
      $phpErrorMessage .= "Token is not empty<br>";

      require_once "{$file_root}database/conn.php";

      // Check connection
      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";

        // Lookup Username in DB
        $userLookup_Query = "SELECT u.*, p.Filename, c.Name AS CourseName, t.Name AS Level, n.Name AS CountryName
          FROM users AS u LEFT JOIN courses AS c ON (u.Course = c.ID)
          LEFT JOIN course_types AS t ON (u.CourseType = t.ID)
          LEFT JOIN uploads AS p ON (u.ProfilePicture = p.ID)
          LEFT JOIN countries AS n ON (u.Country = n.ID)
          WHERE Username = '$Username_Request'";
        $Query_SQL = mysqli_query($Connection_SQL, $userLookup_Query);

        $phpErrorMessage .= "Retrieved Users<br>";

        // Check if username exists, if yes then verify password
        $Rows_Result = mysqli_num_rows($Query_SQL);

        if ($Rows_Result == 1){
          $phpErrorMessage .= "One User Was Found<br>";

          if ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)) {
            $phpErrorMessage .= "Results Fetched<br>";

            $ID_Result = $Row_SQL['ID'];
            $Username_Result = $Row_SQL['Username'];
            $Password_Result = $Row_SQL['Password'];
            $Name_Result = $Row_SQL['Name'];
            $Surnames_Result = $Row_SQL['Surnames'];
            $VerifiedAccount_Result = $Row_SQL['VerifiedAccount'];
            $VerifyToken_Result = $Row_SQL['VerifyToken'];
            $LostAccount_Result = $Row_SQL['LostAccount'];
            $SetupComplete_Result = $Row_SQL['SetupComplete'];
            $Picture_Result = $Row_SQL['Filename'];
            $Country_Result = $Row_SQL['Country'];
            $Course_Result = $Row_SQL['Course'];
            $Level_Result = $Row_SQL['CourseType'];
            $CountryName_Result = $Row_SQL['CountryName'];
            $CourseName_Result = $Row_SQL['CourseName'];
            $LevelName_Result = $Row_SQL['Level'];

            // Check token
            if($Token_Request = $LostToken_Result){
              $phpErrorMessage .= "Token Matches (yay)<br>";

              // Token is correct
              // Store data in session variables
              // FINALLY, UPDATE SQL DB

              $updateToken_Query = "UPDATE users SET LostAccount = 0, LostToken = NULL WHERE Username = '$Username_Session'";
              $dbTokenUpdate = mysqli_query($Connection_SQL, $updateToken_Query);

              if ($dbTokenUpdate) {
                $phpErrorMessage .= "Token Deleted and Account Verified in DB";

                $_SESSION['logged_in'] = TRUE;
                $_SESSION['username'] = $Username_Result;
                $_SESSION['name'] = $Name_Result;
                $_SESSION['surnames'] = $Surnames_Result;
                $_SESSION['lost_account'] = TRUE;
                $_SESSION['verified'] = TRUE;

                if ($SetupComplete_Result == 0) {
                  $_SESSION['setup_account'] = FALSE;
                } else {
                  $_SESSION['setup_account'] = TRUE;
                  $_SESSION['picture'] = $Picture_Result;
                  $_SESSION['country'] = $Country_Result;
                  $_SESSION['course'] = $Course_Result;
                  $_SESSION['level'] = $Level_Result;
                }

                header("location: {$file_root}password/new.php");
              } else {
                $showDatabaseError = TRUE;
              }
            }
          }
        }
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
        <?php echo "<span>$phpErrorMessage</span>"; ?>
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
