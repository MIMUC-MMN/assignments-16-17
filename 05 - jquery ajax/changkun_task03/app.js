'use strict';

$(document).ready(function() {
  const searchURL = 'https://api.spotify.com/v1/search';

  function getData(query, type) {
    $.get(searchURL, {
      q: query,
      type: type,
      limit: 50
    }).done(function(data) {
      if (data && data.albums && data.albums.items) {
        $('#results').empty();
        data.albums.items.forEach(function(album) {

          console.log(album);

          // create modal link
          let link = $('<a>').attr('href', '#album').attr('rel', 'modal:open');

          // create an album
          let divEl = $('<div>');
          let imgEl = $('<img>');
          imgEl.attr('src', album.images && album.images.length ? album.images[0].url : null);
          imgEl.attr('alt', album.name);

          // add modal showup link
          link.append(imgEl);
          // add album href to div, to fetch content when modal showup
          link.attr('data', album.href);
          link.attr('class', 'link');

          divEl.addClass('album');
          divEl.append(link);

          // add to results
          $('#results').append(divEl);
        });

        // fetch more alumb data
        $('.link').on('click', function(event) {
          console.log();
          $.get($(this).attr('data')).done(function(data) {
            console.log(data);
            $('.album-img img').attr('src', data.images[0].url);
            $('.album-name').html(data.name);
            $('.popularity').html('Popularity: '+data.popularity);
            $('.tracks').empty();
            data.tracks.items.forEach( function(element, index) {
              let track = $('<div class="track">');
              let trackname = $('<div class="track-name">').html(element.name);
              trackname.appendTo(track);
              let trackaudio = $('<div class="audio">')
              let audio = $('<audio controls>').attr('src', element.preview_url);
              audio.appendTo(track);

              track.appendTo('.tracks');
            });

          });
        });
      }
    });
  }

  // handle input
  $('#input').on('input', function() {
    if ($('#input').val().length > 2) {
      getData($('#input').val(), 'album');
    }
  });
  
});