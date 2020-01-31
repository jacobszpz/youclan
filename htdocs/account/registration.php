<?php
  $current_page = "registration";

  $path_parts = explode('htdocs', __DIR__);
  $path_deep = substr_count($path_parts[1], "/");
  $file_root = "";

  for ($i=0; $i < $path_deep; $i++) {
    $file_root .= "../";
  }

  include "{$file_root}templates/php_init.php";

  // Overwrite Page Title
  $current_title = $main_strings['register_title'];

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

  // Due to the SHEER amount of different errors we can get through this
  // single maneuver, we'll be using an INT instead of tons of booleans

  $errorType = 0;

  // Where:
  /*
    0: No Error

    EMPTY ERRORS
    1: Empty Name error
    2: Empty User error
    3: Empty Birthday error
    4: Empty Gender error

    INVALID ERRORS
    5: Invalid User error (invalid chars)

    Invalid Password error
    6: due to length
    7: due to simplicity

    8: Invalid Birthday error (invalid day of month, etc)
    9: Invalid Gender error

    TAKEN ERRORS
    10: Taken User error

    SERVER ERRORS
    100: Database Error

  */

  function sendTokenMail($name, $user, $token) {
    global $file_root, $lang, $main_strings;

    $email = "$user@uclan.ac.uk";

    $user_mail_body = file_get_contents($file_root . 'templates/mail.php');
    $user_mail_body = str_replace('MAIN_TEXT', file_get_contents($file_root . 'texts/mail/' . $lang . '_verify.txt'), $user_mail_body);
    $user_mail_body = str_replace('DISCLAIMER_TEXT', file_get_contents($file_root . 'texts/mail/' . $lang .'_disclaimer.txt'), $user_mail_body);
    $user_mail_body = str_replace('NAME', $name, $user_mail_body);
    $user_mail_body = str_replace('MAIL', $email, $user_mail_body);
    $user_mail_body = str_replace('TOKEN', "$token", $user_mail_body);


    $user_mail_reply_to = 'no-reply@youclan.uk';
    $user_mail_sender = 'no-reply@youclan.uk';

    $user_mail_subject = $main_strings['mail_token_subject'];

    // To send HTML mail, the Content-type header must be set
    $mail_headers  = 'MIME-Version: 1.0' . "\r\n";
    $mail_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $mail_headers .= 'From: youclan <' . $user_mail_sender . "> \r\n" . 'Reply-To: ' . $user_mail_reply_to . "\r\n";
    $mail_headers .= 'X-Mailer: PHP/' . phpversion();

    $mailOP = mail($email, $user_mail_subject, $user_mail_body, $mail_headers);
    return $mailOP;
  }

  function getErrorMessage($type) {
    $errorString = "";

    switch ($type) {
      case 100:
        $errorString = 'error_database_connection';
        break;

      case 150:
        $errorString = 'error_email_send';
        break;

      case 0:
        break;

      case 1:
        $errorString = 'error_register_empty_name';
        break;

      case 2:
        $errorString = 'error_register_empty_user';
        break;

      case 3:
      case 4:
        $errorString = 'error_register_empty_data';
        break;

      case 5:
        $errorString = 'error_register_wrong_user';
        break;

      case 6:
        $errorString = 'error_register_short_pass';
        break;

      case 7:
        $errorString = 'error_register_simple_pass';
        break;

      case 8:
        $errorString = 'error_register_wrong_birthday';
        break;

      case 9:
        $errorString = 'error_register_wrong_gender';
        break;

      case 10:
        $errorString = 'error_register_taken_user';
        break;

      default:
        break;
    }

    return $errorString;
  }

  // Registration Function
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $phpErrorMessage .= "Form Method is POST<br>";

    require_once $file_root . "database/config.php";

    # REQUEST VARIABLES #

    $Name_Request = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $Surname_Request = trim(filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING));
    $Username_Request = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    // Idiot handling
    $Username_Request = str_ireplace("@uclan.ac.uk", "", $Username_Request);
    $Password_Request = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    // More Variables
    $BirthDay_Request = trim(filter_input(INPUT_POST, 'day', FILTER_SANITIZE_NUMBER_INT));
    $BirthMonth_Request = trim(filter_input(INPUT_POST, 'month', FILTER_SANITIZE_STRING));
    $BirthYear_Request = trim(filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT));

    $Gender_Request = trim(filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_NUMBER_INT));

    $Sent_Request = TRUE;

    $phpErrorMessage .= "Variables From Request Read<br>";

    # EMPTY REQUEST DETECTION #

    if (empty($Name_Request) || empty($Surname_Request)) {
      $showErrorMessage = TRUE;
      $errorType = 1;
    } else if (empty($Username_Request) || empty($Password_Request)) {
      $showErrorMessage = TRUE;
      $errorType = 2;
    } else if (empty($BirthDay_Request) || empty($BirthMonth_Request) || empty($BirthYear_Request)){
      $showErrorMessage = TRUE;
      $errorType = 3;
    }

    # INVALID DATA VALIDATION #

    if (!$showErrorMessage) {
      $phpErrorMessage .= "Register form is not empty<br>";

      $longMonthArray = [1, 3, 5, 7, 8, 10, 12];

      // Detect Invalid Request Data
      // Such as Invalid Usernames, Dates, etc
      $Password_Length = strlen($Password_Request);

      if ($Password_Length < 8) {
        // Check pass length
        $showErrorMessage = TRUE;
        $errorType = 6;
      } else if (!preg_match('/\d/', $Password_Request) && $Password_Length < 14) {
        // Check password has at least one number
        $showErrorMessage = TRUE;
        $errorType = 7;
      } else if (preg_match('/[^a-zA-Z0-9\-]/', $Username_Request)) {
        // Check if username contains an invalid char
        $showErrorMessage = TRUE;
        $errorType = 5;
      } else {
        // Check valid birthday
        if (!checkdate($BirthMonth_Request, $BirthDay_Request, $BirthYear_Request)){
          $showErrorMessage = TRUE;
          $errorType = 8;
        } else if ($BirthYear_Request < 1900 || $BirthYear_Request > 2050){
          $showErrorMessage = TRUE;
          $errorType = 8;
        } else if ($Gender_Request != 0 && $Gender_Request != 1) {
          // Invalid gender through mischieving
          $showErrorMessage = TRUE;
          $errorType = 9;
        }
      }
    }

    # LOOKUP USER THEN REGISTER USER #

    if (!$showErrorMessage) {
      $phpErrorMessage .= "Data is valid<br>";

      // Data has been validated, continue (effing finally)
      if (function_exists('mysqli_connect')) {
        $Connection_SQL = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
      } else {
        $Connection_SQL = FALSE;
      }

      // Check connection
      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";
        mysqli_set_charset($Connection_SQL, "utf8");
        // FIRST: check if user exists

        // Lookup Username in DB
        $userLookup_Query = "SELECT * FROM users WHERE Username = '$Username_Request'";
        $Query_SQL = mysqli_query($Connection_SQL, $userLookup_Query);

        $phpErrorMessage .= "Retrieved Users<br>";

        // Check if username exists, if yes then verify password
        $Rows_Result = mysqli_num_rows($Query_SQL);

        if ($Rows_Result == 0){
          $phpErrorMessage .= "User Is Not Taken<br>";
          // Drop everything and register the damn bastard
          // SECOND: register user
          $userRegister_Query = "INSERT INTO users (Username, Password, Name, Surnames, Birthdate, Gender, VerifyToken) VALUES (?, ?, ?, ?, ?, ?, ?)";

          if ($Statement_SQL = mysqli_prepare($Connection_SQL, $userRegister_Query)) {
            $Birthdate = new DateTime();
            $Birthdate->setDate($BirthYear_Request, $BirthMonth_Request, $BirthDay_Request);
            $SQLDate = $Birthdate->format('Y-m-d');

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($Statement_SQL, "sssssis", $User_Parameter, $Password_Parameter, $Name_Parameter,
            $Surnames_Parameter, $Birthdate_Parameter, $Gender_Parameter, $Token_Parameter);

            $User_Parameter = $Username_Request;
            $Password_Parameter = password_hash($Password_Request, PASSWORD_DEFAULT);
            $Name_Parameter = $Name_Request;
            $Surnames_Parameter = $Surname_Request;
            $Birthdate_Parameter = $SQLDate;
            $Gender_Parameter = $Gender_Request;
            // Create random token to verify user
            $Token_Parameter = md5(uniqid(rand(), true));
            // You will also mail them this token lol

            // Save User Data In Database (No Need To Retrieve Results)
            if(mysqli_stmt_execute($Statement_SQL)){
              $sendMailOP = FALSE;
              $mailAttempts = 1;

              do {
                $sendMailOP = sendTokenMail($Name_Request, $Username_Request, $Token_Parameter);
                $mailAttempts++;
              } while (!$sendMailOP && $mailAttempts <= 3);

              if (!$sendMailOP) {
                // Token could not be sent. HELP!
                // Email Send Error
                $errorType = 150;
                $showErrorMessage = TRUE;
              } else {
                // Verification has been sent to the users' mailbox,
                // redirect or something lol

                // Password is correct
                // Store data in session variables
                $_SESSION['logged_in'] = TRUE;
                $_SESSION['username'] = $Username_Request;
                $_SESSION['name'] = $Name_Request;
                $_SESSION['surnames'] = $Surname_Request;
                $_SESSION['verified'] = FALSE;
                $_SESSION['verify_token'] = $Token_Parameter;

                header("location: " . $file_root . "account/verify.php");
                exit;
              }
              // FINALLY: redirect user to find friends or so
              // Redirect to login page
              // USER IS REGISTERED
              // NOW: VERIFY USER
              // ALSO: CONTINUE REGISTRATION
            } else {
              // Database Error
              $errorType = 100;
              $showErrorMessage = TRUE;
            }
            mysqli_stmt_close($Statement_SQL);
          }
        } else if ($Rows_Result > 0) {
          // User Is Taken
          $showErrorMessage = TRUE;
          $errorType = 10;
        }
        mysqli_close($Connection_SQL);
      } else {
        // No MYSQL Installed (DB Error)
        $errorType = 100;
        $showErrorMessage = TRUE;
      }
    }

    # ERROR HANDLING #

    $errorString = getErrorMessage($errorType);

    if ($showErrorMessage) {
      $errorMessage = $main_strings[$errorString];
    }
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
      <form class="register-form" action="" method="post">
        <div class="main-center registration-center">
          <?php if ($debuggingActivated) {
              echo '<p class="php-debug">' . $phpErrorMessage . '</p>';
          } ?>
          <h1><?php echo $main_strings['account_register']; ?></h1>
          <h3 class="subtitle"><?php echo $main_strings['account_register_sub']; ?></h3>

          <?php
            if ($showErrorMessage){
              echo '<span id="login-error-message">' . $errorMessage . '</span>';
            }
          ?>

            <div class="login-form-div">
              <div class="register-names">
                <div class="register-name">
                  <h4><?php echo $main_strings['login_name']; ?></h4>
                  <input type="text" name="name" class="register-input" value="<?php if ($Sent_Request) {echo $Name_Request;} ?>"
                  placeholder="" required>
                </div>
                <div class="register-surname">
                  <h4><?php echo $main_strings['login_surname']; ?></h4>
                  <input type="text" name="surname" class="register-input" value="<?php if ($Sent_Request) {echo $Surname_Request;} ?>"
                  placeholder="" required>
                </div>
              </div>
              <div class="login-username">
                <h4><?php echo $main_strings['register_user']; ?></h4>
                <input type="text" name="username" class="register-input" value="<?php if ($Sent_Request) {echo $Username_Request;} ?>"
                placeholder="<?php echo $main_strings['placeholder_user']; ?>" required>
              </div>
              <div class="login-password">
                <h4><?php echo $main_strings['register_pass']; ?></h4>
                <input type="password" id="password-input" name="password" class="register-input" value=""
                placeholder="<?php echo $main_strings['placeholder_pass']; ?>" required>
              </div>
              <div class="password-show">
                <span id="password-show-span"><?php echo $main_strings['password_show']; ?></span>
                <input type="checkbox" name="show" value="" onclick="togglePasswordShow()" class="site-login-checkbox">
              </div>
              <div class="register-plus">
                <div class="register-birthday">
                  <h4><?php echo $main_strings['register_birthday']; ?></h4>
                  <select class="reg-select day-select" name="day" title="<?php echo $main_strings['birthday_day']; ?>" required>
                    <?php
                      $selected = "selected";

                      if (!empty($BirthDay_Request)) {
                        $day = $BirthDay_Request;
                        $day_exists = TRUE;
                        $selected = "";
                      }

                      // Thunder Cross Split Attack
                      print "<option $selected disabled value=\"\">Day</option>";

                      for ($i = 1; $i <= 31 ; $i++) {
                        $selected = "";

                        if ($day_exists && $day == $i) {
                          $selected = "selected";
                        }

                        $birthday_day = $i;

                        print "<option $selected value=\"$i\">$birthday_day</option>";
                      }
                    ?>
                  </select>
                  <select class="reg-select month-select" name="month" title="<?php echo $main_strings['birthday_day'];?>" required>
                    <?php
                      $month_list = [];
                      $selected = "selected";

                      if (!empty($BirthMonth_Request)) {
                        $month = $BirthMonth_Request;
                        $month_exists = TRUE;
                        $selected = "";
                      }

                      for ($i = 0; $i <= 11; $i++) {
                        $new_month = $main_strings["month_list"][$i];
                        $month_list[] = $new_month;
                      }

                      // Thunder Cross Split Attack
                      print "<option $selected disabled value=\"\">Month</option>";

                      for ($i = 1; $i <= 12; $i++) {
                        $selected = "";

                        if ($month_exists && $month == $i) {
                          $selected = "selected";
                        }

                        print "<option $selected value=\"$i\">{$month_list[$i-1]}</option>";
                      }
                    ?>
                  </select>
                  <select class="reg-select year-select" name="year" title="<?php echo $main_strings['birthday_day'];?>" required>
                    <?php
                      $selected = "selected";

                      if (!empty($BirthYear_Request)) {
                        $year = $BirthYear_Request;
                        $year_exists = TRUE;
                        $selected = "";
                      }

                      // Thunder Cross Split Attack
                      print "<option $selected disabled value=\"\">Year</option>";

                      for ($i = 2020; $i >= 1900 ; $i--) {
                        $selected = "";

                        if ($year_exists && $year == $i) {
                          $selected = "selected";
                        }

                        print "<option $selected value=\"$i\">$i</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="register-gender">
                  <h4><?php echo $main_strings['register_gender']; ?></h4>
                  <input type="radio" name="gender" value="1" <?php if ($Gender_Request == 1) { echo "checked";} ?>>
                  <span class="gender-radio"><?php echo $main_strings['gender_female']; ?></span>
                  <br>
                  <input type="radio" name="gender" value="0" <?php if ($Gender_Request == 0) { echo "checked";} ?>>
                  <span class="gender-radio"><?php echo $main_strings['gender_male']; ?></span>
                </div>
              </div>
              <input type="submit" class="header-submit" name="" value="<?php echo $main_strings['account_register_submit']; ?>">
            </div>
        </div>
      </form>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
