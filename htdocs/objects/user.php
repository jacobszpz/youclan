<?php
class User {
  public $name;
  public $surnames;
  public $username;
  public $birthdate;
  public $lecturer;
  public $gender;
  public $startYear;
  public $picture;
  public $course;
  public $level;
  public $country;

  public $defPic = "assets/defaultProfile.svg";

  function getCourseInfo() {
    $courseHTML = "";

    if ($this->$lecturer == 0) {
      $courseHTML = "<h4>Course: {$this->level} in {$this->course}</h4>";
    }

    return $courseHTML;
  }


  function getPicture($upload_dir) {
    $contactPicture = (isset($this->picture) ? $upload_dir . $this->picture : $this->defPic);

    return "/$contactPicture";
  }

  function getGenderPic($asset_dir) {
    $genderPic = "male.svg";

    if ($this->gender == 1) {
      $genderPic = "female.svg";
    }

    return "/{$asset_dir}{$genderPic}";
  }
}
?>
