## Task 4: NodeJS

### a) Provide the npm command to add the body-parser package as a dependency.

```bash
npm install --save body-parser
```

### b) NodeJS has an event-driven I/O. Explain what this means in terms of serving clients.

Event driving means the server serves each request from client as a event. When request comes up, request event will be added to the event loop, then the thread perform(submit) current I/O operation on server, then the thread will execute next I/O event request except some of the I/O operation is done. Thus, it also called non-blocking I/O Model.

### c) Code

```js
var express = require('express')
var bodyParser = require('body-parser');

var app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: false}));

app.get('/year', function(req, res) {
  res.json({
    year : new Date().getFullYear()
  });
});

app.get('/time', function(req, res) {
  var obj = Object();
  obj['date'] = new Date();
  if (req.query.hours) {
    obj['hours'] = new Date().getHours();
  }
  if (req.query.minutes) {
    obj['minutes'] = new Date().getMinutes();
  }
  res.json(obj);
});

app.listen(1337);
```