<?php
  $current_page = "new_comment";

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

  $debuggingActivated = TRUE;
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
  require "{$file_root}objects/comment.php";

  $errorType = 0;

  /*
    ERROR DEFINITIONS:

    0: No Errors

    1: Incorrect String Length
    2: Upload failed
    3: Picture Has Incorrect Format
    4: Could not move file even though upload was ok
    5: Other error on PYMFURTS

  */

  function getErrorMessage($type) {
    $errorString = "";

    switch ($type) {
      case 0:
        $errorString = '';
        break;

      case 1:
        $errorString = 'error_post_length';
        break;

      case 100:
        $errorString = 'error_database_connection';
        break;
    }

    return $errorString;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) && !$noReturn) {
    $phpErrorMessage .= "Form Method is POST<br>";
    $formSent = TRUE;

    // Set Variables
    $CommentContent_Request = filter_input(INPUT_POST, 'comment_text', FILTER_SANITIZE_STRING);
    $PostID_Request = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_STRING);

    $NewPictureFilename = "";

    $phpErrorMessage .= "Variables From Request Read<br>";

    # DATA VALIDATION #

    if (strlen($CommentContent_Request) > 1000 || empty($CommentContent_Request)) {
      $errorType = 1;
    }

    // If picture either not included or correctly uploaded
    if ($errorType == 0) {

      $newPostQuery = "INSERT INTO comments
      (ParentID, PosterID, Content) VALUES ($PostID_Request, $ID_Session, '$CommentContent_Request')";

      $Query_SQL = mysqli_query($Connection_SQL, $newPostQuery);
      $phpErrorMessage .= "Upload registered in DB<br>";
      $commentID = mysqli_insert_id($Connection_SQL);
      $commentComplete = TRUE;
    }

    $errorMessage = $main_strings[getErrorMessage($errorType)];

    $newComment = new Comment;
    $newComment->authorData($Username_Session, "$Name_Session $Surnames_Session", $Picture_Session);
    $newComment->commentData($commentID, $CommentContent_Request, "A moment ago", "0");

    $newCommentHTML = $newComment->createCommentHTML($file_root, "uploads/");

    $returnArray = ['error' => $errorMessage];

    if ($commentComplete) {
      $returnArray['new_comment'] = $newCommentHTML;
    }

    if ($debuggingActivated) {
      $returnArray['debug'] = $phpErrorMessage;
    }

    header('Content-Type: application/json');

    // Return Error Code And New Post
    echo json_encode($returnArray);
  }
?>
