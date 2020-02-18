<?php
  $current_page = "upvote";

  function getRoot($DIR) {
    $path_parts = explode('htdocs', $DIR);
    $path_deep = substr_count($path_parts[1], "/");
    $file_root = "";

    for ($i=0; $i < $path_deep; $i++) {
      $file_root .= "../";
    }

    return $file_root;
  }

  $file_root = getRoot(__DIR__);

  include "{$file_root}templates/php_init.php";

  // To Test Logged In Redirect
  // $loggedIn = TRUE;

  $debuggingActivated = FALSE;
  $phpErrorMessage = "Debugging Activated<br>";

  $noReturn = FALSE;

  // Do not show page if user is not logged in
  if (!$loggedIn || $Lost_Session || !$Verified_Session ||!$Setup_Session) {
    $noReturn = TRUE;
  }

  // We need database connection earlier due to retrieving values
  // for country and courses dropdown
  require_once "{$file_root}database/config.php";
  require_once "{$file_root}database/conn.php";

  $errorType = 0;

  function getErrorMessage($type) {
    $errorString = "";

    if ($type == 1) {
      $errorString = "failed";
    }

    return $errorString;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) && !$noReturn) {
    $phpErrorMessage .= "Form Method is POST<br>";
    $formSent = TRUE;

    // Set Variables
    $PostID_Request = (int) filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    // TODO: Validate User Has Not Upvoted Post
    $voteCheckQuery = "SELECT * FROM roses WHERE UserID = '$ID_Session' AND PostID = '$PostID_Request'";

    $Query_SQL = mysqli_query($voteCheckQuery);

    $phpErrorMessage .= "Retrieved Roses<br>";

    $Rows_Result = mysqli_num_rows($Query_SQL);

    if ($Rows_Result == 0) {
      $upvoteQuery = "UPDATE posts SET Roses = Roses + 1 WHERE PostID = $PostID_Request";

      mysqli_query($upvoteQuery);
      $phpErrorMessage .= "Updated roses<br>";

    } else {
      // User already voted
      $errorType = 1;
    }

    $errorMessage = getErrorMessage($errorType);

    $returnDict = ['error' => $errorMessage];
  }
?>
