/**
 * modified from https://bost.ocks.org/mike/shuffle/ (Fisher-Yates Shuffle).
 * this will allow you to call someArray.shuffle(). The shuffling is in place and also the shuffled Array is returned,
 * you you can method-chain this call.
 *
 */
Array.prototype.shuffle = function() {
  var m = this.length, t, i;
  // While there remain elements to shuffle…
  while (m) {
    // Pick a remaining element…
    i = Math.floor(Math.random() * m--);
    // And swap it with the current element.
    t = this[m];
    this[m] = this[i];
    this[i] = t;
  }
  return this; // to allow method chaining.
};
var template = {
  'Essence': 'Content consists of meta data and …',
  'Content': 'Essence and meta-data combined are called …',
  'Embedded meta data': 'You can save meta-data in databases and separate files. Or you handle it differently. How do you call this different approach?',
  'MPEG-7': 'A standard to describe multimedia contents',
  'ID3': 'A standard to save metadata to mp3 files',
  'Spatial Segment': 'A still-region in an image is in any case a ...',
  'Segment graph': 'You can relate different segments and visualize the result. How do you call this visualization?',
  'high-level unstructured meta data': 'In which category of meta-data does the album cover art fit?',
  'high-level structured meta data': 'In which category of meta-data does „song title“ fit?',
  'low level meta data': 'In which category of meta-data does „bitrate“ fit?',
  'GraceNote': 'A famous database to retrieve meta-data about music',
  'Feature extraction': 'How do you call a process to automatically generate low-level meta-data?',
  'Fingerprinting': 'What is the name of the procedure with which you can recognize music short samples?',
  'Temporal segment': 'A sample of a song can be seen as ...',
  'EXIF': 'What is the most common format to store meta data inside images?',
  'Dublin Core': 'A standard that consists of 15 meta-data elements and that is targeted at books, magazines and journals.',
  'RDF': 'A framework to describe web resources',
  'Shape descriptor': 'The representation of a geometric object',
  'Automatic score transcription': 'How do you call the process to identify parts of a song or its instruments algorithmically?',
  'Shazam': 'A company that offers automatic music tagging',
  'Moving Pictures Expert Group': 'Which organization created MPEG-7?',
  'Still Regions': 'You can split up areas of pictures into ...',
  'Structural relation': 'You can associate segments to one another. How do you call this association?',
  'above': 'An example for a „spatial structural relation“',
  'Resource': 'Which term matches this definition: Anything that can be identified by a URI'
};

// get all keys from template
var keys1 = Object.keys(template);
var keys2 = Object.keys(template);

// use for displayed item
var suffle1 = keys1.shuffle();

// use for questions
var suffle2 = keys2.shuffle();

var currentIndex = 0;
var score = 0;

// use for check up
var matrix = [
  0,0,0,0,0,
  0,0,0,0,0,
  0,0,0,0,0,
  0,0,0,0,0,
  0,0,0,0,0
];

var question = document.getElementById('question');
question.innerHTML = template[suffle2[currentIndex]];

var card = document.getElementById('bingo');

// add all elements
for (var i = 0; i < suffle1.length; i++) {
  var item = document.createElement('div');
  item.classList.add('item');
  item.classList.add(i);
  item.innerHTML = suffle1[i];
  card.appendChild(item);
  
  // five div per line
  if ((i+1)%5 === 0) {
    card.appendChild(document.createElement('br'));
  }
}

// configuration of all click event
document.querySelectorAll('.item').forEach( function(element) {
  element.addEventListener('click', function() {
    if (element.innerHTML == suffle2[currentIndex]) {
      currentIndex++;
      score++;
      element.style.backgroundColor = 'green';
      question.innerHTML = template[suffle2[currentIndex]];
    } else {
      element.style.backgroundColor = 'red';
      score--; 
    }

    // remove all event listener
    removeAllEventListner(element);

    // mark clicked element as 1
    matrix[element.classList[1]] = 1;

    // check matrix contains a full row/column/diagonal
    check(matrix);
  })
});

function removeAllEventListner(element) {
  var elClone = element.cloneNode(true);
  element.parentNode.replaceChild(elClone, element);
}

function check(matrix) {
  var rowResult = [];
  var columnResult = [0,0,0,0,0];
  var temp = 0;
  for (var i = 0; i < matrix.length; i++) {
    columnResult[i%5] += matrix[i];
    temp += matrix[i];
    if((i+1)%5==0) {
      rowResult.push(temp);
      temp = 0;
    }
  }
  if( matrix[5*0+0] + matrix[5*1+1] + matrix[5*2+2] + matrix[5*3+3] + matrix[5*4+4] == 5 || 
      matrix[5*0+4] + matrix[5*1+3] + matrix[5*2+2] + matrix[5*3+1] + matrix[5*4+0] == 5 ||
      rowResult.indexOf(5) !== -1 || columnResult.indexOf(5) !== -1 ) {
    document.querySelectorAll('.item').forEach(function(element) {
      removeAllEventListner(element);
    });
    question.innerHTML = 'Bingo! Your Score is: ' + (score < 0 ? 0 : score) ;
  }

}