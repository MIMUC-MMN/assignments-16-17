var sourcesURL = 'https://newsapi.org/v1/sources?language=en';
var articlesURL = 'https://newsapi.org/v1/articles';
var apiKey = 'YOUR-API-KEY';

$.get(sourcesURL).done(function(data) {
  var sources = data.sources;
  for (var i = 0; i < sources.length; i++) {
    var image = $('<img>');
    image.attr('id', sources[i].id)
    image.attr('src', sources[i].urlsToLogos.medium);
    image.appendTo('#source').on('click', function() {
      $.get(articlesURL, {
        source: $(this).attr('id'),
        apiKey: apiKey,
        sortBy: 'latest'
      }).done(function(data) {
        console.log(data);
        $('#content').empty();
        var articles = data.articles;
        for (var i = 0; i < articles.length; i++) {
          var title       = articles[i].title;
          var time        = articles[i].publishedAt;
          var author      = articles[i].author;
          var description = articles[i].description;
          var url         = articles[i].url;
          var urlImg      = articles[i].urlToImage;

        var card   = $('<div class="card">');
        var title  = $('<div class="title">').html(title);
        var author = $('<div class="author">').html(author);
        var image  = $('<div class="image">').html($('<img>')
          .attr('src', urlImg));
        var description = $('<div class="description">').html(description);
        var link   = $('<div class="link">').html($('<a>')
          .attr('href', articles[i].url).html('Read More'));
        card.append(title).append(author).append(image).append(description).append(link);
        $('#content').append(card);
          
        }


      }).fail(function() {
        $('#content').empty();
        var alert = $('<div id="none">');
        alert.appendTo('#content');
        $(alert).html('Unfortunately, this news cannot be opened.<br/> Please try another.');
      });
    });
  }
});