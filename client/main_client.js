/**
test comment
 * Created by jedi on 2016-07-01.
 */

'use strict';

var socket = io(window.location.origin + ':3000');
var chat = new Chat($, socket);

$(document).ready(function () {

    socket.emit('clientCheckChatIfAvailable', chat.getChatUniqObject());

    $("#disconnect-chat").click(function () {
        var exit = confirm("ნამდვილად გსურთ საუბრის დასრულება?");
        if (exit === true) {
            socket.emit('clientCloseChat');
        }
        return false;
    });

    $("#begin_btn").click(function ()  {
        $('#begin_btn').attr({disabled: true});
        var select_theme = $('#select_theme').val();
        var firstName = $('#first_name').val();
        var lastName = $('#last_name').val();

        if (!select_theme || select_theme === '') {
            alert('აირჩიეთ სერვისი');
            return;
        }

        if (!firstName || firstName === '') {
            alert('აირჩიეთ სახელი');
            return;
        }

        if (!lastName || lastName === '') {
            alert('აირჩიეთ გვარი');
            return;
        }
        chat.setUserInformation(firstName, lastName);
        socket.emit('clientInitParams', {serviceId: select_theme, firstName: firstName, lastName: lastName});
    });

    $("#usermsg").keyup(function (event) {
        socket.emit('userIsWriting', chat.getChatUniqObject());
        if (event.keyCode === 13) {
            $("#submitmsg").click();
        }
    });

    $("#submitmsg").click(function () {
        var usermsg = $('#usermsg');
        var message = usermsg.val();
        var ran = Math.floor(Math.random() * 10000000);
        socket.emit('clientMessage', {chatUniqId: chat.getChatUniqId(), message: message, id: ran});
        usermsg.val('');
        chat.addMyMessage(ran, null, message);
    });

});


/*
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

*/

socket.on('testResponse', function (data){
    console.log('execute: testResponse');
    console.log(data);
});

socket.on('serverError', function (data){
    alert('Server Error');
});

socket.io.on('reconnect', function () {
    socket.emit('clientCheckChatIfAvailable', chat.getChatUniqObject());
});

socket.on('clientCheckChatIfAvailableResponse', function (data) {
    console.log('execute: clientCheckChatIfAvailableResponse');
    // console.log(data);
    if (data && data.hasOwnProperty('isValid') && data.isValid) {
        chat.setUserInformation(data.firstName, data.lastName);
        $('#asarchevi').hide();
        $('.panel').show();
        if (data.messages && Array.isArray(data.messages)) {
            chat.cleanChatWindow();
            data.messages.forEach(function (item) {
                if (!!item.guestUserId || !!item.online_user_id) {
                    chat.addMyMessage(item.messageId, item.messageDate, item.message, true);
                } else {
                    chat.addOtherMessage(item, true);
                }
            });
            chat.scrollDown();
        }
    } else {
        socket.emit('clientGetServices');
    }
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

    chat.cleanChatWindow();
    chat.setChatUniqId(data.chatUniqId);
    $('#asarchevi').hide();
    $('.panel').show();
});

socket.on('operatorJoined', function (data) {
    console.log('execute: operatorJoined');
    console.log(data);
    $("#chat-body-ul").append("<div class='msg msglnr'>" + data + "<br></div>");
});

socket.on('message', function (data) {
    console.log('execute: message');
    console.log(data);
    socket.emit('clientMessageReceived', data.ran);

    if (data.messageType === 'operatorIsWorking') {
        chat.operatorIsWorkingShow();
    } else if (data.messageType === 'writing') {
        chat.operatorIsWritingShow();
    } else if (data.messageType === 'ban') {
        chat.banUser();
        socket.disconnect();
    } else if (data.messageType === 'close') {
        chat.closeChat();
    } else {
        if (data.guestUserId) {
            chat.addMyMessage(data.messageUniqId, null, data.message);
        } else {
            chat.addOtherMessage(data);
        }
    }
});
