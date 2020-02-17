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
