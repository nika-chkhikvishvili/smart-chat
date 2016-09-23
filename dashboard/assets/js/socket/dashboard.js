/**
 * Created by jedi on 2016-07-02.
 */


var services = [];
var me;

var meTemplate = jQuery.validator.format("<div><div class='msgln' id='message_{0}'>({1}) : {2}<br></div></div>");
var othTemplate = jQuery.validator.format("<div class='msglnr'>({0}) <b>{1}</b>: {2}<br></div>");




function isChatWindowHidden(id) {
    return localStorage.getItem("hidde_chat_"+id) || false;
}

//ჩატის ფანჯარას ქმნის და მონაცემებს წამოიღებს
var createChatWindowAndLoadData = function(data){
    console.log('execute: createChatWindowAndLoadData');
    //console.log(data);

    if (!data || !data.chat_uniq_id) {
        return;
    }

    if ($('#' + data.chat_uniq_id).length >0) {
        return;
    }

    $('#msgbox_container').append('<div class="msgbox_chat_window" id="' + data.chat_uniq_id + '" style="display:'+(isChatWindowHidden(data.chat_uniq_id) ? 'none':'block')+';">'+
        '<div class="msgbox_chat_minimized titlebar">' + data.first_name + ' '+ data.last_name + '</div>'+
        '<div class="msgbox_chat_content">'+
        '<div class="titlebar">'+
        '<div class="msgbox_left">' + shorter( data.first_name + ' '+ data.last_name) + '</div>'+
        '<div class="msgbox_right">'+
        '<input type="checkbox" class="msgbox_working_checkbox">'+
        '<a class="msgbox_close" href="#">X</a>'+
        '</div>'+
        '</div>'+
        '<div class="msgbox_chat_area scrollable">'+
        '</div>'+
        '<div class="msgbox_chat_">'+
        '<textarea name="usermsg" type="text" class="msgbox_usermsg"></textarea>'+
        '</div>'+
        '</div>'+
        '</div>');


    $('.contacts-list').append( '<li class="list-group-item">'+
    '<a href="#" onclick=\' localStorage.removeItem("hidde_chat_'+ data.chat_uniq_id+'");    $("#'+data.chat_uniq_id+'").show(); return false; \'>'+
    '<div class="avatar">'+
    '<img src="/assets/images/users/man.png" alt="">'+
    '</div>'+
    '<span class="name">' + shorter( data.first_name + ' '+ data.last_name) + '</span>'+
    '<i class="fa fa-circle online"></i>'+
    '</a>'+
    '<span class="clearfix"></span>'+
    '</li>');

    socket.emit('getAllChatMessages', { chat_uniq_id: data.chat_uniq_id});

};

socket.on('getAllChatMessagesResponse', function (data) {
    console.log('execute: getAllChatMessagesResponse');
    console.log(data);

    var elChatbox = $("#"+data.chat_uniq_id +' .msgbox_chat_area');

    data.messages.forEach(function(item){

        if(item.online_user_id){
            elChatbox.append(othTemplate(item.message_date.substr(11,8) , '', item.chat_message ));
        } else {
            addMessage(data.chat_uniq_id, item.chat_message_id, item.chat_message);
        }
    });

    elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');

});

socket.on('checkTokenResponse', function (data){
    console.log('execute: checkTokenResponse');
    //console.log(data);

    if (data.isValid){
        //socket.emit('get',{token : token});
        socket.emit('getWaitingList');
        socket.emit('getActiveChats');

        if (Array.isArray(data.ans) ) {
            data.ans.forEach(function(i){
                //console.log(i);

                var d = {
                    chat_uniq_id : i.chatUniqId,
                    first_name : i.first_name || '',
                    last_name : i.last_name || ''
                };

                createChatWindowAndLoadData(d)
            })
        }

    }
});

//აქ მოდის ოპერატორის მიერ გაგზავნილ შეტყობინებაზე პასუხი
socket.on('sendMessageResponse', function (data){
    console.log('execute: sendMessageResponse');
    //console.log(data);
    if (!data || !data.isValid){
        //TODO შეცდომა მესიჯის გაგზავნისას, აქ უნდა დამუშავდეს
    }
});


function getNextWaitingClient(data){
    console.log(data);
    socket.emit('getNextWaitingClient',{serviceId : data});
}

socket.on('userDisconnect', function (data){
    console.log('execute: userDisconnect');
    //console.log(data);

   // clients_queee_body
});

$(document).ready(function () {
    // checks and authoriser user

    //ამოჭმებს ნამდვილად არის თუ არა აუტორიზებულე პიროვნება, და ასევე აბრუნებს ღია ფანჯრების სიას
    socket.emit('checkToken',{token : token});

    //სასაუბრო ფანჯრის ჩაკეცვა
    $("#msgbox_container").on('click', '.msgbox_left', function () {
        var chatWindow = $(this).parents('.msgbox_chat_window');
        chatWindow.children( ".msgbox_chat_content" ).hide();
        chatWindow.children( ".msgbox_chat_minimized" ).show();
    });

    //ჩაკეცილი სასაუბრო ფანჯრის გადიდება
    $("#msgbox_container").on('click', '.msgbox_chat_minimized', function () {
        var chatWindow = $(this).parents('.msgbox_chat_window');
        chatWindow.children( ".msgbox_chat_content" ).show();
        chatWindow.children( ".msgbox_chat_minimized" ).hide();
    });

    //სასაუბრო ფანჯრის დამალვა
    $("#msgbox_container").on('click', '.msgbox_close', function (e) {
        var a = $(this).parents('.msgbox_chat_window');
        localStorage.setItem("hidde_chat_"+ a.attr('id'), true);
        a.hide();
        e.preventDefault();
    });

    // ჩატის ტექსტის აკრეფინს ფანჯარაზე ენტერ ღილაკზე დაჭერა
    $("#msgbox_container").on('keyup', '.msgbox_usermsg', function () {
        if (event.keyCode == 13 ) {

            var message = event.target.value;
            var id = makeRandomString();
            var chat_uniq_id = $(event.target).parents('.msgbox_chat_window').attr('id');
            socket.emit('sendMessage', {
                chat_uniq_id:  chat_uniq_id ,
                message: message,
                id: id
            });
            event.target.value = '';
            addMessage(chat_uniq_id, id, message);
        }
    });


});

socket.on('message', function (data) {
    console.log('execute: message');
    //console.log(data);

    socket.emit('messageReceivedFromClient', { chatUniqId: data.chatUniqId, msgId: data.ran});
    var elChatbox = $("#"+data.chatUniqId +' .msgbox_chat_area');

    elChatbox.append(othTemplate((new Date()).toISOString().substr(11,8) , '', data.message ));
    elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');

});


//////////////////DONE//////////////////////////////////////////////////

// სათაურში ამოკლებს ტექსტს
function shorter(data) {
    if (!data) {
        return data;
    }

    if (data.length > 25) {
        return  data.substr(0,25) +"...";
    }
    return data;
}

//ეს მოდის როცა ახალი მომხმარებელი შემოვა ჩატში,
socket.on('checkClientCount', function (){
    console.log('execute: checkClientCount');
    socket.emit('getWaitingList');

});

// სერვისების სია, ბიბლიოთეკა
socket.emit('clientGetServices');
socket.on('clientGetServicesResponse', function (data) {
    console.log('execute: clientGetServicesResponse');
    if ($.isArray(data)){
        $.each(data, function(key, value) {
            services[value.category_service_id] = value.service_name;

        });
    }
});

//აბრუნებს რიგში მყოფი, ოპერატორების მომლოდინეების სიას
socket.on('getWaitingListResponse', function (data){
    console.log('execute: getWaitingListResponse');
    //console.log(data);
    var ans="";
    if (Array.isArray(data)){
        $.each(data,function(key, value) {
            if (value) {
                ans = ans + '<tr><td><a href="#" onclick="return getNextWaitingClient('+key+');" '+ services[key]+ ">"+services[key]+"</a></a></td><td>";
                $.each(value, function(i, val){
                    ans = ans + val.first_name+" " + val.last_name + ", ";
                });
                ans = ans + "</td></tr>"
            }
        })
    }
    $("#clients_queee_body").html(ans);
});



socket.on('getActiveChatsResponse', function (data){
    console.log('execute: getActiveChatsResponse');
    //console.log(data);

var i = 1;
    $('#online_chats_list tbody').html('');

    data.forEach(function(item){

        $('#online_chats_list').append(

            '<tr>'+
            '<td>'+i+++'</td>'+
            '<td> </td>'+
            '<td>მომხმრებელი</td>'+
            '<td>'+services[item.service_id]+'</td>'+
            '<td>'+item.add_date+'</td>'+
            '<td>'+
            '<a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>'+
            '  <a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>'+
            '   <a href="#" class="on-default edit-row"><i class="fa md-pageview" data-toggle="tooltip" data-placement="left" title="დათვალიერება"></i></a>&nbsp;&nbsp;'+
            '<a href="#" class="on-default edit-row"><i class="fa fa-play-circle-o" data-toggle="tooltip" data-placement="right" title="საუბარში ჩართვა"></i></a>'+
            '</td>'+
            '</tr>'
        );
    });

    return;
    var ans="";

    if (Array.isArray(data)){
        $.each(data,function(key, value) {
            if (value) {
                ans = ans + '<tr><td><a href="#" onclick="return getNextWaitingClient('+key+');" '+ services[key]+ ">"+services[key]+"</a></a></td><td>";
                $.each(value, function(i, val){
                    ans = ans + val.first_name+" " + val.last_name + ", ";
                });
                ans = ans + "</td></tr>"
            }
        })
    }
    $("#clients_queee_body").html(ans);
});

//აიღებს პირველ მოლოდინში მყოფ კლიენტს და აბრუნებს ჩატის იდ-ს
socket.on('getNextWaitingClientResponse', function (data){
    console.log('execute: getNextWaitingClientResponse');
    //console.log(data);
    createChatWindowAndLoadData(data);
    socket.emit('getWaitingList');
});

//ჩატის ფანჯარაში ტექსტის დამატების ფუნქცია
function addMessage(chat_uniq_id, id, message){
    var msg = meTemplate(id, (new Date()).toISOString().substr(11,8)  ,message );

    var elChatbox = $("#"+chat_uniq_id + ' .msgbox_chat_area');
    elChatbox.append(msg);

    var height = elChatbox[0].scrollHeight;
    elChatbox.scrollTop(height);
}

function makeRandomString() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 15; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

////////////////////////////////////////////////// //////////////////////


socket.io.on('reconnect', function () {
    console.log('execute: reconnect');
    socket.emit('checkToken',{token : token});
});


socket.on('messageReceived', function (data) {
    console.log('execute: messageReceived');
    //console.log(data);
    var el = $('#message_' + data.msgId);

    el.val('submited');
    el.css({'background-color': 'greenyellow'});
});

socket.on('clientMessageResponse', function (data) {
    console.log('execute: clientMessageResponse');
    //console.log(data);
});


function redAlert(id) {
    var el = $('#message_' + id);
    if (el.val() != 'submited') el.css({'background-color': 'red'});
}

