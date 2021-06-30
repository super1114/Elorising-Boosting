function flipCurrent(rank) {
  $("#current-rank-select").fadeToggle();
  $("#currentRank-text").text($(rank).next().text());
  $("#currentRank").attr("src", $(rank).attr("url"));
}

function flipDesired(rank) {
  $("#desired-rank-select").fadeToggle();
  $("#desiredRank-text").text($(rank).next().text());
  $("#desiredRank").attr("src", $(rank).attr("url"));
}
