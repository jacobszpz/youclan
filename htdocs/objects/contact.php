<?php
class Contact {
  public $name;
  public $surname;
  public $picture;

  public $username;

  public $defPic = "assets/defaultProfile.svg";

  function createContactHTML($upload_dir) {
    $contactPicture = (isset($this->picture) ? $upload_dir . $this->picture : $this->defPic);
    $contactHTML =
    "<li>
      <a href=\"/user.php?user={$this->username}\">
        <div class=\"contact-instance\">
          <div class=\"contact-image-wrapper\">
            <img class=\"contact-image\" src=\"/{$contactPicture}\" alt=\"Profile picture of {$this->name}\">
          </div>
          <span class=\"contact-name\">{$this->name} {$this->surname}</span>
        </div>
      </a>
    </li>";

    return $contactHTML;
  }
}
?>
