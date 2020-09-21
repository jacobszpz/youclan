<?php
  $file_root = $_SERVER['DOCUMENT_ROOT'] . "/";
  $quiz = json_decode(file_get_contents($file_root . '4wc_api/json/questions_01.json'), TRUE);

  $randomise = filter_input(INPUT_GET, 'randomise', FILTER_VALIDATE_BOOLEAN);

  if ($randomise) {
    foreach ($quiz as $key => $question) {
      $correct = $question['choices'][$question['correct']];
      shuffle($question['choices']);
      $question['correct'] = array_search($correct, $question['choices'], TRUE);
      $quiz[$key] = $question;
    }
  }

  echo json_encode($quiz);
?>
