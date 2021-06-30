
$( ".receiptExtras" ).hide();

var currentRankReceipt = $("#currentRank-text").text();
var desiredRankReceipt = $("#desiredRank-text").text();

var gameType = $("#game_type option:selected").val();
var gameAmount = $("#game_amount").val();

var lastSeasonRank = $('#lastSeasonRank option:selected').text();

var orderType = $('input[name=order_type]').val();

if (orderType == "solo") {
  $('#boosting_desc').text(currentRankReceipt  + " -> " + desiredRankReceipt);
}

if (orderType == "duo") {
  $('#boosting_desc').text(currentRankReceipt  + " " + gameType + " x " + gameAmount);
}

if (orderType == "placements") {
  $('#boosting_desc').text(lastSeasonRank  + " Placements x " + gameAmount);
}

if (orderType == "wins") {
  $('#boosting_desc').text("Normal Wins x " + gameAmount);
}

function updateReceipt() {

  var currentRankReceipt = $("#currentRank-text").text();
  var desiredRankReceipt = $("#desiredRank-text").text();

  var gameType = $("#game_type option:selected").val();
  var gameAmount = $("#game_amount").val();

  var lastSeasonRank = $('#lastSeasonRank option:selected').text();

  var orderType = $('input[name=order_type]').val();

  if (orderType == "solo") {
    $('#boosting_desc').text(currentRankReceipt  + " -> " + desiredRankReceipt);
  }

  if (orderType == "duo") {
    $('#boosting_desc').text(currentRankReceipt  + " -> " + gameType + " x " + gameAmount);
  }

  if (orderType == "placements") {
    $('#boosting_desc').text(lastSeasonRank  + " Placements x " + gameAmount);
  }

  if (orderType == "wins") {
    $('#boosting_desc').text("Normal Wins x " + gameAmount);
  }

}
