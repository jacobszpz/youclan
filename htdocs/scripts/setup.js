function toggleStudentFields() {
  $("#setup-course-type").toggle(300);
  $("#setup-course").toggle(300);
}

$(function(){
  if ($('input[name=lecturer]:checked', '#lecturer-section').val() == 1) {
    toggleStudentFields();
  }

  $("#setup-pfp").change(function() {
    readURL(this, "#pfp-preview");
    $("#pfp-preview").show(300);
    $("#pfp-preview-caption").show(300);
  });
});
