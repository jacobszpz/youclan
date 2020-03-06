<?php
class Comment {
  // Declare  properties
  public $id;
  public $author;
  public $authorUN;
  public $authorPicture;

  public $content;
  public $time;
  public $roses;

  function authorData($un, $n, $pfp) {
    $this->authorUN = $un;
    $this->author = $n;
    $this->authorPicture = $pfp;
  }

  function commentData($comment_id, $text, $time, $roses) {
    $this->id = $comment_id;
    $this->content = $text;
    $this->time = $time;
    $this->roses = $roses;
  }

  function createCommentHTML($upload_dir) {
    $commentHTML =
    "<div class=\"comment\">
      <div class=\"comment-inside\">
        <div class=\"comment-user-img-w\">
          <img class=\"comment-user-img\" src=\"/{$upload_dir}{$this->authorPicture}\" alt=\"\">
        </div>
        <div class=\"comment-body\">
          <div class=\"comment-content\">
            <span class=\"comment-author\"><a href=\"/user.php?user={$this->authorUN}\">$this->author</a></span>
            <span class=\"comment-text\">$this->content</span>
          </div>
          <div class=\"comment-info\">
            <span class=\"comment-time\">$this->time</span>
            <div class=\"comment-roses\" comment-id=\"$this->id\">
              <img class=\"comment-rose-icon\" src=\"/assets/icons/rose.svg\" alt=\"\">
              <span class=\"comment-roses-no\">$this->roses</span>
            </div>
          </div>
        </div>
      </div>
    </div>";

    return $commentHTML;
  }
}
?>
