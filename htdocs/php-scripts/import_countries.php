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


  $file_name = "../texts/countries.txt";
  $file = file($file_name);

  if ($Connection_SQL !== FALSE && $Auth_Request == "PERHAPS") {
    foreach ($file as $key => $country) {
      $country = trim($country);

      $countryInsertion_Query = "INSERT INTO countries (Name) VALUES ('$country')";

      $Query_SQL = mysqli_query($Connection_SQL, $countryInsertion_Query);

      if ($Query_SQL) {
        print ("$country was added to the db<br>");
      }
    }
  }
?>
