$(function(){
  $(".header-account-dd").hide();
  $(".menu-contacts").hide();

  $(".header-account").hover(
    function() {
      $(".dropdown-arrow").addClass('rotated');
      $(".header-account-dd").stop( true, true ).fadeIn();
    }, function() {
      $(".dropdown-arrow").removeClass('rotated');
      $(".header-account-dd").stop( true, true ).fadeOut();
    }
  );

  $(".menu-close").click(function() {
    $(".contact-list").hide(50);
    $(".menu-contacts").animate({width: 'toggle'}, 180, function() {
      $(".contact-list").show();
    });
  });

  $("#new-post-form").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var form = $(this);

    $.ajax({
      type: "POST",
      url: form.attr('action'),
      data: formData,
      dataType: 'JSON',

      success: function (data) {
        console.log('Submission was successful.');
        console.log(data);
        var error = data["error"];
        if (error == null) {
          // No errors on ajax request
          // PROCEED
          var new_post = data["new_post"];
          $("#feed ul").append(new_post);
        } else {
          // Error ocurred, show it?
          alert(error);
        }
      },

      error: function (data) {
        console.log('An error occurred.');
        console.log(data);
      },
      cache: false,
      contentType: false,
      processData: false
    });
  });

});
