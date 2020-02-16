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
});
