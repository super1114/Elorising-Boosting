
var discount = 0;

function checkReferral() {

  var code = $('input[name=referral_code]').val();

  $.post( "http://elorising.com/referralCheck", { code: code } ).done(function( data ) {

    if (data == "<p>The referral code could not be found.</p>") {
      console.log( data );
    } else {

      var procentage = parseInt(data);
      discount = (100 - procentage) / 100;

      updatePrice();

    }

  });

}
