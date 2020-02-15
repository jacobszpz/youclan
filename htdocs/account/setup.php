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

  $debuggingActivated = TRUE;
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
      exit;
    }
  }

  // We need database connection earlier due to retrieving values
  // for country and courses dropdown
  require_once "{$file_root}database/config.php";
  require_once "{$file_root}database/conn.php";

  $showErrorMessage = FALSE;
  $showDatabaseError = FALSE;
  $errorType = 0;

  /*
    ERROR DEFINITIONS:

    0: No Errors

    1: Invalid Lecturer Status
    2: One or more values are not Integers
    3: Range of values is not right
    4: Could not move file even though upload was ok

  */

  function getErrorMessage($type) {
    $errorString = "";

    switch ($type) {
      case 1:
        $errorString = 'error_lecturer_invalid';
        break;

      case 2:
        $errorString = 'error_values_not_integers';
        break;

      case 3:
        $errorString = 'error_values_range';
        break;

      case 4:
        $errorString = 'error_file_upload';
        break;

      case 100:
        $errorString = 'error_database_connection';
        break;
    }

    return $errorString;
  }

  function printItems($tableName, $defaultValue, $title) {
    global $Connection_SQL, $formSent;
    $item_Query = "SELECT * FROM $tableName";

    $Query_SQL = mysqli_query($Connection_SQL, $item_Query);

    $selectedStr;

    print "<option disabled value=\"\">$title</option>";

    while ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)) {
      if ($Row_SQL['ID'] == $defaultValue) {$selectedStr = "option selected";} else {$selectedStr = "option";}
      print "\t\t\t<$selectedStr value=\"{$Row_SQL['ID']}\">{$Row_SQL['Name']}</option>" . PHP_EOL;
    }
  }

  $formSent = FALSE;
  $Lecturer_Request = 0;
  $Country_Request = 186;
  $StartYear_Request = date("Y");

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $phpErrorMessage .= "Form Method is POST<br>";
    $formSent = TRUE;

    // Set Variables
    $Course_Request = (int) filter_input(INPUT_POST, 'course', FILTER_SANITIZE_NUMBER_INT);
    $CourseType_Request = (int) filter_input(INPUT_POST, 'course_type', FILTER_SANITIZE_NUMBER_INT);
    $Country_Request = (int) filter_input(INPUT_POST, 'country', FILTER_SANITIZE_NUMBER_INT);
    $Lecturer_Request = (int) filter_input(INPUT_POST, 'lecturer', FILTER_SANITIZE_NUMBER_INT);
    $StartYear_Request = (int) filter_input(INPUT_POST, 'start_year', FILTER_SANITIZE_NUMBER_INT);

    $Picture_Request = $_FILES['picture'];
    $PictureName = trim(filter_var($Picture_Request['name'], FILTER_SANITIZE_STRING));
    $PictureTmpName = trim(filter_var($Picture_Request['tmp_name'], FILTER_SANITIZE_STRING));

    $FileType = trim(filter_var($Picture_Request['type'], FILTER_SANITIZE_STRING));
    $FileSize = (int) filter_var($Picture_Request['size'], FILTER_SANITIZE_NUMBER_INT);
    $FileCode = (int) filter_var($Picture_Request['error'], FILTER_SANITIZE_NUMBER_INT);

    $phpErrorMessage .= "Variables From Request Read<br>";

    # EMPTY REQUEST DETECTION #

    $intRequestValues = [$Country_Request, $StartYear_Request];

    // Check Lecturer Var
    if ($Lecturer_Request === 0) {
      $intRequestValues[] = $Course_Request;
      $intRequestValues[] = $CourseType_Request;
    } else if ($Lecturer_Request === 1) {
      $Course_Request = NULL;
      $CourseType_Request = 0;
    } else {
      // Invalid lecturer status through mischieving
      $showErrorMessage = TRUE;
      $errorType = 1;
    }

    // Check Integrity (pun intended) of values
    if (!$showErrorMessage) {
      $phpErrorMessage .= "Lecturer value correct<br>";

      foreach ($intRequestValues as $intVar) {
        if (!is_int($intVar)) {
          $showErrorMessage = TRUE;
          // One or more values are not in integer form
          $errorType = 2;
        }
      }
    }

    # DATA VALIDATION #

    // Check Range of Values
    if (!$showErrorMessage) {
      $phpErrorMessage .= "All variables are Integers<br>";

      $showErrorMessage = TRUE;
      $errorType = 3;

      if ($Country_Request < 197 && $Country_Request > 0) {
        if ($StartYear_Request <= date("Y") && $StartYear_Request >= 1900) {
          if ($Lecturer_Request === 0) {
            if ($Course_Request < 355 && $Course_Request > 0) {
              if ($CourseType_Request < 6 && $CourseType_Request > 0) {
                $showErrorMessage = FALSE;
                $errorType = 0;
              }
            }
          } else {
            $showErrorMessage = FALSE;
            $errorType = 0;
          }
        }
      }
    }

    // Check File
    if (!$showErrorMessage) {
      $phpErrorMessage .= "All values within range<br>";
      $showErrorMessage = TRUE;

      if ($FileCode == 0) {
        $phpErrorMessage .= "Upload has been successful<br>";

        $FileExtension = end(explode(".", $PictureName));

        $NewFilename = md5(uniqid(rand(), true)) . "." . $FileExtension;
        $FileChecksum = hash_file("sha256", $PictureTmpName);

        if (move_uploaded_file($PictureTmpName, "{$file_root}uploads/$NewFilename")) {
          $phpErrorMessage .= "Upload moved under new secret name<br>";

          $newUploadQuery = "INSERT INTO uploads
          (OriginalFilename, Filename, UploadedBy, FileChecksum, Filesize) VALUES
          ('$PictureName', '$NewFilename', $ID_Session, '$FileChecksum', $FileSize)";

          $Query_SQL = mysqli_query($Connection_SQL, $newUploadQuery);
          echo $Query_SQL;
          $phpErrorMessage .= "Upload registered in DB<br>";

          $uploadID = mysqli_insert_id($Connection_SQL);
          $showErrorMessage = FALSE;
        }
      }

      if ($showErrorMessage) {
        // Debug file upload
        switch ($FileCode) {
          default:
            $errorType = 4;
            break;
        }
      }
    }

    // If both variables present
    if (!$showErrorMessage) {
      // Check if it matches database
      $phpErrorMessage .= "Setup is not empty<br>";

      // Check connection
      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";
        mysqli_set_charset($Connection_SQL, "utf8");

        // Lookup Username in DB
        $userSetup_Query = "UPDATE users SET
        Country = $Country_Request,
        Course = $Course_Request,
        CourseType = $CourseType_Request,
        StartYear = $StartYear_Request,
        Lecturer = $Lecturer_Request,
        ProfilePicture = $uploadID,
        SetupComplete = 1 WHERE ID = $ID_Session";

        $Query_SQL = mysqli_query($Connection_SQL, $userSetup_Query);

        $phpErrorMessage .= "Performed User Setup Query<br>";

        // Only close connection if user won't be showed form again
        mysqli_close($Connection_SQL);

        $_SESSION['setup_account'] = TRUE;

        header("location: {$file_root}");
        exit;
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
    <script src="<?php print $file_root; ?>scripts/setup.js"></script>
  </head>
  <body>
    <header>
      <?php include "{$file_root}templates/header.php"; ?>
    </header>
    <main>
      <form id="setup-form" action="" enctype="multipart/form-data" method="post">
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
            <div class="setup-profile-picture">
              <h4><?php echo $main_strings['setup_pfp']; ?></h4>
              <input type="hidden" name="MAX_FILE_SIZE" value="12582912">
              <input type="file" accept="image/*" id="setup-pfp" class="setup-input" name="picture" value="" required>
              <h4 id="pfp-preview-caption"><?php echo $main_strings['setup_pfp_preview']; ?></h4>
              <img src="" alt="" id="pfp-preview">
            </div>
            <div class="setup-lecturer" id="lecturer-section">
              <h4><?php echo $main_strings['setup_lecturer']; ?></h4>
              <input type="radio" name="lecturer" onchange="toggleStudentFields()" value="1" <?php if ($Lecturer_Request == 1) { echo "checked";} ?>>
              <span class="lecturer-radio"><?php echo $main_strings['setup_l_lecturer']; ?></span>
              <br>
              <input type="radio" name="lecturer" onchange="toggleStudentFields()" value="0" <?php if ($Lecturer_Request == 0) { echo "checked";} ?>>
              <span class="lecturer-radio"><?php echo $main_strings['setup_l_student']; ?></span>
            </div>
            <div id="setup-country">
              <h4><?php echo $main_strings['setup_country']; ?></h4>
              <select class="setup-select" name="country" required>
                <?php printItems("countries", $Country_Request, "Country"); ?>
              </select>
            </div>
            <div id="setup-course-type">
              <h4><?php echo $main_strings['setup_course_type']; ?></h4>
              <select class="setup-select" name="course_type">
                <?php printItems("course_types", $CourseType_Request, "Level"); ?>
              </select>
            </div>
            <div id="setup-course">
              <h4><?php echo $main_strings['setup_course']; ?></h4>
              <select class="setup-select" name="course">
                <?php printItems("courses", $Course_Request, "Course"); ?>
              </select>
            </div>
            <div class="setup-start-year">
              <h4><?php echo $main_strings['setup_start_year']; ?></h4>
              <input type="number" class= "setup-input" name="start_year" value="<?php echo $StartYear_Request; ?>" min="1900" max="<?php echo date("Y"); ?>" required>
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
