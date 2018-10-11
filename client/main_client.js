/**
 * Created by jedi on 2016-07-01.
 */

'use strict';

let socket = io(window.location.origin + ':8443');
let infoMessagePanel = $("#info_message_panel");
let chat = new ChatClient($, socket);
let disconnect_chat_title = "ჩატის დასრულება";
let disconnect_chat_message = "ნამდვილად გსურთ საუბრის დასრულება?";

socket.on('testResponse', function (data) {
    console.log('execute: testResponse');
    console.log(data);
});

$(document).ready(function () {

    /////

    document.addEventListener("message", function(data) {
        alert(JSON.stringify(data));
        if (typeof data === 'string'){
            data = JSON.parse(data);
        }
        alert(JSON.stringify(data));
        if (data.hasOwnProperty('data')) {
            data = data.data;
        }
        alert(JSON.stringify(data));
        if (typeof data === 'string'){
            data = JSON.parse(data);
        }
        alert(JSON.stringify(data));
        if (data.data.type === 'token') {
            socket.emit('clientSetPushNotificationToken', {token: data.data.value });
        } else if (data.data.type === 'state') {
            if (data.data.value === 'inactive') {
                socket.emit('clientSetDeviceInactive');
            } else if (data.data.value === 'active') {
                socket.emit('clientSetDeviceActive');
            }
        }
    });

    ////


    socket.emit('clientCheckChatIfAvailable', chat.getChatUniqObject());

    $("#disconnect-chat").click(function () {

        bootbox.confirm({
            title: disconnect_chat_title,
            message: disconnect_chat_message,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    socket.emit('clientCloseChat');
                }
            }
        });
        return false;
    });

    $("#begin_btn").click(function ()  {
        $('#begin_btn').attr({disabled: true});
        let select_theme = $('#select_theme').val();
        let firstName = $('#first_name').val();
        let lastName = $('#last_name').val();

        if (!select_theme || select_theme === '') {
            alert('აირჩიეთ სერვისი');
            $('#begin_btn').attr({disabled: false});
            return;
        }

        if (!firstName || firstName === '') {
            alert('აირჩიეთ სახელი');
            $('#begin_btn').attr({disabled: false});
            return;
        }

        if (!lastName || lastName === '') {
            alert('აირჩიეთ გვარი');
            $('#begin_btn').attr({disabled: false});
            return;
        }
        let language = $(".bfh-languages")[0].value;
        chat.setUserInformation(firstName, lastName, language);
        socket.emit('clientInitParams', {serviceId: select_theme, firstName: firstName, lastName: lastName, language: language});
    });

    $("#usermsg").keyup(function (event) {
        socket.emit('userIsWriting', chat.getChatUniqObject());
        if (event.keyCode === 13) {
            $("#submitmsg").click();
        }
    });

    $("#submitmsg").click(function () {
        let usermsg = $('#usermsg');
        let message = usermsg.val();
        if (message.length < 2 ) {
            return;
        }
        let ran = Math.floor(Math.random() * 10000000);
        socket.emit('clientMessage', {chatUniqId: chat.getChatUniqId(), message: message, id: ran});
        usermsg.val('');
    });

    $(".bfh-languages").on('change.bfhselectbox', function (event) {
        chat.changeLanguage(event.target.value);
    });

    let chatBody = $('#chat-body');
    let popoverBigImage = $('#popover_big_image');

    chatBody.on('mousemove','.popover_image', function(e){
        popoverBigImage.css({
            top: e.pageY - 310,
            left: e.pageX + 10
        });
    });
    chatBody.on('mouseenter','.popover_image', function(){
        popoverBigImage.attr('src', this.src);
        popoverBigImage.show();
    });
    chatBody.on('mouseleave','.popover_image', function(){
        popoverBigImage.hide();
    });

    setInterval(chat.executeClientLoopFunction, 1000);
    $(".bfh-languages").change();
});


socket.io.on('reconnect', function () {
    socket.emit('clientCheckChatIfAvailable', chat.getChatUniqObject());
});

socket.on('clientCheckChatIfAvailableResponse', function (data) {
    if (data && data.hasOwnProperty('isValid') && data.isValid) {
        chat.setUserInformation(data.firstName, data.lastName, data.language);
        $('#asarchevi').hide();
        if (parseInt(data.chatStatusId) === 0) {
            $('#wait_operator').show();
        } else {
            $('#chat_window').show();
        }

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

});

socket.on('clientInitParamsResponse', function (data) {

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
    $('#wait_operator').show();
});

socket.on('operatorJoined', function () {
    // console.log(data);
    $('#wait_operator').hide();
    $('#chat_window').show();
});

socket.on('message', function (data) {
    socket.emit('clientMessageReceived', data.ran);

    if (data.messageType === 'operatorIsWorking') {
        chat.operatorIsWorkingShow();
    } else if (data.messageType === 'writing') {
        chat.operatorIsWritingShow();
    } else if (data.messageType === 'ban') {
        chat.banUser(data.message);
        socket.disconnect();
        } else if (data.messageType === 'close') {
        chat.closeChat();
    } else {
        if (data.guestUserId) {
            chat.addMyMessage(data.messageUniqId, null, data.message);
        } else {
            chat.operatorIsWorkingHide();
            chat.addOtherMessage(data);
        }
    }
});
