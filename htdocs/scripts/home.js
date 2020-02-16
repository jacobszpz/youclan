$(function(){
  $(".header-account-dd").hide();

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
    $(".contact-list").hide(250);
    $(".menu-contacts").animate({width: 'toggle'}, 350, function() {
      $(".contact-list").show(150);
    });
  });
});
