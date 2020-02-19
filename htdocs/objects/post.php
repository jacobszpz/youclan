<?php
class Post {
  // Declare  properties
  public $id;
  public $author;
  public $authorUN;
  public $authorPicture;

  public $content;
  public $picture;
  public $time;
  public $roses;

  function createPostHTML($file_root, $upload_dir) {
    $this->name;

    $imageHTML = "";

    if ($this->picture != NULL) {
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
          <img class=\"post-user-img\" src=\"{$file_root}{$upload_dir}{$this->authorPicture}\" alt=\"\">
          <div class=\"post-user-info\">
            <span class=\"post-user-name\"><a href=\"{$file_root}user.php?user={$this->authorUN}\">$this->author</a></span>
            <span class=\"post-time\">$this->time</span>
          </div>
        </div>
        <div class=\"post-content\">
          <div class=\"post-text\">
            <span>{$this->content}</span>
          </div>
          $imageHTML
          <div class=\"post-roses\" post-id=\"$this->id\">
            <img class=\"post-rose-icon\" src=\"{$file_root}assets/icons/rose.svg\" alt=\"\">
            <span class=\"post-roses-no\">$this->roses</span>
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
}
?>
