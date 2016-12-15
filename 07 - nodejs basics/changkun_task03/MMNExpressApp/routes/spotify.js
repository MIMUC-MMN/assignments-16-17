'use strict';

let express = require('express');
let path    = require('path');

let router = express.Router();
router.use('/', express.static(path.join(__dirname, '../spotifysearch')));

module.exports = router;