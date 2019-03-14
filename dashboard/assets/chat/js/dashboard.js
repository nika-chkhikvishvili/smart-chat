/**
 * Created by jedi on 2016-07-02.
 */


let services = [];
let me;
let choose_redirect_person_locker = false;

let filesDialog = $("#dialog-form-files" );
let dialogFormTemplate = $("#dialog-form-template" );
let dialogUserBlock = $("#dialog-user-block" );
let dialogChatTypeRedirect = $("#chat-redirect-type-dialog" );
let dialogChatGroupRedirect = $("#chat-redirect-group-dialog" );
let chatManager = new DashboardChat($, socket);

/** BAN USER */

function approve_ban_person(){
    "use strict";
    let chatUniqueId = $('.active-chat').attr('data-chat');
    if (!chatUniqueId  || chatUniqueId === '') return;

    let msg = dialogUserBlock.find('textarea').val();
    socket.emit('banPerson', {
        chatUniqId:  chatUniqueId ,
        message: msg
    });
}

function ban_person(){
    let chatUniqueId = $('.active-chat').attr('data-chat');
    if (!chatUniqueId  || chatUniqueId === '') return;
    dialogUserBlock.find('textarea').val('');
    dialogUserBlock.modal();
}

socket.on('banPersonResponse', function (data) {
    console.log('execute: banPersonResponse');
    // console.log(data);
    if (!data || !data.hasOwnProperty('isValid')) {
        return;
    }
    if (data.isValid) {
        chatManager.showInfoMessage('წარმატებით გადაეგზავნა შესამოწმებლად');
    } else {
        chatManager.showInfoMessage('ბლოკირების შეცდომა');
    }
});

/** END BAN USER */

/** REDIRECT */

function choose_redirect_type(){
    let chatUniqueId = $('.active-chat').attr('data-chat');
    if (!chatUniqueId  || chatUniqueId === '') return;
    dialogChatTypeRedirect.modal();
}

/* Redirect to service */
function choose_redirect_group() {
    dialogChatTypeRedirect.modal("toggle");
    dialogChatGroupRedirect.modal();
}

function redirect_to_service(serviceId, serviceName){
    let chatUniqueId = $('.active-chat').attr('data-chat');
    if (!chatUniqueId  || chatUniqueId === '') return;

    bootbox.confirm({
        title: "გადამისამართება ჯგუფზე",
        message: "ნამდვილად გსურთ ჯგუფი " + serviceName +"-ს არჩევა?",
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
                dialogChatGroupRedirect.modal("toggle" );
                socket.emit('redirectToService', {chatUniqId: chatUniqueId, serviceId: serviceId});
            }
        }
    });
}

socket.on('redirectToServiceResponse', function (data) {
    console.log('execute: redirectToServiceResponse');
    console.log(data);
    if (!data.success){
        chatManager.showInfoMessage('ვერ მოხერხდა გადამისამართება', 'ჯგუფზე გადამისამართება');
        return ;
    }
    chatManager.closeDashboardChat(data.chatUniqId, 'redirect');
});

/* Redirect to person */

function choose_redirect_person_dialog(redirectType) {
    if (choose_redirect_person_locker) return ;
    choose_redirect_person_locker = true;
    $("#chat-redirect-person-dialog").data( 'redirectType', redirectType );
    socket.emit('getPersonsForRedirect', {});
    chatManager.showLoader();
}

socket.on('getPersonsForRedirectResponse', function (data) {
    chatManager.hideLoader();
    dialogChatTypeRedirect.modal("toggle" );
    let personDialog = $("#chat-redirect-person-dialog");
    personDialog.modal();
    choose_redirect_person_locker = false;
    return ;
  /*  console.log('execute: getPersonsForRedirectResponse');
    // console.log(data);
    chatManager.hideLoader();
    dialogChatTypeRedirect.modal("toggle" );
    if (!data || !Array.isArray(data)){
        chatManager.showInfoMessage('ვერ მოხერხდა თანამშრომლების სიის წამოღება', 'გადამისამართება პიროვნებაზე');
        choose_redirect_person_locker = false;
        return ;
    }

    let chatUniqueId = $('.active-chat').attr('data-chat');
    if (!chatUniqueId  || chatUniqueId === '') return;

    let personDialog = $("#chat-redirect-person-dialog");
    let tbl = personDialog.find("tbody");

    tbl.html('');

    data.forEach(function(item){
        console.log(item);
        tbl.append('<tr>' +
            '<td>' + item.person_id + '</td>' +
            '<td><a href="javascript:redirect_to_person(' + item.person_id + ');">' + item.first_name + ' ' + item.last_name + '</a></td>' +
            '</tr>');
    });
    personDialog.modal();
    choose_redirect_person_locker = false;*/
});

function redirect_to_person(personId){
    let chatUniqueId = $('.active-chat').attr('data-chat');
    if (!chatUniqueId  || chatUniqueId === '') return;
    let personDialog = $("#chat-redirect-person-dialog");
    let redirectType = personDialog.data( 'redirectType' );
    personDialog.modal("toggle" );

    bootbox.confirm({
        title: "გადამისამართება პიროვნებაზე",
        message: "ნამდვილად გსურთ ამ პიროვნების არჩევა?",
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
                socket.emit('redirectToPerson', {chatUniqId: chatUniqueId, personId: personId, redirectType: redirectType});
            }
        }
    });
}

socket.on('redirectToPersonResponse', function (data) {
    console.log('execute: redirectToPersonResponse');
    // console.log(data);

    if (!data.isValid){
        chatManager.showInfoMessage('ვერ მოხერხდა პიროვნების დამატება');
    }

});

/** END REDIRECT */

/** CLOSE CHAT */

function close_chat(){
    let chatUniqueId = $('.active-chat').attr('data-chat');
    if (!chatUniqueId  || chatUniqueId === '') return;

    bootbox.confirm({
        title: "ჩატის დასრულება",
        message: "ნამდვილად გსურთ საუბრის დასრულება?",
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
                socket.emit('operatorCloseChat', {chatUniqId : chatUniqueId});
            }
        }
    });

}

/** END CLOSE CHAT */

function send_file(fileId, fileName){

    let elChatbox = $('.active-chat');

    if (elChatbox.size() === 0) {
        alert('არ არის არჩეული პიროვნება');
        return ;
    }

    socket.emit('sendFile', {
        chatUniqId:  elChatbox.data('chat'),
        message: fileName,
        id: fileId
    });
    filesDialog.modal( "toggle" );
}

//ჩატის ფანჯარას ქმნის და მონაცემებს წამოიღებს
function createChatWindowAndLoadDataSimple(data){
    console.log('execute: createChatWindowAndLoadData');
    // console.log(data);

    if (!data || !data.chatUniqId) {
        return;
    }

    chatManager.createChatWindow(data);
    socket.emit('getChatAllMessages', { chatUniqId: data.chatUniqId});
}

function leaveReadOnlyRoom(chatId) {
    socket.emit('leaveReadOnlyRoom', chatId);
    $('[data-chat='+ chatId + ']').remove();
}

function takeRoom() {
    let elChatbox = $('.active-chat');
    let chatUniqId = elChatbox.data('chat');
    // $('[data-chat='+ chatUniqId + ']').remove();
    socket.emit('takeRoom', chatUniqId);
    $('.right').removeClass('readonly');
    $('.person').removeClass('readonly');
    socket.emit('joinToRoom', {chatUniqId:chatUniqId, joinType: 1});
}

function createChatWindowAndLoadData(data){
    console.log('execute: createChatWindowAndLoadData');
    console.log(data);

    if (!data || !data.chatUniqId) {
        return;
    }
    let elChatbox = $(".chat[data-chat = " + data.chatUniqId + "]");
    if (elChatbox.length>0) {
        return ;
    }

    let d = {
        chatUniqId : data.chatUniqId,
        firstName : data.guestUser.firstName || '',
        lastName : data.guestUser.lastName || '',
        joinType: data.joinType || 1,
        playAudio : true

    };
    createChatWindowAndLoadDataSimple(d);
}

socket.on('getAllChatMessagesResponse', function (data) {
    console.log('execute: getAllChatMessagesResponse');
    console.log(data);
    let elChatbox = $(".chat[data-chat = "+data.chatUniqId+"]");

/*    elChatbox.append('<div class="conversation-start">'+
        '<span>'+ new Date(item.messageDate).toISOString() +'</span>'+
        '</div><br>');*/

    data.messages.forEach(function(item){
        if(item.guestUserId){
            elChatbox.append('<div class="bubble you">'+ item.message + '</div>' );
        } else {
            elChatbox.append('<div class="bubble me">'+ item.message + '</div>' );
        }
    });

});

socket.on('checkTokenResponse', function (data){
    console.log('execute: checkTokenResponse');
    // console.log(data);

    if (data.isValid){
        $(".person").remove();
        $(".chat").remove();

        let available = (data.hasOwnProperty('isAvailable') && data.isAvailable === true);

        chatManager.setAvailability(available, true);

        socket.emit('getWaitingList');
        socket.emit('getActiveChats');

        if (Array.isArray(data.ans) ) {
            data.ans.forEach(function(i){
                // console.log(i);

                let d = {
                    chatUniqId : i.chatUniqId,
                    firstName : i.first_name || '',
                    lastName : i.last_name || '',
                    joinType : i.joinType || 1,
                    playAudio : false
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
    console.log(data);

   // clients_queee_body
});

$(document).ready(function () {

    if (!socket.disconnected) {
        $('#connection_to_server_circle').css("background-color", 'green');
    }

    //$('.chat[data-chat=person2]').addClass('active-chat');
    //$('.person[data-chat=person2]').addClass('active');

    //$('.left .person').mousedown(function(){

    $('.left').on('mousedown','.person', function(){
        let element = $(this);

        if (element.hasClass('.active')) {
            return false;
        } else {

            if (element.data('type') === 'readonly') {
                $('.right').addClass('readonly');
            } else {
                $('.right').removeClass('readonly');
            }

            chatManager.makeActiveChat(element.data('chat'));
        }
    });

    $('#im_working_checkbox').click(function() {
        let chat = $('.active-chat');
        let chatId = chat.data('chat');
        chatManager.toggleIAmWorking(chatId);
    });

    // checks and authoriser user
    //ამოწმებს ნამდვილად არის თუ არა აუტორიზებული პიროვნება, და ასევე აბრუნებს ღია ფანჯრების სიას
    socket.emit('checkToken',{token : token});


    //სასაუბრო ფანჯრის ჩაკეცვა
    $("#msgbox_container").on('click', '.msgbox_left', function () {
        let chatWindow = $(this).parents('.msgbox_chat_window');
        chatWindow.children( ".msgbox_chat_content" ).hide();
        chatWindow.children( ".msgbox_chat_minimized" ).show();
    });

    //ჩაკეცილი სასაუბრო ფანჯრის გადიდება
    $("#msgbox_container").on('click', '.msgbox_chat_minimized', function () {
        let chatWindow = $(this).parents('.msgbox_chat_window');
        chatWindow.children( ".msgbox_chat_content" ).show();
        chatWindow.children( ".msgbox_chat_minimized" ).hide();
    });

    //სასაუბრო ფანჯრის დამალვა
    $("#msgbox_container").on('click', '.msgbox_close', function (e) {
        let a = $(this).parents('.msgbox_chat_window');
        localStorage.setItem("hidde_chat_"+ a.attr('id'), true);
        a.hide();
        e.preventDefault();
    });

    // ჩატის ტექსტის აკრეფინს ფანჯარაზე ენტერ ღილაკზე დაჭერა
    $(".write textarea").on('keyup', function (event) {
        let elChatbox = $('.active-chat');
        socket.emit('operatorIsWriting', {chatUniqId: elChatbox.data("chat")});
        if (event.keyCode === 13 ) {
            $(".wrapper_chat .send").click();
        }
    });


    $(".person").on('click', '.close_readonly', function (e) {
        console.log(this);

    });

    //სასაუბრო ფანჯრის დამალვა1
    $(".wrapper_chat").on('click', '.send', function (e) {

        let elChatbox = $('.active-chat');

        if (elChatbox.size() === 0) {
            alert('არ არის არჩეული პიროვნება');
            return ;
        }

        let message = $("div.write textarea").val();
        if (message.length < 3 ) {
            return;
        }
        let id = makeRandomString();
        let chatUniqId = elChatbox.data('chat');

        socket.emit('sendMessage', {
            chatUniqId:  chatUniqId ,
            message: message,
            id: id
        });

        $(".write textarea").val('');
        autosize.update(document.querySelectorAll('.write textarea'));
        chatManager.turnOffIAmWorking(chatUniqId);
        // elChatbox.append('<div class="bubble me">'+ message + '</div>' );
        // elChatbox.animate({scrollTop: elChatbox[0].scrollHeight}, 'normal');
    });

    $(".wrapper_chat").on('click', '.attach', function (e) {
        filesDialog.modal();
    });

    $(".wrapper_chat").on('click', '.draft', function (e) {
        dialogFormTemplate.modal();
    });


    $("#dialog-form-template").on('click', 'li', function (e) {
        $("div.write textarea").val($(this).html());
        dialogFormTemplate.modal('toggle');
    });

    $("#template_dialog_form_table").on('click', 'td', function (e) {
        $("div.write textarea").val($(this).html());
        dialogFormTemplate.modal('toggle');
    });

    function searchFiles() {
        let needle =$("#dialog_form_files_search_field").val().toLowerCase();
        $('#dialog_form_files_table > tbody > tr').each(function (id, item) {
            let tritem = $(item);
            let keywords = tritem.data('keywords');
            if (keywords.toLowerCase().indexOf(needle) === -1){
                tritem.hide();
            } else {
                tritem.show();
            }
        });
    }


    function searchTemplates() {
        let needle =$("#template_dialog_form_search_field").val().toLowerCase();

        let template_service = $("#template_service");
        let template_lang    = $("#template_lang");
        let service = parseInt(template_service.val());
        let lang    = template_lang.val() || 'ka';

        $('#template_dialog_form_table > tbody > tr').each(function (id, item) {
            let tritem = $(item);
            let keywords = tritem.data('keywords');
            let itemService = tritem.data('service');
            let itemLang = tritem.data('lang');
            if ( (service === 0 || itemService === 0 || itemService === service) && keywords.toLowerCase().indexOf(needle) !== -1 && itemLang === lang){
                tritem.show();
            } else {
                tritem.hide();
            }
        });

    }

    searchTemplates();
    $("#template_dialog_form_search_field").keyup(searchTemplates);
    $("#dialog_form_files_search_field")   .keyup(searchFiles);
    $("#template_service, #template_lang") .change(searchTemplates);

    $("#operator_on_of_switch").change(function (obj) {
        chatManager.setAvailability(!obj.currentTarget.checked, false);
    });

    setInterval(function () {$.get( "/checksession", function( data ) {if (data !== 'OK' ) {window.location = '/';}});}, 60000);
    setInterval(chatManager.executeLoopFunction, 10000);
});

socket.on('connect', () => {
    console.log('execute: connect');
    $('#connection_to_server_circle').css("background-color", 'green');
});

socket.on('app_error', (data) => {
    console.log('execute: app_error');
    console.log(data);
});

socket.on('disconnect', (reason) => {
    console.log('execute: disconnect');
    console.log(reason);
    $('#connection_to_server_circle').css("background-color", 'red');
    $("#operator_on_of_switch").attr('checked', false);
    $('#available_circle').css("background-color", 'red');
});

socket.on('operatorIsWorking', function (data) {
    console.log('execute: operatorIsWorking');
    console.log(data);
});

socket.on('activeUsers', function (data) {
    console.log('execute: activeUsers');
    // console.log(data);
    let ool = $('#online_operators_list');
    ool.html('');
    $('#chat-redirect-person-dialog tbody tr').removeClass('user_is_online');
    $.each(data, function (id, user) {
        $('#redirect_person_tr_' + user.userId ).addClass('user_is_online');
        let onlcls =  !user.available ? 'offline' : 'online';
        ool.append('<li class="list-group-item online_operator_' + user.userId + '"> ' +
            '<a href="#"> ' +
            '<div class="avatar"> ' +
            '<img src="/assets/images/users/girl.png" alt=""> ' +
            '</div> ' +
            '<span class="name">' + user.firstName + ' ' + user.lastName + '</span> ' +
            '<span class="opened_chats">' + user.openChats + '</span> ' +
            '<i class="fa fa-circle ' + onlcls + '"></i> ' +
            '</a> ' +
            '<span class="clearfix"></span> ' +
            '</li>');
    });
});

socket.on('message', function (data) {
    console.log('execute: message');
    console.log(data);

    socket.emit('messageReceivedFromClient', {chatUniqId: data.chatUniqId, msgId: data.ran});

    if(data.messageType === 'writing') {
        console.log('ბეჭდავს');
        // $('#operator_is_writing').show();
        //
        // setTimeout(function(){
        //     $('#operator_is_writing').hide();
        // },3000);

    } else if(data.messageType === 'leave') {
        alert('თქვენ გახვედით ჩატიდან');
        $(".person[data-chat = " + data.chatUniqId + "]").remove();
        $(".chat[data-chat = " + data.chatUniqId + "]").remove();
    } else if(data.messageType === 'close') {
        chatManager.closeDashboardChat(data.chatUniqId, data.sender);
    } else if(data.messageType === 'operatorIsWorking') {

    } else {
    if (data.guestUserId) {
        chatManager.messageGuest(data.chatUniqId, data.message);
    } else {
        if (data.messageUniqId !== -158)
        chatManager.messageMe(data.chatUniqId, data.message);
    }
}
});

//////////////////DONE//////////////////////////////////////////////////

//ეს მოდის როცა ახალი მომხმარებელი შემოვა ჩატში,
socket.on('checkClientCount', function (){
    console.log('execute: checkClientCount');
    socket.emit('getWaitingList');

});

socket.on('checkActiveChats', function (){
    console.log('execute: checkActiveChats');
    socket.emit('getWaitingList');
    socket.emit('getActiveChats');
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
socket.on('getWaitingListResponse', function (data) {
    console.log('execute: getWaitingListResponse');
    // console.log(data);
    let ans="";
    if (Array.isArray(data.guests)){
        $.each(data.guests, function(key, value) {
            if (value) {
                ans = ans + '<tr><td><a class="btn" href="#" data-toggle="popover" data-content="' + data.serviceUsers[key] + '" ' +
                    ' rel="popover" data-placement="right" title="' + services[key] + '" data-trigger="hover" data-html="true">'
                    + services[key] + '</a></td><td>';

                $.each(value, function(i, val){
                    ans = ans + val.guestUser.firstName + " " + val.guestUser.lastName + ", ";
                });
                ans = ans + "</td></tr>"
            }
        })
    }
    $("#clients_queee_body").html(ans);
    $('[data-toggle="popover"]').popover();
});


socket.on('leak', function (data){
    console.log('execute: leak');
    console.log(data);
});

socket.on('stats', function (data){
    console.log('execute: stats');
    console.log(data);
});

socket.on('getStatisticResponse', function (data) {
    console.log('execute: getStatisticResponse');
    console.log(data);
});

socket.on('getActiveChatsResponse', function (data){
    console.log('execute: getActiveChatsResponse');
    // console.log(data);

    let tableBody = $('#online_chats_list').find('tbody').html('');

    data.forEach(function(item){
        let operator = 'ჯერ არ შესულა ოპერატორი';
        if (item.users && Array.isArray(item.users) && item.users.length > 0) {
            // console.log(item.users[0]);
            operator = item.users[0].firstName + ' ' + item.users[0].lastName;
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
        ' <a href="javascript:joinToRoom(\''+item.chat_uniq_id +'\',2);" class="on-default edit-row"><i class="fa md-pageview" data-toggle="tooltip" data-placement="left" title="დათვალიერება"></i></a>&nbsp;&nbsp;'+
        // ' <a href="javascript:joinToRoom(\''+item.chat_uniq_id+'\',1);" class="on-default edit-row"><i class="fa fa-play-circle-o" data-toggle="tooltip" data-placement="right" title="საუბარში ჩართვა"></i></a>'+
        ' </td>'+
        ' </tr>'
        );
    });
});

function joinToRoom(chatUniqId, joinType) {
    let exit = confirm("ნამდვილად გსურთ საუბარში ჩარევა?");
    if (exit === true) {
        socket.emit('joinToRoom', {chatUniqId:chatUniqId, joinType: joinType});
    }
}

//აიღებს პირველ მოლოდინში მყოფ კლიენტს და აბრუნებს ჩატის იდ-ს
socket.on('getNextWaitingClientResponse', function (data){
    console.log('execute: getNextWaitingClientResponse');
    //console.log(data);
    createChatWindowAndLoadData(data);
    socket.emit('getWaitingList');
});

function makeRandomString() {
    let text = "";
    let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( let i=0; i < 15; i++ )
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
    let el = $('#message_' + data.msgId);

    el.val('submited');
    el.css({'background-color': 'greenyellow'});
});

socket.on('clientMessageResponse', function (data) {
    console.log('execute: clientMessageResponse');
    //console.log(data);
});

function redAlert(id) {
    let el = $('#message_' + id);
    if (el.val() !== 'submited') el.css({'background-color': 'red'});
}

socket.on('newChatWindow', function (data) {
    console.log('execute: newChatWindow');
    // console.log(data);
    data.playAudio = true;
    createChatWindowAndLoadData(data);
});

socket.on('userInfo', function (data) {
    console.log('execute: userInfo');
    // console.log(data);
    user = data;

});

socket.on('getGuestResponse', function (data) {
    console.log('execute: getGuestResponse');
    console.log(data);
});

socket.on('userAvailabilityChanged', function (data) {
    console.log('execute: userAvailabilityChanged');
    // console.log(data);
    let el = $('.online_operator_' + data.userId + ' i');

    if (data.available === true) {
        el.removeClass('offline');
        el.addClass('online');
    } else {
        el.removeClass('online');
        el.addClass('offline');
    }

    if (user.userId === data.userId) {
        chatManager.setAvailability(data.available === true, true);
    }
    $('.online_operator_' + data.userId + ' .opened_chats').html(data.openChats);
});
