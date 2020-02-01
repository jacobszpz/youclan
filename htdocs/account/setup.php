<?php
  $current_page = "setup";

  $path_parts = explode('htdocs', __DIR__);
  $path_deep = substr_count($path_parts[1], "/");
  $file_root = "";

  for ($i=0; $i < $path_deep; $i++) {
    $file_root .= "../";
  }

  include "{$file_root}templates/php_init.php";

  // Overwrite Page Title
  $current_title = $main_strings['setup_title'];

  // To Test Logged In Redirect
  // $loggedIn = TRUE;

  $debuggingActivated = FALSE;
  $phpErrorMessage = "Debugging Activated<br>";

  // Do not show page if user is not logged in
  if (!$loggedIn) {
    header("location: " . $file_root . "login");
    exit;
  } else {
    if ($Lost_Session) {
      header("location: {$file_root}password/new.php");
      exit;
    } else if (!$Verified_Session) {
      header("location: {$file_root}account/verify.php");
      exit;
    } else if ($Setup_Session) {
      header("location: {$file_root}");
    }
  }

  // We need database connection earlier due to retrieving values
  // for country and courses dropdown
  require_once "{$file_root}database/config.php";
  require_once "{$file_root}database/conn.php";

  $showErrorMessage = TRUE;
  $showDatabaseError = FALSE;
  $errorType = 0;

  /*
    ERROR DEFINITIONS:

    0: No Errors


  */

  function getErrorMessage($type) {
    $errorString = "";

    switch ($type) {
      case 100:
        $errorString = 'error_database_connection';
        break;

      default:
        break;
    }

    return $errorString;
  }

  function printItems($tableName) {
    global $Connection_SQL;
    $item_Query = "SELECT * FROM $tableName";

    $Query_SQL = mysqli_query($Connection_SQL, $item_Query);

    while ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)) {
      print "\t\t\t\t<option value=\"{$Row_SQL['ID']}\">{$Row_SQL['Name']}</option>" . PHP_EOL;
    }
  }

  $Lecturer_Request = 0;

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $phpErrorMessage .= "Form Method is POST<br>";

    // Set Variables
    $Course_Request = trim(filter_input(INPUT_GET, 'course', FILTER_SANITIZE_STRING));
    $CourseType_Request = trim(filter_input(INPUT_GET, 'course_type', FILTER_SANITIZE_STRING));
    $Country_Request = trim(filter_input(INPUT_GET, 'country', FILTER_SANITIZE_NUMBER_INT));
    $Lecturer_Request = trim(filter_input(INPUT_GET, 'lecturer', FILTER_SANITIZE_NUMBER_INT));

    $phpErrorMessage .= "Variables From Request Read<br>";

    # DATA VALIDATION #

    // If both variables present
    // TODO: CHANGE THIS CONDITIONAL
    if (!empty($Token_Request) && FALSE) {
      // Check if it matches database
      $phpErrorMessage .= "Setup is not empty<br>";

      // Check connection
      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";
        mysqli_set_charset($Connection_SQL, "utf8");

        // Lookup Username in DB
        $userSetup_Query = "UPDATE users SET
        Country = '$Country_Request',
        Course = '$Course_Request',
        CourseType = '$CourseType_Request',
        StartYear = '$StartYear_Request',
        Lecturer = '$Lecturer',
        SetupComplete = 1 WHERE Username = '$Username_Session'";

        $Query_SQL = mysqli_query($Connection_SQL, $userSetup_Query);

        $phpErrorMessage .= "Performed User Setup Query<br>";

        // Only close connection if user won't be showed form again
        mysqli_close($Connection_SQL);
      } else {
        $showDatabaseError = TRUE;
      }
    }
  }

  # ERROR HANDLING #

  $errorString = getErrorMessage($errorType);

  if ($showErrorMessage) {
    $errorMessage = $main_strings[$errorString];
  }

  if ($showDatabaseError) {
    $errorMessage = $main_strings['error_database_connection'];
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
      <form class="" action="" method="post">
        <div class="main-center setup-center">
          <?php if ($debuggingActivated) {
              echo '<p class="php-debug">' . $phpErrorMessage . '</p>';
          } ?>
          <h1><?php echo $main_strings['account_setup']; ?></h1>
          <?php
            if ($showErrorMessage){
              echo '<span id="login-error-message">' . $errorMessage . '</span>';
            }
          ?>
          <div class="setup-main">
            <div class="setup-lecturer">
              <h4><?php echo $main_strings['setup_lecturer']; ?></h4>
              <input type="radio" name="lecturer" value="1" <?php if ($Lecturer_Request == 1) { echo "checked";} ?>>
              <span class="lecturer-radio"><?php echo $main_strings['setup_l_lecturer']; ?></span>
              <br>
              <input type="radio" name="lecturer" value="0" <?php if ($Lecturer_Request == 0) { echo "checked";} ?>>
              <span class="lecturer-radio"><?php echo $main_strings['setup_l_student']; ?></span>
            </div>
            <div class="setup-country">
              <h4><?php echo $main_strings['setup_country']; ?></h4>
              <select class="setup-select" name="country">
                <?php printItems("countries"); ?>
              </select>
            </div>
            <div class="setup-course-type">
              <h4><?php echo $main_strings['setup_course_type']; ?></h4>
              <select class="setup-select" name="course_types">
                <?php printItems("course_types"); ?>
              </select>
            </div>
            <div class="setup-course">
              <h4><?php echo $main_strings['setup_course']; ?></h4>
              <select class="setup-select" name="courses">
                <?php printItems("courses"); ?>
              </select>
            </div>
            <input type="submit" name="" class="header-submit small-form-submit" value="<?php echo $main_strings['account_save']; ?>">
          </div>
        </div>
      </form>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
