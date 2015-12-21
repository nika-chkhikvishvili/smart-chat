var express = require('express');
var app = express();

app.get('/', function (req, res) {
  
   res.send('<html><head><meta http-equiv="refresh" content="2"></head><body><h2>უჩა !</h2>><img src=http://s50.radikal.ru/i130/1412/ee/4b73d8a3ba09.jpg></img>');
  
});

var server = app.listen(3000, function () {
  var host = server.address().address;
  var port = server.address().port;

  console.log('Example app listening at http://%s:%s', host, port);
});

//sdfsdfsdfsdf
