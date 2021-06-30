
$(document).ready(function() {
  var url = window.location.hash;

  if (url == "#login") {

  } else {
    $( "#login" ).hide();
  }

  if (url == "#register") {

  } else {
    $( "#register" ).hide();
  }

  if (url == "#change") {

  } else {
    $( "#change" ).hide();
  }

  if (url == "#forgot") {
    $( "#login" ).hide();
  } else {
    $( "#forgot" ).hide();
  }

});

$(window).keydown(function(event){
  if(event.keyCode == 13) {
    event.preventDefault();
    return false;
  }
});

function sliderUpdate() {
  $("#game_amount_label").text($("#game_amount").val());
}

$(".login-toggle").click(function() {
  $("#login").fadeToggle();
});

$(".register-toggle").click(function() {
  $("#register").fadeToggle();
});

$(".forgot-toggle").click(function() {
  $("#login").fadeToggle();
  $("#forgot").fadeToggle();
});

$(".current-rank-select-toggle").click(function() {
  $("#current-rank-select").fadeToggle();
});

$(".desired-rank-select-toggle").click(function() {
  $("#desired-rank-select").fadeToggle();
});

$("#dropdown1-click").click(function(){

  if ($("#dropdown2").css("display") == "block") {
    $("#dropdown2").slideToggle();
  }

  if ($("#dropdown3").css("display") == "block") {
    $("#dropdown3").slideToggle();
  }

  if ($("#dropdown4").css("display") == "block") {
    $("#dropdown4").slideToggle();
  }

  if ($("#dropdown5").css("display") == "block") {
    $("#dropdown5").slideToggle();
  }

  if ($("#dropdown6").css("display") == "block") {
    $("#dropdown6").slideToggle();
  }

  $("#dropdown1").slideToggle();
});

$("#dropdown2-click").click(function(){

  if ($("#dropdown1").css("display") == "block") {
    $("#dropdown1").slideToggle();
  }

  if ($("#dropdown3").css("display") == "block") {
    $("#dropdown3").slideToggle();
  }

  if ($("#dropdown4").css("display") == "block") {
    $("#dropdown4").slideToggle();
  }

  if ($("#dropdown5").css("display") == "block") {
    $("#dropdown5").slideToggle();
  }

  if ($("#dropdown6").css("display") == "block") {
    $("#dropdown6").slideToggle();
  }

  $("#dropdown2").slideToggle();
});

$("#dropdown3-click").click(function(){

  if ($("#dropdown1").css("display") == "block") {
    $("#dropdown1").slideToggle();
  }

  if ($("#dropdown2").css("display") == "block") {
    $("#dropdown2").slideToggle();
  }

  if ($("#dropdown4").css("display") == "block") {
    $("#dropdown4").slideToggle();
  }

  if ($("#dropdown5").css("display") == "block") {
    $("#dropdown5").slideToggle();
  }

  if ($("#dropdown6").css("display") == "block") {
    $("#dropdown6").slideToggle();
  }

  $("#dropdown3").slideToggle();
});

$("#dropdown4-click").click(function(){

  if ($("#dropdown1").css("display") == "block") {
    $("#dropdown1").slideToggle();
  }

  if ($("#dropdown2").css("display") == "block") {
    $("#dropdown2").slideToggle();
  }

  if ($("#dropdown3").css("display") == "block") {
    $("#dropdown3").slideToggle();
  }

  if ($("#dropdown5").css("display") == "block") {
    $("#dropdown5").slideToggle();
  }

  if ($("#dropdown6").css("display") == "block") {
    $("#dropdown6").slideToggle();
  }

  $("#dropdown4").slideToggle();
});

$("#dropdown5-click").click(function(){

  if ($("#dropdown1").css("display") == "block") {
    $("#dropdown1").slideToggle();
  }

  if ($("#dropdown2").css("display") == "block") {
    $("#dropdown2").slideToggle();
  }

  if ($("#dropdown3").css("display") == "block") {
    $("#dropdown3").slideToggle();
  }

  if ($("#dropdown4").css("display") == "block") {
    $("#dropdown4").slideToggle();
  }

  if ($("#dropdown6").css("display") == "block") {
    $("#dropdown6").slideToggle();
  }

  $("#dropdown5").slideToggle();
});

$("#dropdown6-click").click(function(){

  if ($("#dropdown1").css("display") == "block") {
    $("#dropdown1").slideToggle();
  }

  if ($("#dropdown2").css("display") == "block") {
    $("#dropdown2").slideToggle();
  }

  if ($("#dropdown3").css("display") == "block") {
    $("#dropdown3").slideToggle();
  }

  if ($("#dropdown4").css("display") == "block") {
    $("#dropdown4").slideToggle();
  }

  if ($("#dropdown5").css("display") == "block") {
    $("#dropdown5").slideToggle();
  }

  $("#dropdown6").slideToggle();
});


/*$(function () {
  $(document).scroll(function () {
    var $nav = $("nav");
    $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
  });
});*/
