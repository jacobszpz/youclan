<?php
  if (function_exists('mysqli_connect')) {
    $Connection_SQL = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_set_charset($Connection_SQL, "utf8");
  } else {
    $Connection_SQL = FALSE;
  }
?>
