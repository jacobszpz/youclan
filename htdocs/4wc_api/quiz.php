<?php
  $file_root = $_SERVER['DOCUMENT_ROOT'] . "/";
  $quiz = json_decode(file_get_contents($file_root . '4wc_api/json/questions_01.json'), TRUE);
  echo json_encode($quiz);
?>
