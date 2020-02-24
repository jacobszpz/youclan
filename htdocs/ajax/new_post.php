<?php
  $current_page = "new_post";

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

  function createPostHTML($author, $username, $pf_picture, $content, $picture, $post_id) {
    global $file_root;
    $imageHTML = "";

    if ($picture != "uploads/") {
      $imageHTML =
      "<div class=\"post-image\">
        <a href=\"{$file_root}{$picture}\">
          <img class=\"post-img\" src=\"{$file_root}{$picture}\" alt=\"\">
        </a>
      </div>";
    }

    $postHTML =
    "<li>
      <div class=\"post\">
        <div class=\"post-info\">
          <img class=\"post-user-img\" src=\"{$file_root}{$pf_picture}\" alt=\"\">
          <div class=\"post-user-info\">
            <span class=\"post-user-name\"><a href=\"{$file_root}user.php?user={$username}\">$author</a></span>
            <span class=\"post-time\">A moment ago</span>
          </div>
        </div>
        <div class=\"post-content\">
          <div class=\"post-text\">
            <span>{$content}</span>
          </div>
          $imageHTML
          <div class=\"post-roses\" post-id=\"$post_id\">
            <img class=\"post-rose-icon\" src=\"{$file_root}assets/icons/rose.svg\" alt=\"\">
            <span class=\"post-roses-no\">0</span>
          </div>
        </div>
        <div class=\"post-comments\">
          <span class=\"comments-title\">COMMENTS</span>
          <div class=\"new-comment\">
            <form class=\"new-comment-form\" action=\"\" method=\"post\">
              <div class=\"new-comment-inside\">
                <input type=\"text\" class=\"new-comment-input\" name=\"\" value=\"\" placeholder=\"Add to the conversation...\" required>
                <input type=\"submit\" class=\"new-post-button new-comment-button\" name=\"\" value=\"SEND\">
              </div>
            </form>
          </div>
        </div>
      </div>
    </li>";

    return $postHTML;
  }

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
    $newPostHTML = createPostHTML("$Name_Session $Surnames_Session", $Username_Session, $Picture_Session, $PostContent_Request, "uploads/{$NewPictureFilename}", $postID);

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
