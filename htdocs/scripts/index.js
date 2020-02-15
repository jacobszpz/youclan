count = 999;

$(function(){
  $(".hero-hover").css("display", "flex").hide();
  $(".main-counter").children().hide();

  $(".main-hero").hide();
  $(".main-hero").fadeIn(3000);

  $(".main-hero").hover(function() {
    $(".hero-hover").fadeIn();
  }, function() {
    //$(".hero-hover").fadeOut();
  });

  setInterval(increaseCounter, 1000);
});

function showCounter() {
  $(".main-counter").children().fadeIn(1000);
}

function increaseCounter() {
  count += Math.floor((Math.random() * 5) + 1);
  document.getElementById("main-counter").textContent = count.toLocaleString();
}
