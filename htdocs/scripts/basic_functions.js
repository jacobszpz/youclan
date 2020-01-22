function togglePasswordVisibility(input_field) {
  if (input_field.type === "password") {
    input_field.type = "text";
  } else {
    input_field.type = "password";
  }
}
