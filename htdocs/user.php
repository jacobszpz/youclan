<?php
  $current_page = "user";

  $file_root = $_SERVER['DOCUMENT_ROOT'] . '/';
  include "{$file_root}templates/php_init.php";

  require "{$file_root}objects/user.php";

  $debuggingActivated = FALSE;
  $phpErrorMessage = "Debugging Activated<br>";

  $showErrorMessage = FALSE;
  $showDatabaseError = FALSE;
  $errorMessage = "";
  // Do not show page if user is already verified or not logged in
  if (!$loggedIn) {
    header("location: /login");
    exit;
  } else {
    if ($Lost_Session) {
      header("location: /password/new.php");
      exit;
    } else if (!$Verified_Session) {
      header("location: /account/verify.php");
      exit;
    } else if (!$Setup_Session) {
      header("location: /account/setup.php");
      exit;
    }
  }

  if (!empty($_GET)) {
    $phpErrorMessage .= "Form Method is GET<br>";
    require_once "{$file_root}database/config.php";

    $Username_Request = trim(filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING));
    if (!empty($Username_Request)) {
      require_once "{$file_root}database/conn.php";

      if ($Connection_SQL !== FALSE) {
        $phpErrorMessage .= "DB Connection was successful<br>";

        $getUserQuery = "SELECT u.Name, u.Surnames,
        u.Birthdate, u.Lecturer, u.Gender, u.StartYear,
        p.Filename, c.Name AS CourseName, t.Name AS Level,
        n.Name AS CountryName FROM users AS u
        LEFT JOIN courses AS c ON (u.Course = c.ID)
        LEFT JOIN course_types AS t ON (u.CourseType = t.ID)
        LEFT JOIN uploads AS p ON (u.ProfilePicture = p.ID)
        LEFT JOIN countries AS n ON (u.Country = n.ID)
        WHERE Username = '$Username_Request'";

        $Query_SQL = mysqli_query($Connection_SQL, $getUserQuery);

        if ($Query_SQL) {
          $phpErrorMessage .= "Retrieved Users<br>";
          $User_Result = new User;

          // Check if user was real
          $Rows_Result = mysqli_num_rows($Query_SQL);
          if ($Rows_Result == 1){
            if ($Row_SQL = mysqli_fetch_array($Query_SQL, MYSQLI_ASSOC)){
              $User_Result->name = $Row_SQL['Name'];
              $User_Result->surnames = $Row_SQL['Surnames'];
              $User_Result->username = $Username_Request;

              $User_Result->birthdate = $Row_SQL['Birthdate'];
              $User_Result->lecturer = $Row_SQL['Lecturer'];
              $User_Result->gender = $Row_SQL['Gender'];

              $User_Result->startYear = $Row_SQL['StartYear'];
              $User_Result->picture = $Row_SQL['Filename'];
              $User_Result->course = $Row_SQL['CourseName'];
              $User_Result->level = $Row_SQL['Level'];
              $User_Result->country = $Row_SQL['CountryName'];
            }
          } else {
            $showErrorMessage = TRUE;
            $errorMessage = "User was not found";
          }
        }
      }
    }
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
      <div class="user">
        <div class="user-left">
          <h2><?php echo $errorMessage; ?></h2>
          <img id="user-picture" src="<?php echo $User_Result->getPicture("uploads/"); ?>" alt="">
          <div class="user-left-bottom">
            <div class="user-names">
              <h2><?php echo $User_Result->name; ?></h2>
              <h2><?php echo $User_Result->surnames; ?></h2>
            </div>
            <img id="user-gender" src="<?php echo $User_Result->getGenderPic("assets/icons/"); ?>" alt="">
          </div>
        </div>
        <div class="user-right">
          <h4>Username: <?php echo $User_Result->username; ?></h4>
          <br>
          <h4>Country: <?php echo $User_Result->country; ?></h4>
          <br>
          <?php print $User_Result->getCourseInfo(); ?>
          <h4>Started on <?php echo $User_Result->startYear; ?></h4>
          <br>
          <h4>Birthday: <?php echo $User_Result->birthdate; ?></h4>
        </div>
      </div>
    </main>
    <footer>
      <?php include "{$file_root}templates/footer.php"; ?>
    </footer>
  </body>
</html>
