
$( "#slide2" ).hide();
$( "#slide3" ).hide();

$( ".step1" ).click(function() {
  $( "#slide2" ).fadeToggle(500, function() {
    $( "#slide1" ).fadeToggle(500);
  });
});

$( ".step2" ).click(function() {
  $( "#slide1" ).fadeToggle(500, function() {
    $( "#slide2" ).fadeToggle(500);
  });
});

$( ".step2back" ).click(function() {
  $( "#slide3" ).fadeToggle(500, function() {
    $( "#slide2" ).fadeToggle(500);
    $( ".price" ).fadeToggle(500);
  });
});

$( ".step3" ).click(function() {
  $( ".price" ).fadeToggle(500);
  $( "#slide2" ).fadeToggle(500, function() {
    $( "#slide3" ).fadeToggle(500);
  });
});

$( ".step1text" ).click(function() {
  $('.step').text('Step 1');
});

$( ".step2text" ).click(function() {
  $('.step').text('Step 2');
});

$( ".step3text" ).click(function() {
  $('.step').text('Step 3');
});

$( ".step4text" ).click(function() {
  $('.step').text('Step 4');
});
