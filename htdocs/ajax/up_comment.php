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
    $CommentID_Request = (int) filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);

    // TODO: Validate User Has Not Upvoted Post
    $voteCheckQuery = "SELECT * FROM c_roses WHERE UserID = $ID_Session AND CommentID = $CommentID_Request";

    $Query_SQL = mysqli_query($Connection_SQL, $voteCheckQuery);

    $phpErrorMessage .= "Retrieved Roses<br>";

    $Rows_Result = mysqli_num_rows($Query_SQL);

    if ($Rows_Result == 0) {
      $registerRoseQuery = "INSERT INTO c_roses (CommentID, UserID) VALUES ($CommentID_Request, $ID_Session)";
      mysqli_query($Connection_SQL, $registerRoseQuery);

      $upvoteQuery = "UPDATE comments SET Roses = Roses + 1 WHERE ID = $CommentID_Request";
      mysqli_query($Connection_SQL, $upvoteQuery);
      $phpErrorMessage .= "Updated roses<br>";

    } else {
      // User already voted
      $errorType = 1;
    }

    $errorMessage = getErrorMessage($errorType);

    $returnDict = ['error' => $errorMessage];

    echo json_encode($returnDict);
  }
?>