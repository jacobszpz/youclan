<?php
  $current_page = "new_post";

  $file_root = substr(__FILE__, 0, strpos(__FILE__, 'htdocs') + 7);
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
  require "{$file_root}objects/post.php";

  $pictureIncluded = FALSE;
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

      case 2:
      case 3:
      case 4:
      case 5:
        $errorString = 'error_file_upload';
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
    $PostContent_Request = filter_input(INPUT_POST, 'post_text', FILTER_SANITIZE_STRING);

    $Picture_Request = $_FILES['post_picture'];
    $PictureName = trim(filter_var($Picture_Request['name'], FILTER_SANITIZE_STRING));
    $PictureTmpName = trim(filter_var($Picture_Request['tmp_name'], FILTER_SANITIZE_STRING));

    $FileType = trim(filter_var($Picture_Request['type'], FILTER_SANITIZE_STRING));
    $FileSize = (int) filter_var($Picture_Request['size'], FILTER_SANITIZE_NUMBER_INT);
    $FileCode = (int) filter_var($Picture_Request['error'], FILTER_SANITIZE_NUMBER_INT);

    $NewPictureFilename = "";

    $phpErrorMessage .= "Variables From Request Read<br>";

    # DATA VALIDATION #

    if (strlen($PostContent_Request) > 10000 || empty($PostContent_Request)) {
      $errorType = 1;
    }

    // Picture Validation

    if ($FileCode == 0 && $errorType == 0) {
      $phpErrorMessage .= "Upload has been successful<br>";

      $goodFileExtensions = ['jpeg', 'jpg', 'png', 'webp', 'gif'];

      $FileExtension = end(explode(".", $PictureName));

      if (in_array(strtolower($FileExtension), $goodFileExtensions)) {

        $NewFilename = md5(uniqid(rand(), true)) . "." . $FileExtension;
        $FileChecksum = hash_file("sha256", $PictureTmpName);

        if (move_uploaded_file($PictureTmpName, "{$file_root}uploads/$NewFilename")) {
          $errorType = 5;

          $phpErrorMessage .= "Upload moved under new secret name<br>";

          $newUploadQuery = "INSERT INTO uploads
          (OriginalFilename, Filename, UploadedBy, FileChecksum, Filesize) VALUES
          ('$PictureName', '$NewFilename', $ID_Session, '$FileChecksum', $FileSize)";

          $Query_SQL = mysqli_query($Connection_SQL, $newUploadQuery);

          $phpErrorMessage .= "Upload registered in DB<br>";

          $uploadID = mysqli_insert_id($Connection_SQL);

          if ($uploadID != 0) {
            $ID_Upload = $uploadID;
            $errorType = 0;
            $pictureIncluded = TRUE;
            $NewPictureFilename = $NewFilename;
          }

          $showErrorMessage = FALSE;
        } else {
          $errorType = 4;
        }
      } else {
        $errorType = 3;
      }
    } else {
      if ($errorType == 0 && $FileCode != 4) {
        $errorType = 2;
      }
    }

    // If picture either not included or correctly uploaded
    if ($errorType == 0) {
      $picQ = "";
      $picV = "";

      if ($pictureIncluded) {
        $picQ = ", ImageID";
        $picV = ", $ID_Upload";
      }

      $newPostQuery = "INSERT INTO posts
      (PosterID, Content{$picQ}) VALUES ($ID_Session, '$PostContent_Request'{$picV})";

      $Query_SQL = mysqli_query($Connection_SQL, $newPostQuery);
      $phpErrorMessage .= "Upload registered in DB<br>";
      $postID = mysqli_insert_id($Connection_SQL);
      $postComplete = TRUE;
    }

    $errorMessage = $main_strings[getErrorMessage($errorType)];

    $newPost = new Post;
    $newPost->authorData($Username_Session, "$Name_Session $Surnames_Session", $Picture_Session, true);
    $newPost->postData($postID, $PostContent_Request, $NewPictureFilename, "A moment ago", "0");

    $newPostHTML = $newPost->createPostHTML("");

    $returnArray = ['error' => $errorMessage];

    if ($postComplete) {
      $returnArray['new_post'] = $newPostHTML;
    }

    if ($debuggingActivated) {
      $returnArray['debug'] = $phpErrorMessage;
    }

    header('Content-Type: application/json');

    // Return Error Code And New Post
    echo json_encode($returnArray);
  }
?>
