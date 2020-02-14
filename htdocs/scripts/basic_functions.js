function togglePasswordVisibility(input_field) {
  if (input_field.type === "password") {
    input_field.type = "text";
  } else {
    input_field.type = "password";
  }
}

function readURL(input, imageSelector) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $(imageSelector).attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}
