/**
 * Created by jedi on 2016-07-02.
 */


var services = [];
var me;

var meTemplate = jQuery.validator.format("<div><div class='msgln' id='message_{0}'>({1}) : {2}<br></div></div>");
var othTemplate = jQuery.validator.format("<div class='msglnr'>({0}) <b>{1}</b>: {2}<br></div>");


function close_chat(){

}

function ban_person(){
    var chat_uniq_id = $('.active-chat').attr('data-chat');
    if (!chat_uniq_id  || chat_uniq_id == '') return;

    var dialog = $( "#block-dialog" ).dialog({
        autoOpen: false,
        height: 400,
        width: 550,
        modal: true,
        buttons: {
            Ok: function() {

                var msg= $('#block-dialog textarea').val();
                socket.emit('banPerson', {
                    chat_uniq_id:  chat_uniq_id ,
                    message: msg
                });

                dialog.dialog( "close" );
                $('#block-dialog textarea').val('');
            },
            Cancel: function() {
                dialog.dialog( "close" );
            }
        },
        close: function() {

        }
    });

    dialog.dialog( "open" );
}

function isChatWindowHidden(id) {
    return localStorage.getItem("hidde_chat_"+id) || false;
}

//ჩატის ფანჯარას ქმნის და მონაცემებს წამოიღებს
var createChatWindowAndLoadData = function(data){
    console.log('execute: createChatWindowAndLoadData');
    console.log(data);

    if (!data || !data.chat_uniq_id) {
        return;
    }


    $('.wrapper_chat .container_chat .left .people').append('<li class="person" data-chat="' + data.chat_uniq_id + '">'+
        '<img src="http://s13.postimg.org/ih41k9tqr/img1.jpg" alt="" />'+
        '<span class="name">'+ data.first_name + ' '+ data.last_name+'</span>'+
    '<span class="time">2:09 PM</span>'+
    '<span class="preview">...</span>'+
    '</li>');

    $('.wrapper_chat .container_chat .right .chats_container').append(' <div class="chat" data-chat="' + data.chat_uniq_id + '"></div>');

    socket.emit('getAllChatMessages', { chat_uniq_id: data.chat_uniq_id});

};

socket.on('getAllChatMessagesResponse', function (data) {
    console.log('execute: getAllChatMessagesResponse');
    //console.log(data);
    var elChatbox = $(".chat[data-chat = "+data.chat_uniq_id+"]");

    elChatbox.append('<div class="conversation-start">'+
        '<span>Today, 6:48 AM</span>'+
        '</div>');

    data.messages.forEach(function(item){
        if(item.online_user_id){
            elChatbox.append('<div class="bubble you">'+ item.chat_message + '</div>' );
        } else {
            elChatbox.append('<div class="bubble me">'+ item.chat_message + '</div>' );
        }
    });


    //elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');

});

socket.on('checkTokenResponse', function (data){
    console.log('execute: checkTokenResponse');
    console.log(data);

    if (data.isValid){
        //socket.emit('get',{token : token});
        socket.emit('getWaitingList');
        // socket.emit('getActiveChats');

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

    //$('.chat[data-chat=person2]').addClass('active-chat');
    //$('.person[data-chat=person2]').addClass('active');

    //$('.left .person').mousedown(function(){

    $('.left').on('mousedown','.person', function(){
        if ($(this).hasClass('.active')) {
            return false;
        } else {
            var findChat = $(this).attr('data-chat');
            var personName = $(this).find('.name').text();
            $('.right .top .name').html(personName);
            $('.chat').removeClass('active-chat');
            $('.left .person').removeClass('active');
            $(this).addClass('active');
            $('.chat[data-chat = '+findChat+']').addClass('active-chat');
        }
    });

    //სასაუბრო ფანჯრის დამალვა
    $(".wrapper_chat").on('click', '.chat_close_button', function (e) {
        $(".container_chat").hide();
        $(".chat_open_button").show();
        $(".wrapper_chat").css(  {bottom: "9px",  right: "0px", width : "20px", height: "20px" });
    });

    $(".wrapper_chat").on('click', '.chat_open_button', function (e) {
        $(".container_chat").show();
        $(".chat_open_button").hide();
        $(".wrapper_chat").removeAttr('style');
    });

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

    //სასაუბრო ფანჯრის დამალვა
    $(".wrapper_chat").on('click', '.send', function (e) {

        if ($('.active-chat').size() == 0) {
            alert('არ არის არჩეული პიროვნება');
            return ;
        }

        var message = $("div.write input").val();
        var id = makeRandomString();
        var chat_uniq_id = $('.active-chat').attr('data-chat');
        socket.emit('sendMessage', {
            chat_uniq_id:  chat_uniq_id ,
            message: message,
            id: id
        });
        $('.active-chat').append('<div class="bubble me">'+ message + '</div>' );
        $(".write input").val('');
    });

    var dialog = $( "#template-dialog-form" ).dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
        buttons: {
            Cancel: function() {
                dialog.dialog( "close" );
            }
        },
        close: function() {

        }
    });

    $(".wrapper_chat").on('click', '.smiley', function (e) {
        dialog.dialog( "open" );

    });


    $("#template-dialog-form").on('click', 'li', function (e) {
        $("div.write input").val($(this).html());
        dialog.dialog( "close" );
    });


    $("#template_service, #template_lang").change(function (e) {
        var template_service = $("#template_service");
        var template_lang    = $("#template_lang");
        var ul = $("#template_dialog_form_ul");

        var service = template_service.val();
        var lang    = template_lang.val() || 'ka';
        var field_name = 'template_text_ge';
        if (lang === 'en' ) {
            field_name = 'template_text_en';
        }
        if (lang === 'ru' ) {
            field_name = 'template_text_ru';
        }

        ul.html('');

        messageTemplates.forEach(function(tmpl){
            if (service == 0 || service === tmpl.service_id) {
                ul.append('<li data-serviceId='+tmpl['service_id']+' data-lang='+lang+'>'+tmpl[field_name]+'</li>');
            }
        });
    });

    setInterval(function(){
        $('.msgbox_working_checkbox').each(function(key,val){
            if(val.checked){
                if(!val.hasOwnProperty('lastWorkingCount')) val.lastWorkingCount = 0;
                ++val.lastWorkingCount;
                if(val.lastWorkingCount>5) {
                    val.lastWorkingCount = 0;
                    socket.emit('operatorIsWorking',{chat_uniq_id: $(val).parents('.msgbox_chat_window').attr('id') });
                }

            } else {
                val.lastWorkingChecked = new Date();
                val.lastWorkingCount = 0;
            }

        });
    }, 1000);

});

socket.on('message', function (data) {
    console.log('execute: message');
    //console.log(data);

    socket.emit('messageReceivedFromClient', { chatUniqId: data.chatUniqId, msgId: data.ran});

    var elChatbox = $(".chat[data-chat = "+data.chatUniqId+"]");
    elChatbox.append('<div class="bubble you">'+ data.message + '</div>' );

    $(".person[data-chat = "+data.chatUniqId+"] .preview").html(shorter(data.message));
    $(".person[data-chat = "+data.chatUniqId+"] .time").html(Date().substr(16,5));

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
            services[value.category_service_id] = value.service_name_geo;

        });
    }
});

//აბრუნებს რიგში მყოფი, ოპერატორების მომლოდინეების სიას
socket.on('getWaitingListResponse', function (data){
    console.log('execute: getWaitingListResponse');
    console.log(data);
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



// socket.on('getActiveChatsResponse', function (data){
//     console.log('execute: getActiveChatsResponse');
//     return ;
//     console.log(data);
//
//     var i = 1;
//     $('#online_chats_list tbody').html('');
//
//     data.forEach(function(item){
//         console.log(item);
//         createChatWindowAndLoadData(item);
//     });
// });

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


socket.on('newChatWindow', function (data) {
    console.log('execute: newChatWindow');
    console.log(data);
    createChatWindowAndLoadData(data);
});