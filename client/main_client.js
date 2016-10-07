/**
test comment
 * Created by jedi on 2016-07-01.
 */

var first_name = '';
var last_name = '';
var meTemplate = jQuery.validator.format("<div><div class='msgln' id='message_{0}'>({1}) <b>{2}</b>: {3}<br></div></div>");
var othTemplate = jQuery.validator.format("<div class='msglnr'>({0}) <b>{1}</b>: {2}<br></div>");

//var socket = io('http://smartchat.cloud.gov.ge:3000');
var socket = io(window.location.origin  + ':3000');

socket.on('testResponse', function (data){
    console.log('execute: testResponse');
    console.log(data);
});

socket.on('serverError', function (data){
    alert('Server Error');
});

socket.on('clientGetServicesResponse', function (data) {
    console.log('execute: clientGetServicesResponse');
    if ($.isArray(data)){
        $('#select_theme').html('');
        $.each(data, function(key, value) {
            $('#select_theme')
                .append($("<option></option>")
                    .attr("value",value.category_service_id)
                    .text(value.category_name + ' - ' + value.service_name));
        });
    }
});

socket.on('clientInitParamsResponse', function (data) {
    console.log('execute: clientInitParamsResponse');
    localStorage.chatUniqId = data.chatUniqId;
    $('#saxeli_span').text(first_name + ' ' + last_name);
    $('#asarchevi').hide();
    $('#wrapper').show();
});

socket.on('clientCheckChatIfAvariableResponse', function (data) {
    console.log('execute: checkChatIfAvariableResponse');
    console.log(data);
    if(data && data.hasOwnProperty('isValid') && data.isValid){
        first_name =data.first_name || '';
        last_name =data.last_name || '';
        $('#saxeli_span').text(first_name + ' ' + last_name);
        $('#asarchevi').hide();
        $('#wrapper').show();
        var elChatbox = $("#chatbox");
        if (data.messages && Array.isArray(data.messages)){
            elChatbox.html('');
            data.messages.forEach(function(item){
                if(item.online_user_id){
                    elChatbox.append(meTemplate(item.chat_message_id, item.message_date.substr(11,8) , first_name +' '+ last_name , item.chat_message ));
                } else {
                    elChatbox.append(othTemplate(item.message_date.substr(11,8) ,'system', item.chat_message ));
                }
            })
        }
        elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
    } else {
        socket.emit('clientGetServices');
    }
});

socket.on('message', function (data) {
    console.log('execute: message');
    socket.emit('clientMessageReceived', { chatUniqId: localStorage.getItem("chatUniqId"), msgId: data.ran});
    ++msgCount;
    var elChatbox = $("#chatbox");
    if(data.message == 'ping') {
        $('#operator_is_working').show();

        setTimeout(function(){
            $('#operator_is_working').hide();
        },3000);
    } else elChatbox.append(othTemplate((new Date()).toISOString().substr(11,8) , data.sender, data.message ));

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
    if (el.val() != 'submited') el.css({'background-color': 'red'});
}

function addMessage(id , message){
    var msg = meTemplate(id, (new Date()).toISOString().substr(11,8) , first_name +' '+ last_name ,message );

      var elChatbox = $("#chatbox");
    elChatbox.append(msg);

    //setTimeout(function () {
    //    redAlert(id);
    //}, 3000);
    elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
}


socket.io.on('reconnect', function () {
    var chatUniqId = localStorage.getItem("chatUniqId") || '';
    socket.emit('clientCheckChatIfAvariable',{chatUniqId : chatUniqId});
});

$(document).ready(function () {
    var chatUniqId = localStorage.getItem("chatUniqId") || '';
    socket.emit('clientCheckChatIfAvariable',{chatUniqId : chatUniqId});

    $("#exit").click(function () {
        var exit = confirm("Are you sure you want to end the session?");
        if (exit == true) {
            delete localStorage['chatUniqId'];
            $('#asarchevi').show();
            $('#wrapper').hide();
            $('#begin_btn').attr({disabled: false});
            socket.emit('clientGetServices');
        }
        return false;
    });

    $("#begin_btn").click(function ()  {
        $('#begin_btn').attr({disabled: true});
        var select_theme = $('#select_theme').val();
        first_name = $('#first_name').val();
        last_name = $('#last_name').val();

        if (!select_theme || select_theme == '') {
            alert('choose repo');
            return;
        }

        if (!first_name || first_name == '') {
            alert('choose first_name');
            return;
        }

        if (!last_name || last_name == '') {
            alert('choose last_name');
            return;
        }

        socket.emit('clientInitParams', {repo_id: select_theme, first_name: first_name, last_name: last_name});
    });

    $("#usermsg").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#submitmsg").click();
        }
    });

    $("#submitmsg").click(function () {
        var message = $('#usermsg').val();
        var ran = Math.floor(Math.random() * 10000000);
        socket.emit('clientMessage', {chatUniqId: localStorage.getItem("chatUniqId") , message: message, id: ran});
        $('#usermsg').val('');
        addMessage(ran, message);
    });

});
