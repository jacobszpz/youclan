$(function(){
  $(".hero-hover").css("display", "flex").hide();

  $(".main-hero").hover(function() {
    $(".hero-hover").fadeIn();
  }, function() {
    //$(".hero-hover").fadeOut();
  }); });
