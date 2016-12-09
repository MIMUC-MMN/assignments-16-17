var previous = document.querySelectorAll('.previous')[0]
var next = document.querySelectorAll('.next')[0]
var max = 6
previous.addEventListener('click', function() {
  var app   = document.getElementById('app');
  var index = parseInt(document.getElementById('index').innerHTML);
  console.log(index);
  if (index == 1) {
    app.style.background = "url('images/"+max+".jpg') center";
    document.getElementById('index').innerHTML = max;
  } else {
    app.style.background = "url('images/"+(index-1)+".jpg') center";
    document.getElementById('index').innerHTML = index-1;
  }
});
next.addEventListener('click', function() {
  var app   = document.getElementById('app');
  var index = parseInt(document.getElementById('index').innerHTML);
  console.log(index);
  if (index == max) {
    app.style.background = "url('images/1.jpg')";
    document.getElementById('index').innerHTML = 1;
  } else {
    app.style.background = "url('images/"+(index+1)+".jpg')";
    document.getElementById('index').innerHTML = index+1;
  }
});