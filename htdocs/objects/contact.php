<?php
class Contact {
  public $name;
  public $surname;
  public $picture;

  public $username;

  public $defPic = "assets/defaultProfile.svg";

  function createContactHTML($file_root, $upload_dir) {
    $contactPicture = (isset($this->picture) ? $upload_dir . $this->picture : $this->defPic);
    $contactHTML =
    "<li>
      <a href=\"{$file_root}user.php?user={$this->username}\">
        <div class=\"contact-instance\">
          <div class=\"contact-image-wrapper\">
            <img class=\"contact-image\" src=\"{$file_root}{$contactPicture}\" alt=\"Profile picture of {$this->name}\">
          </div>
          <span class=\"contact-name\">{$this->name} {$this->surname}</span>
        </div>
      </a>
    </li>";

    return $contactHTML;
  }
}
?>
