$('#password').focus();
$('#password').on('input', function() {
  var current = $(this).val();
  var results = zxcvbn(current);

  // set streangth bar
  var score = results.score;
  var divs  = $('.strength div');
  // clear all div color
  divs.each(function(index, val) {
    $(this).css('background-color', '#808080');
  });
  // render strength color
  divs.each(function(index, val) {
    if (index > score) return;
    switch (score) {
      case 0: $(this).css('background-color', '#cb5c5f');break;
      case 1: $(this).css('background-color', '#e09384');break;
      case 2: $(this).css('background-color', '#f29c2b');break;
      case 3: $(this).css('background-color', '#b7f28a');break;
      default:$(this).css('background-color', '#20ff02');break;
    }
  });

  // add main issue
  var warning = results.feedback.warning;
  if (warning) {
    $('.issue').html(warning);
  } else {
    switch (score) {
      case 0: $('.issue').html('Never user this password!');break;
      case 1: $('.issue').html('Make it stronger!');break;
      case 2: $('.issue').html('Make it much stronger!');break;
      case 3: $('.issue').html('This is just ok!');break;
      default:$('.issue').html('This is a perfect password!');break;
    }
  }
  
  // add crack time
  var online_crack = results.crack_times_display.online_no_throttling_10_per_second;
  var hash_crack   = results.crack_times_display.offline_fast_hashing_1e10_per_second;
  $('.time').empty();
  $('.time').append(
    '<p>If this password were used for an onlien banking site, it could probably be cracked within <span>'+online_crack+'</span></p>'
  );
  $('.time').append(
    '<p>If this password would leak as encrypted string, it could probably be cracked within <span>'+hash_crack+'</span></p>'
  );

  // add suggestions
  var suggestions = results.feedback.suggestions;
  $('.suggestions ul').empty();
  for (var i = 0; i < suggestions.length; i++) {
    $('.suggestions ul').append(
      '<li>'+suggestions[i]+'</li>'
    );
  };

  // add all results of json
  $('.json pre').html(JSON.stringify(results, undefined, 2));
});