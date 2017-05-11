/**
test comment
 * Created by jedi on 2016-07-01.
 */

'use strict';

var firstName = '';
var lastName = '';
var meTemplate = jQuery.validator.format("<div><div class='msg msgln' id='message_{0}'>({1}) <b>{2}</b>: {3}<br></div></div>");
var othTemplate = jQuery.validator.format("<div class='msg msglnr'>({0}) <b>{1}</b>: {2}<br></div>");

var socket = io(window.location.origin + ':3000');

socket.on('testResponse', function (data){
    console.log('execute: testResponse');
    console.log(data);
});

socket.on('serverError', function (data){
    alert('Server Error');
});

socket.on('clientGetServicesResponse', function (data) {
    console.log('execute: clientGetServicesResponse');
    // console.log(data);
    if ($.isArray(data)){
        $('#select_theme').html('');
        $.each(data, function(key, value) {
            $('#select_theme').append($("<option></option>").attr("value", value.category_service_id).text(value.category_name + ' - ' + value.service_name_geo));
        });
    }
});

socket.on('operatorJoined', function (data) {
    console.log('execute: operatorJoined');
    console.log(data);
    $("#chatbox").append("<div class='msg msglnr'>" + data + "<br></div>");
});

socket.on('clientInitParamsResponse', function (data) {
    console.log('execute: clientInitParamsResponse');

    if (!data) {
        alert('Error');
        return;
    }

    if (data.hasOwnProperty('isValid')) {
        alert('Wrong Params');
        return;
    }

    if (data.hasOwnProperty('serviceIsOffline')) {
        alert('Service Is Offline');
        $('#begin_btn').attr({disabled: false});
        window.location = 'offline.php';
        return;
    }

    $('#chatbox').html('');
    localStorage.chatUniqId = data.chatUniqId;
    $('#saxeli_span').text(firstName + ' ' + lastName);
    $('#asarchevi').hide();
    $('#wrapper').show();
});

socket.on('clientCheckChatIfAvailableResponse', function (data) {
    console.log('execute: clientCheckChatIfAvailableResponse');
    console.log(data);
    if(data && data.hasOwnProperty('isValid') && data.isValid){
        firstName =data.firstName || '';
        lastName =data.lastName || '';
        $('#saxeli_span').text(firstName + ' ' + lastName);
        $('#asarchevi').hide();
        $('#wrapper').show();
        var elChatbox = $("#chatbox");
        if (data.messages && Array.isArray(data.messages)) {
            elChatbox.html('');
            data.messages.forEach(function(item) {
                if(item.online_user_id) {
                    elChatbox.append(meTemplate(item.chat_message_id, item.message_date.substr(11, 8), firstName + ' ' + lastName, item.chat_message));
                } else {
                    elChatbox.append(othTemplate(item.message_date.substr(11, 8), item.sender, item.chat_message));
                }
            });
        }
        elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
    } else {
        socket.emit('clientGetServices');
    }
});

socket.on('message', function (data) {
    console.log('execute: message');
    console.log(data);
    socket.emit('clientMessageReceived', {chatUniqId: localStorage.getItem("chatUniqId"), msgId: data.ran});
    var elChatbox = $("#chatbox");
    if(data.messageType === 'ping') {
        $('#operator_is_working').show();

        setTimeout(function(){
            $('#operator_is_working').hide();
        },3000);


    } else if (data.messageType === 'writing') {
        var a = $('#operator_is_writing');
        a.data('lastWriteTime', Date.now());
        a.show();

    } else if (data.messageType === 'ban') {
        $('#wrapper').hide();
        $('#asarchevi').hide();
        $('.user_ban').html(data.message);
        socket.disconnect();
    } else if(data.messageType === 'close') {
        delete localStorage.chatUniqId;
        elChatbox.html('');
        $('#asarchevi').show();
        $('#wrapper').hide();
        $('#begin_btn').attr({disabled: false});
        socket.emit('clientGetServices');

    } else {
        if (data.guestUserId) {
            elChatbox.append(meTemplate(data.messageUniqId, Date.now().toISOString().substr(11, 8), firstName + ' ' + lastName, data.message ));
        } else {
            elChatbox.append(othTemplate((new Date()).toISOString().substr(11, 8), data.sender, data.message));
        }
    }
    elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
});

socket.on('messageReceived', function (data) {
    console.log('execute: messageReceived');
    console.log(data);
    var el = $('#message_' + data.msgId);

    el.val('submited');
    el.css({'background-color': 'greenyellow'});
});

socket.on('clientMessageResponse', function (data) {
    console.log('execute: clientMessageResponse');
    console.log(data);
});


function redAlert(id) {
    var el = $('#message_' + id);
    if (el.val() !== 'submited') {
        el.css({'background-color': 'red'});
    }
}

function addMessage(id, message) {
    var msg = meTemplate(id, (new Date()).toISOString().substr(11, 8), firstName + ' ' + lastName, message);

    var elChatbox = $("#chatbox");
    elChatbox.append(msg);

    //setTimeout(function () {
    //    redAlert(id);
    //}, 3000);
    elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
}

socket.io.on('reconnect', function () {
    var chatUniqId = localStorage.getItem("chatUniqId") || '';
    socket.emit('clientCheckChatIfAvailable', {chatUniqId: chatUniqId});
});

$(document).ready(function () {
    var chatUniqId = localStorage.getItem("chatUniqId") || '';
    socket.emit('clientCheckChatIfAvailable', {chatUniqId: chatUniqId});

    $("#exit").click(function () {
        var exit = confirm("Are you sure you want to end the session?");
        if (exit === true) {
            socket.emit('clientCloseChat', {chatUniqId: localStorage.getItem("chatUniqId")});
        }
        return false;
    });

    $("#begin_btn").click(function ()  {
        $('#begin_btn').attr({disabled: true});
        var select_theme = $('#select_theme').val();
        firstName = $('#first_name').val();
        lastName = $('#last_name').val();
        var personal_no = $('#personal_no').val();

        if (!select_theme || select_theme === '') {
            alert('choose service');
            return;
        }

        if (!firstName || firstName === '') {
            alert('choose first_name');
            return;
        }

        if (!lastName || lastName === '') {
            alert('choose last_name');
            return;
        }
        socket.emit('clientInitParams', {service_id: select_theme, first_name: firstName, last_name: lastName, personal_no:personal_no});
    });

    $("#usermsg").keyup(function (event) {
        socket.emit('userIsWriting', {chatUniqId: localStorage.getItem("chatUniqId")});
        if (event.keyCode === 13) {
            $("#submitmsg").click();
        }
    });

    $("#submitmsg").click(function () {
        var usermsg = $('#usermsg');
        var message = usermsg.val();
        var ran = Math.floor(Math.random() * 10000000);
        socket.emit('clientMessage', {chatUniqId: localStorage.getItem("chatUniqId") , message: message, id: ran});
        usermsg.val('');
        addMessage(ran, message);
    });
});

setInterval(
    function hideIsWriting(){
        var el = $('#operator_is_writing');
        if (isNaN(el.data('lastWriteTime'))) {
            return;
        }
        if (Date.now() - el.data('lastWriteTime') > 3000) {
            el.hide();
        }
    }, 1000
);
