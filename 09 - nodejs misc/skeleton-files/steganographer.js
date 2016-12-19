/**
 * Created by Tobi on 19/12/2016.
 */
var express = require('express');
var router = express.Router();
var path = require('path');
var fs = require('fs');

// TODO make sure the dependencies already exist --> npm install --save stegosaurus uuid
var stego = require('stegosaurus');
var uuid = require('uuid');

// TODO set the correct directory (static) for the stegano module that you have to create first.
// put an index.html in there. (see skeleton)
var steganoDir = path.join(__dirname, '../stegano');
var secretImagesDir = path.join(steganoDir, 'watermarked');

// change this to anything you like. make sure the image exists and is a PNG!
var hostImage = path.join(__dirname, '../public/images/', 'merry-x-mas.png');


// make sure the folder exists.
if (!fs.existsSync(secretImagesDir)) {
  fs.mkdirSync(secretImagesDir);
}

// serves the content (index.html and watermarked images)
router.use(express.static(steganoDir));

// hides or seeks the secret messages.
router.use('/:method', function(req, res, next) {
  var id;
  var generatedImage;
  var message;
  var size;

  /**
   * generates a valid path for the image that contains the message.
   *
   * @param id of the image. Either created or read from the request.
   */
  function makeImagePath(id) {
    return path.join(secretImagesDir, id + '.png');
  }

  // what do we want to do?
  // hide --> encode
  if(req.params.method == 'hide') {
    // required parameter: message. Either as GET query or POST parameter.
    // TODO


    // seek --> decode the image.
  } else if(req.params.method == 'seek') {
    // the mandatory parameter is the id of the image.

    // TODO
  } else {
    // method did not match anything that we can handle.
    next();
  }
});



module.exports = router;
