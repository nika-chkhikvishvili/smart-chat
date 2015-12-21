var express = require('express');
var app = express();

app.get('/', function (req, res) {
  
   res.send('<html><head><meta http-equiv="refresh" content="2"></head><body><h2>asd4asd54d56as4d56as4d65as4d65as4d65as4d65as4d654');
  
});

var server = app.listen(3000, function () {
  var host = server.address().address;
  var port = server.address().port;

  console.log('Example app listening at http://%s:%s', host, port);
});

//sdfsdfsdfsdf
