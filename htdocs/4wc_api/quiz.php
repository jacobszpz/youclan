<?php
  $file_root = $_SERVER['DOCUMENT_ROOT'] . "/";
  $quiz = json_decode(file_get_contents($file_root . '4wc_api/json/questions_01.json'), TRUE);

  $randomise = filter_input(INPUT_GET, 'randomise', FILTER_VALIDATE_BOOLEAN);

  if ($randomise) {
    foreach ($quiz as $question) {
      $correct = $quiz['choices'][$quiz['correct']];
      shuffle($quiz['choices']);
      $quiz['correct'] = array_search($correct, $quiz['choices'], TRUE);
    }
  }

  echo json_encode($quiz);
?>
