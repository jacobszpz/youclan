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
});

var npf = $('#new-post-form');

npf.submit(function (e) {
  e.preventDefault();

  $.ajax({
    type: npf.attr('method'),
    url: npf.attr('action'),
    data: npf.serialize(),
    dataType: 'JSON',

    success: function (data) {
      console.log('Submission was successful.');
      console.log(data);
      var error = response["error"];
      if (error == "") {
        // No errors on ajax request
        // PROCEED
        var new_post = response["new_post"];
        $("#feed ul").append(new_post);
      }
    },

    error: function (data) {
      console.log('An error occurred.');
      console.log(data);
    },
  });
});
