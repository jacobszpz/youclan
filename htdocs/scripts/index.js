$(function(){
  $(".hero-hover").css("display", "flex").hide();

  $(".main-hero").hide();
  $(".main-hero").fadeIn(3000);

  $(".main-hero").hover(function() {
    $(".hero-hover").fadeIn();
  }, function() {
    //$(".hero-hover").fadeOut();
  });
});
