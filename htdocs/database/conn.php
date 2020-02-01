<?php
  if (function_exists('mysqli_connect')) {
    $Connection_SQL = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
  } else {
    $Connection_SQL = FALSE;
  }
?>
