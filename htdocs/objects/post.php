<?php
class Post {
  // Declare  properties
  public $id;
  public $author;
  public $authorUN;
  public $authorPicture;

  public $defPic = "assets/defaultProfile.svg";

  public $content;
  public $picture;
  public $time;
  public $roses;
  public $comments;

  function postData($post_id, $text, $image, $time, $roses) {
    $this->id = $post_id;
    $this->content = $text;
    $this->picture = $image;
    $this->time = $time;
    $this->roses = $roses;
  }

  function authorData($user, $name, $pf_picture) {
    $this->author = $name;
    $this->authorUN = $user;
    $this->authorPicture = $pf_picture;
  }

  function createPostHTML($file_root, $upload_dir) {
    $smartText = $this->mentions($this->content, $file_root);

    $imageHTML = "";

    $contactPicture = (!empty($this->authorPicture) ? $upload_dir . $this->authorPicture : $this->defPic);

    if (!empty($this->picture)) {
      $imageHTML =
      "<div class=\"post-image\">
        <a href=\"{$file_root}{$upload_dir}{$this->picture}\">
          <img class=\"post-img\" src=\"{$file_root}{$upload_dir}{$this->picture}\" alt=\"\">
        </a>
      </div>";
    }

    $postHTML =
    "<li>
      <div class=\"post\">
        <div class=\"post-info\">
          <div class=\"post-user-img-wrapper\">
            <img class=\"post-user-img\" src=\"{$file_root}{$contactPicture}\" alt=\"\">
          </div>
          <div class=\"post-user-info\">
            <span class=\"post-user-name\"><a href=\"{$file_root}user.php?user={$this->authorUN}\">$this->author</a></span>
            <span class=\"post-time\">$this->time</span>
          </div>
        </div>
        <div class=\"post-content\">
          <div class=\"post-text\">
            <span>$smartText</span>
          </div>
          $imageHTML
          <div class=\"post-roses\" post-id=\"$this->id\">
            <img class=\"post-rose-icon\" src=\"{$file_root}assets/icons/rose.svg\" alt=\"\">
            <span class=\"post-roses-no\">$this->roses</span>
          </div>
        </div>
        <div class=\"post-comments\">
          <span class=\"comments-title\">COMMENTS</span>
          $this->comments
          <div class=\"new-comment\">
            <form class=\"new-comment-form\" action=\"{$file_root}ajax/new_comment.php\" method=\"post\">
              <div class=\"new-comment-inside\">
                <input type=\"hidden\" name=\"post_id\" value=\"$this->id\">
                <input type=\"text\" class=\"new-comment-input\" name=\"comment_text\" value=\"\" placeholder=\"Add to the conversation...\" required>
                <input type=\"submit\" class=\"new-post-button new-comment-button\" name=\"\" value=\"SEND\">
              </div>
            </form>
          </div>
        </div>
      </div>
    </li>";

    return $postHTML;
  }

  function mentions($text, $file_root) {
    if (!empty($text)) {
      $atPos = strpos($text, '@');

      if($atPos !== false) {
        $pattern = '/(\@)([a-zA-Z0-9\-\.]+)/';
        $replacement = "<a href=\"{$file_root}user.php?user=$2\">$1$2</a>";
        $text = preg_replace($pattern, $replacement, $text);
      }
    }

    return $text;
  }
}
?>
