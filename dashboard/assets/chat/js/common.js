/**
 * Created by jedi on 2016-05-24.
 */

var socket = io(socketAddr +':3000');


socket.on('testResponse', function (data) {
    console.log('execute: testResponse');
    console.log(data);
});


socket.on('serverError', function (data) {
    alert('Server Error');
});




