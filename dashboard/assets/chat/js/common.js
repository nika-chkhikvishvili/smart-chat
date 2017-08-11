/**
 * Created by jedi on 2016-05-24.
 */

let socket = io(socketAddr +':3000');
let user = {firstName: '', lastName: '', userName: ''};

socket.on('testResponse', function (data) {
    console.log('execute: testResponse');
    console.log(data);
});


socket.on('serverError', function (data) {
    alert('Server Error');
});




