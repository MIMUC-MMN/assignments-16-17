var express = require('express');
var router = express.Router();

var path = require('path');
var jimp = require('jimp');
var fs   = require('fs');

const sorryPath     = path.join(__dirname, '../public/images/sorry.png');
const watermarkPath = path.join(__dirname, '../public/images/watermark.png');
const watermarkedImagesDirectory = path.join(__dirname, '../watermarks');

// globally load watermark image
jimp.read(watermarkPath, function(err, img) {
  watermark = img;

  // register route
  router.use('/', function(req, res, next) {
    // valid only url is not null
    if (req.query.url) {
      // prepare save path
      var markedPath = path.join(watermarkedImagesDirectory, 'temp.png');
      // load image from url
      jimp.read(req.query.url, function(err, img) {
        if(err) 
          res.sendFile(sorryPath);
        else {
          img.composite(watermark, 0, 0, function(err, dst) {
            if(err) res.sendFile(sorryPath);
            dst.write(markedPath, function() {
              res.sendFile(markedPath);
            });
          });
        }
      });
    } else {
      res.sendFile(sorryPath);
    }
  });
});

module.exports = router;