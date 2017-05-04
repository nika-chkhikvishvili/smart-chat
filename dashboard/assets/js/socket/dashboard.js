/**
 * Created by jedi on 2016-07-02.
 */


var services = [];
var me;
var choose_redirect_person_locker = false;

var meTemplate = jQuery.validator.format("<div><div class='msgln' id='message_{0}'>({1}) : {2}<br></div></div>");
var othTemplate = jQuery.validator.format("<div class='msglnr'>({0}) <b>{1}</b>: {2}<br></div>");


function close_chat(){
    var chat_uniq_id = $('.active-chat').attr('data-chat');
    if (!chat_uniq_id  || chat_uniq_id === '') return;


    var exit = confirm("Are you sure you want to end the session?");
    if (exit === true) {
        socket.emit('clientCloseChat', {chatUniqId : chat_uniq_id});
        $(".person[data-chat = " + chat_uniq_id + "]").remove();
        $(".chat[data-chat = " + chat_uniq_id + "]").remove();
    }
}

function redirect_to_service(serviceId){
    var chatUniqId = $( "#chat-redirect-type-dialog" ).data( 'chatUniqId' );
    $( "#chat-redirect-group-dialog" ).dialog("close" );
    var exit = confirm("ნამდვილად გსურთ ამ ჯგუფის არჩევა?");
    if (exit === true) {
        socket.emit('redirectToService', {chatUniqId: chatUniqId, serviceId: serviceId});
    }

}

socket.on('redirectToServiceResponse', function (data) {
    console.log('execute: redirectToServiceResponse');
    console.log(data);
    if (!data.isValid){
        alert('ვერ მოხერხდა გადამისამართება');
        return ;
    }

    if (data.redirectType === 1) {
        $(".person[data-chat = " + data.chatUniqId + "]").remove();
        $(".chat[data-chat = " + data.chatUniqId + "]").remove();
    }
});


socket.on('redirectToServiceResponse', function (data) {
    console.log('execute: redirectToServiceResponse');
    console.log(data);
    if (!data.isValid){
        alert('ვერ მოხერხდა გადამისამართება');
        return ;
    }

    $(".person[data-chat = " + data.chatUniqId + "]").remove();
    $(".chat[data-chat = " + data.chatUniqId + "]").remove();

});

function redirect_to_person(personId){
    var chatUniqId = $( "#chat-redirect-type-dialog" ).data( 'chatUniqId' );
    var redirectType = $("#chat-redirect-person-dialog").data( 'redirectType' );

    $( "#chat-redirect-person-dialog" ).dialog("close" );
    var exit = confirm("ნამდვილად გსურთ ამ პიროვნების არჩევა?");
    if (exit === true) {
        socket.emit('redirectToPerson', {chatUniqId: chatUniqId, personId: personId, redirectType: redirectType});
    }
}
socket.on('redirectToPersonResponse', function (data) {
    console.log('execute: redirectToPersonResponse');
    // console.log(data);

    if (!data.isValid){
        alert('ვერ მოხერხდა პიროვნების დამატება');
        return ;
    }

});

socket.on('getPersonsForRedirectResponse', function (data) {
    console.log('execute: getPersonsForRedirectResponse');
    // console.log(data);
    if (!data || !Array.isArray(data)){
        alert('ver moxerxda gadamisamarteba');
        choose_redirect_person_locker = false;
        return ;
    }

    var chatUniqId = $( "#chat-redirect-type-dialog" ).data( 'chatUniqId' );
    $( "#chat-redirect-type-dialog" ).dialog("close" );

    var personDialog = $("#chat-redirect-person-dialog");
    var tbl = personDialog.find("tbody");

    tbl.html('');

    data.forEach(function(item){
        console.log(item);
        tbl.append('<tr>' +
            '<td>' + item.person_id + '</td>' +
            '<td><a href="javascript:redirect_to_person(' + item.person_id + ');">' + item.first_name + ' ' + item.last_name + '</a></td>' +
            '</tr>');
    });

    var dialogGroup = personDialog.dialog({
        autoOpen: false,
        height: 400,
        width: 750,
        modal: true,
        buttons: {
            Ok: function() {
            },
            Cancel: function() {
                dialogGroup.dialog( "close" );
            }
        },
        close: function() {
        }
    });

    dialogGroup.dialog( "open" );
    choose_redirect_person_locker = false;
});


function choose_redirect_person_dialog(redirectType) {
    if (choose_redirect_person_locker) return ;
    choose_redirect_person_locker = true;
    $("#chat-redirect-person-dialog").data( 'redirectType', redirectType );
    socket.emit('getPersonsForRedirect', {});
}

function choose_redirect_group() {
    var chatUniqId = $( "#chat-redirect-type-dialog" ).data( 'chatUniqId' );
    $( "#chat-redirect-type-dialog" ).dialog("close" );

    var dialogGroup = $( "#chat-redirect-group-dialog" ).dialog({
        autoOpen: false,
        height: 400,
        width: 750,
        modal: true,
        buttons: {
            Ok: function() {

            },
            Cancel: function() {
                dialogGroup.dialog( "close" );
            }
        },
        close: function() {

        }
    });

    dialogGroup.dialog( "open" );
}

function choose_redirect_type(){
    var chatUniqId = $('.active-chat').attr('data-chat');
    if (!chatUniqId  || chatUniqId === '') return;


    $( "#chat-redirect-type-dialog" ).data( 'chatUniqId', chatUniqId );

    var dialog = $( "#chat-redirect-type-dialog" ).dialog({
        autoOpen: false,
        height: 400,
        width: 550,
        modal: true,
        buttons: {
            Ok: function() {

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


function ban_person(){
    var chat_uniq_id = $('.active-chat').attr('data-chat');
    if (!chat_uniq_id  || chat_uniq_id === '') return;

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
var createChatWindowAndLoadDataSimple = function(data){
    console.log('execute: createChatWindowAndLoadData');
    // console.log(data);

    if (!data || !data.chatUniqId) {
        return;
    }


    $('.wrapper_chat .container_chat .left .people').append('<li class="person" data-chat="' + data.chatUniqId + '">'+
        '<img src="http://s13.postimg.org/ih41k9tqr/img1.jpg" alt="" />'+
        '<span class="name">'+ data.firstName + ' '+ data.lastName+'</span>'+
        '<span class="new_message_icon"></span>'+
        '<span class="time"></span>'+
        '<span class="preview">...</span>'+
        '</li>');

    $('.wrapper_chat .container_chat .right .chats_container').append('<div> <div class="chat" data-chat="' + data.chatUniqId + '"></div></div>');

    socket.emit('getAllChatMessages', { chatUniqId: data.chatUniqId});

};

var createChatWindowAndLoadData = function(data){
    console.log('execute: createChatWindowAndLoadData');
    // console.log(data);

    if (!data || !data.chatUniqId) {
        return;
    }
    var elChatbox = $(".chat[data-chat = " + data.chatUniqId + "]");
    if (elChatbox.length>0) {
        return ;
    }

    var d = {
        chatUniqId : data.chatUniqId,
        firstName : data.chat.guestUser.firstName || '',
        lastName : data.chat.guestUser.firstName || ''
    };
    createChatWindowAndLoadDataSimple(d);


};

socket.on('getAllChatMessagesResponse', function (data) {
    console.log('execute: getAllChatMessagesResponse');
    console.log(data);
    var elChatbox = $(".chat[data-chat = "+data.chatUniqId+"]");

    elChatbox.append('<div class="conversation-start">'+
        '<span>Today, 6:48 AM</span>'+
        '</div>');

    data.messages.forEach(function(item){
        if(item.guestUserId){
            elChatbox.append('<div class="bubble you">'+ item.chat_message + '</div>' );
        } else {
            elChatbox.append('<div class="bubble me">'+ item.chat_message + '</div>' );
        }
    });

});

socket.on('checkTokenResponse', function (data){
    console.log('execute: checkTokenResponse');
    // console.log(data);

    if (data.isValid){
        //socket.emit('get',{token : token});

        $(".person").remove();
        $(".chat").remove();

        socket.emit('getWaitingList');
        socket.emit('getActiveChats');

        if (Array.isArray(data.ans) ) {
            data.ans.forEach(function(i){
                // console.log(i);

                var d = {
                    chatUniqId : i.chatUniqId,
                    firstName : i.first_name || '',
                    lastName : i.last_name || ''
                };
                createChatWindowAndLoadDataSimple(d)
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
            $(this).removeClass('new_message');
            $('.chat[data-chat = '+findChat+']').addClass('active-chat');

            // $(this).attr('data-chat')
            $('#im_working_checkbox')[0].checked=$(this).data('ImWorking');

            var elChatbox = $('.active-chat');
            elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
        }
    });

    $('#im_working_checkbox').click(function() {
        $('.active-chat').data('ImWorking', this.checked);
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
    $(".write input").on('keyup', function (event) {
        if (event.keyCode === 13 ) {
            $(".wrapper_chat .send").click();
        }
    });

    //სასაუბრო ფანჯრის დამალვა
    $(".wrapper_chat").on('click', '.send', function (e) {

        var elChatbox = $('.active-chat');

        if (elChatbox.size() === 0) {
            alert('არ არის არჩეული პიროვნება');
            return ;
        }

        var message = $("div.write input").val();
        var id = makeRandomString();
        var chatUniqId = elChatbox.attr('data-chat');

        socket.emit('sendMessage', {
            chatUniqId:  chatUniqId ,
            message: message,
            id: id
        });
        elChatbox.append('<div class="bubble me">'+ message + '</div>' );
        $(".write input").val('');

        elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
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


    function searchTemplates() {
        var needle =$("#template_dialog_form_search_field").val();

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
                if (needle && (tmpl.template_text_ge.indexOf(needle) !==-1 ||
                    tmpl.template_text_en.indexOf(needle) !==-1 ||
                    tmpl.template_text_ru.indexOf(needle) !==-1 )
                ) ul.append('<li data-serviceId='+tmpl['service_id']+' data-lang='+lang+'>'+tmpl[field_name]+'</li>');
            }
        });
    }


    $("#template_dialog_form_search_field").keyup(searchTemplates);
    $("#template_service, #template_lang").change(searchTemplates);

    setInterval(function(){
        $('.chat').each(function(key,val){
            var element = $(val);
            if(!element.data('lastSendTime')) element.data('lastSendTime', new Date(0));

            if (element.data('ImWorking')) {
                // console.log(element.data('chat'));


                console.log(element.data('lastSendTime') );
            }

            // if(val.checked){
            //     ++val.lastWorkingCount;
            //     if(val.lastWorkingCount>5) {
            //         val.lastWorkingCount = 0;
            //         socket.emit('operatorIsWorking',{chat_uniq_id: $(val).parents('.msgbox_chat_window').attr('id') });
            //     }
            //
            // } else {
            //     val.lastWorkingChecked = new Date();
            //     val.lastWorkingCount = 0;
            // }

        });
    }, 1000);

});

socket.on('message', function (data) {
    console.log('execute: message');
    console.log(data);

    socket.emit('messageReceivedFromClient', {chatUniqId: data.chatUniqId, msgId: data.ran});

    var elChatbox = $(".chat[data-chat = " + data.chatUniqId + "]");

    if(data.messageType === 'writing') {
        // $('#operator_is_writing').show();
        //
        // setTimeout(function(){
        //     $('#operator_is_writing').hide();
        // },3000);

    } else if(data.messageType === 'close') {
        alert('მომხმარებელმა ჩატი დახურა');
        $(".person[data-chat = " + data.chatUniqId + "]").remove();
        $(".chat[data-chat = " + data.chatUniqId + "]").remove();
    } else {

    if (data.guestUserId) {
        elChatbox.append('<div class="bubble you">' + data.message + '</div>');

        if (!($(".person[data-chat = " + data.chatUniqId + "]").hasClass('active'))) {
            $(".person[data-chat = " + data.chatUniqId + "]").addClass('new_message');
        }
    } else {
        elChatbox.append('<div class="bubble me">' + data.message + '</div>');
    }

    $(".person[data-chat = " + data.chatUniqId + "] .preview").html(shorter(data.message));
    $(".person[data-chat = " + data.chatUniqId + "] .time").html(Date().substr(16, 5));
    if (elChatbox && elChatbox[0]) elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
}
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
    // console.log(data);
    var ans="";
    if (Array.isArray(data)){
        $.each(data,function(key, value) {
            if (value) {
                ans = ans + '<tr><td>'+services[key]+"</td><td>";
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
    // console.log(data);

    var i = 1;
    var tableBody = $('#online_chats_list').find('tbody').html('');

    data.forEach(function(item){
        console.log(item);
        var operator = 'ჯერ არ შესულა ოპერატორი';
        if (item.users && Array.isArray(item.users)) {
            operator = item.users[0].firstName + ' ' + item.users[0].firstName;
        }

        tableBody.append(
        ' <tr>'+
        ' <td>'+ item.chat_id +'</td>'+
        ' <td>' + operator + '</td>'+
        ' <td>'+ item.user_first_name + ' ' + item.user_last_name +'</td>'+
        ' <td>' + item.service_name_geo + '</td>'+
        ' <td>' + item.add_date + '</td>'+
        ' <td>'+
        ' <a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>'+
        ' <a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>'+
        ' <a href="javascript:joinToRoom(\''+item.chat_uniq_id +'\',1);" class="on-default edit-row"><i class="fa md-pageview" data-toggle="tooltip" data-placement="left" title="დათვალიერება"></i></a>&nbsp;&nbsp;'+
        ' <a href="javascript:joinToRoom(\''+item.chat_uniq_id+'\',2);" class="on-default edit-row"><i class="fa fa-play-circle-o" data-toggle="tooltip" data-placement="right" title="საუბარში ჩართვა"></i></a>'+
        ' </td>'+
        ' </tr>'
        );
    });
});

function joinToRoom(chatUniqId, joinType) {
    socket.emit('joinToRoom', {chatUniqId:chatUniqId, joinType: joinType});
}



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

    // var height = elChatbox[0].scrollHeight;
    // elChatbox.scrollTop(height);

    elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
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
    if (el.val() !== 'submited') el.css({'background-color': 'red'});
}


socket.on('newChatWindow', function (data) {
    console.log('execute: newChatWindow');
    // console.log(data);
    createChatWindowAndLoadData(data);
});