<?php
// Open File with every course
// For every line, insert it into db

  require_once "../database/config.php";

  if (function_exists('mysqli_connect')) {
    $Connection_SQL = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
  } else {
    $Connection_SQL = FALSE;
  }

  $Auth_Request = trim(filter_input(INPUT_GET, 'auth', FILTER_SANITIZE_STRING));


  $file_name = "../texts/courses.txt";
  $file = file($file_name);

  if ($Connection_SQL !== FALSE && $Auth_Request == "PERHAPS") {
    foreach ($file as $key => $course) {

      $courseInsertion_Query = "INSERT INTO courses (Name) VALUES ('$course')";

      $Query_SQL = mysqli_query($Connection_SQL, $courseInsertion_Query);

      if ($Query_SQL) {
        print ("$course was added to the db<br>");
      }
    }
  }
?>
