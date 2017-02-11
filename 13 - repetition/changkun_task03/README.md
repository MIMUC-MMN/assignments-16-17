## Task 3: AJAX

### a) Explain the advantages and disadvantages of AJAX!

Advantages:

- better interactivity
- easier navigation
- compact
- backed by reputed brands

Disadvantages:

- back and refresh button are rendered useless
- hard to debug
- increased load on web server


### b) Explain what the "Same Origin Policy" (SOP) is. Give a concrete example of an AJAX request that will result in a violation of the SOP.

The same-origin policy is an important concept in the web application security model. Under the policy, a web browser permits scripts contained in a first web page to access data in a second web page, but only if both web pages have the same origin.

As example, when we perform a AJAX request at the site `https://changkun.us/:`

```js
$.ajax({
  type: 'GET',
  url: 'http://medien.ifi.lmu.de'
})
```

the SOP problems will come. i.e. the request will be prevented.

### c) Implement AJAX through jQuery or Vanilla JS

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Get Movies</title>
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script>

    // c. DOM is ready
    $(document).ready(function() {
      var output = $('#output');

      // b.i. server response
      function handleMobiesResponse(movies) {
        movies.forEach( function(movie) {
          // b.ii. generate div
          var div = $('<div>');

          // b.iii. generate h2 for title and year
          var h2  = $('<h2>');
          h2.html(movie.name + '(' + movie.year + ')').appendTo(div);

          // b.iv. generate unordered list for cast
          var ul = $('<ul>');
          movie.cast.forEach(function(name) {
            var li = $('<li>');
            li.html(name).appendTo(ul);
          })
          ul.appendTo(div);

          div.appendTo(output);
        });
      }
      function requestMovies() {
        // a. start request
        $.get('http://movi.es').done(handleMobiesResponse);
      }
      requestMovies();
    });
  </script>
</head>
<body><div id="output">
  <h1>Movies</h1>
</div></body>
</html>
```

### d) Fix Polymer Code

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Get Movies</title>
  <script src="bower_components/webcomponentsjs/webcomponents-lite.min.js"></script>
  <!-- ERROR: 1. import iron-ajax component -->
  <link rel="import" href="bower_components/polymer/polymer.html">
  <link rel="import" href="bower_components/iron-ajax/iron-ajax.html">
</head>
<body>
  <div id="output">
    <h1>Mobies</h1>
      <!-- ERROR: 2. missing template is dom-bind -->
      <template is="dom-bind">
        <!-- ERROR: 3. lastResponse should be last-response -->
        <iron-ajax auto url="movies.json" last-response="{{moviesResponse}}"></iron-ajax>
        <template is="dom-repteat" items="[[moviesResponse]]" as="movie">
          <!-- ERROR: 4. missing data binding brackets-->
          <h2>[[movie.name]] ([[movie.year]])</h2>
          <ul>
            <template is="dom-repeat" items="[[movie.cast]]" as="actor">
              <li>[[actor]]</li>
            </template>
          </ul>
        </template>
      </template>
  </div>
</body>
</html>
```