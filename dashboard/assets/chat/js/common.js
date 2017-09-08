/**
 * Created by jedi on 2016-05-24.
 */

// let socket = io(socketAddr +':8443');
let socket = io( window.location.origin + ':8443');
let user = {firstName: '', lastName: '', userName: ''};

socket.on('testResponse', function (data) {
    console.log('execute: testResponse');
    console.log(data);
});


socket.on('serverError', function (data) {
    alert('Server Error');
});




