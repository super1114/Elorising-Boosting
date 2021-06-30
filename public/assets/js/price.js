
var price = 0;
var extras = 0;
var fullPrice = 0;

function getPrice() {

  var costs = [ 7,7,8,9,10,
                11,11,11,11,15,
                15,15,15,15,18,
                20,20,22,22,25,
                40,50,60,78,110];

  var getCurrentRank = $('input[name=currentRank]:checked', '#solo-boosting-form').val();
  var getDesiredRank = $('input[name=desiredRank]:checked', '#solo-boosting-form').val();

  current = parseInt(getCurrentRank);
  desired = parseInt(getDesiredRank);

  if (current < desired) {
    price = 0;
    for (i = current; i < desired; i++) {
        price += costs[i];
    }
  } else {
    price = 0;
  }
  $('#boosting_price').text(Math.round(price) + "$");

}

function getPriceDuo() {

  var getCurrentRank = $('input[name=currentRank]:checked', '#duo-boosting-form').val();
  var getGameAmount = $("#game_amount").val();

  currentRank = parseInt(getCurrentRank);
  gameAmount = parseInt(getGameAmount);

  if (currentRank > 19) {
    price = 12 * gameAmount;
    console.log("diamond");
  } else if (currentRank > 14) {
    price = 7 * gameAmount;
  } else if (currentRank > 9) {
    price = 5 * gameAmount;
  } else if (currentRank > 4) {
    price = 4 * gameAmount;
  } else if (currentRank >= 0) {
    price = 2.5 * gameAmount;
  }

  $('#boosting_price').text(Math.round(price) + "$");
  updatePrice();

}

function getPricePlacement() {

  var currentRank = $('#lastSeasonRank option:selected').val();
  var getGameAmount = $("#game_amount").val();

  gameAmount = parseInt(getGameAmount);

  if (currentRank == "Diamond") {
    price = 12 * gameAmount;
  }
  if (currentRank == "Platinum") {
    price = 6 * gameAmount;
  }
  if (currentRank == "Gold") {
    price = 5 * gameAmount;
  }
  if (currentRank == "Silver") {
    price = 5 * gameAmount;
  }
  if (currentRank == "Bronze") {
    price = 3.5 * gameAmount;
  }

  $('#boosting_price').text(Math.round(price) + "$");
  updatePrice();

}

function getPriceWins() {

  var getGameAmount = $("#game_amount").val();

  gameAmount = parseInt(getGameAmount);

  price = gameAmount * 1.5;

  $('#boosting_price').text(Math.round(price) + "$");
  updatePrice();

}

function updatePrice() {

  if (discount > 0) {

    fullPrice = (price + extras) * discount;
    $('.final-price').text(Math.round(fullPrice) + "$");
    document.getElementById("price-input").value = Math.round(fullPrice);

  } else {

    fullPrice = price + extras;
    $('.final-price').text(Math.round(fullPrice) + "$");
    document.getElementById("price-input").value = Math.round(fullPrice);

  }

}

$( "input[name=specific_spellposition]" ).click(function() {
  if ($('input[name=specific_spellposition]').is(':checked')) {
    extras = (extras + 5);
    $('.final-price').text(Math.round(price + extras) + "$");
    $( "#specific_spellposition_receipt" ).toggle(500);
  } else {
    extras = (extras - 5);
    $('.final-price').text(Math.round(price + extras) + "$");
    $( "#specific_spellposition_receipt" ).toggle(500);
  }
});

$( "input[name=favorite_champs]" ).click(function() {
  if ($('input[name=favorite_champs]').is(':checked')) {
    extras = extras + 10;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#favorite_champs_receipt" ).toggle(500);
  } else {
    extras = extras - 10;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#favorite_champs_receipt" ).toggle(500);
  }
});

$( "input[name=priority_completion]" ).click(function() {
  if ($('input[name=priority_completion]').is(':checked')) {
    extras = extras * 1.1;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#apriority_completion_receipt" ).toggle(500);
  } else {
    extras = extras / 1.1;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#apriority_completion_receipt" ).toggle(500);
  }
});

$( "input[name=coching]" ).click(function() {
  if ($('input[name=coching]').is(':checked')) {
    extras = extras + 20;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#coching_receipt" ).toggle(500);
  } else {
    extras = extras - 20;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#coching_receipt" ).toggle(500);
  }
});

$( "input[name=offlinemode]" ).click(function() {
  if ($('input[name=offlinemode]').is(':checked')) {
    extras = extras + 10;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#offlinemode_receipt" ).toggle(500);
  } else {
    extras = extras - 10;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#offlinemode_receipt" ).toggle(500);
  }
});


$( "input[name=account_warranty]" ).click(function() {
  if ($('input[name=account_warranty]').is(':checked')) {
    extras = extras + 10;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#account_warranty_receipt" ).toggle(500);
  } else {
    extras = extras - 10;
    $('.final-price').text(Math.round(extras) + "$");
    $( "#account_warranty_receipt" ).toggle(500);
  }
});
