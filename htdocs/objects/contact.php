<?php
class Contact {
  public $name;
  public $surname;
  public $picture;

  public $username;

  public $defPic = "assets/defaultProfile.svg";

  function createContactHTML($file_root, $upload_dir) {
    $contactPicture = (!empty($this->picture) ? $this->picture : $defPic);
    $contactHTML =
    "<li>
      <a href=\"{$file_root}user.php?user={$this->username}\">
        <div class=\"contact-instance\">
          <img class=\"contact-image\" src=\"{$file_root}{$upload_dir}{$contactPicture}\" alt=\"Profile picture of {$this->name}\">
          <span class=\"contact-name\">{$this->name} {$this->surname}</span>
        </div>
      </a>
    </li>";

    return $contactHTML;
  }
}
?>
