<?php
  $current_page = "logout";

  $path_parts = explode('htdocs', __DIR__);
  $path_deep = substr_count($path_parts[1], "/");
  $file_root = "";

  for ($i=0; $i < $path_deep; $i++) {
    $file_root .= "../";
  }

  // Initialize the session
  session_start();

  // Unset all of the session variables
  $_SESSION = array();

  // Destroy the session.
  session_destroy();

  // Redirect to login page
  header("location: {$file_root}");
  exit;
?>
