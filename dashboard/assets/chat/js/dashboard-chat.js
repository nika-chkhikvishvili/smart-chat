/**
 * Created by jedi on 2017-08-02
 */

function DashboardChat($, socket) {
    'use strict';

    let me = this;
    let closeChatWindow = new Audio('/assets/chat/audio/button-2.mp3');
    let newChatWindowAudio = new Audio('/assets/chat/audio/button-09.mp3');
    let newMessage = new Audio('/assets/chat/audio/button-09.mp3');
    let infoMessagePanel = $("#info_message_panel");
    let chats= new Map();

    function showInfoMessageFn(str = '', title = '') {
        infoMessagePanel.find('.modal-title').html(title);
        infoMessagePanel.find('.modal-body').html(str);
        infoMessagePanel.modal();
    }
    function hideLoaderFn() {
        $("#overlay").hide();
    }

    function showLoaderFn() {
        $("#overlay").show();
    }

    function messageGuestFn(chatId, message){
        let chat = chats.get(chatId);
        if (!chat) {
            return false;
        }
        chat.messageGuestFun(message);
    }

    function messageMeFn(chatId, message){
        let chat = chats.get(chatId);
        if (!chat) {
            return false;
        }
        console.log(chat);
        chat.messageMeFun(message);
    }

    function createChatWindowFn(data){
        let ro = '';

        if (data.joinType === 2){
            ro = 'readonly';
        }

        if (data.hasOwnProperty('playAudio') && data.playAudio) {
            newChatWindowAudio.play();
        }

        $('.wrapper_chat .container_chat .left .people').append('<li class="person ' + ro + '"  data-type="' + ro + '"  data-chat="' + data.chatUniqId + '">'+
            '<img src="https://cdn1.iconfinder.com/data/icons/social-messaging-productivity-1/128/profile2-48.png" alt="" />'+
            '<span class="name">'+ data.firstName + ' '+ data.lastName+'</span>'+
            // '<span class="new_message_icon"></span>'+
            '<span class="badge">0</span>'+
            '<span class="only_view" style="color: darkred;">დათვალიერების რეჟიმი</span>'+
            '<span class="time"></span><br>'+
            '<span class="only_view close_readonly"><a href=\'javascript:leaveReadOnlyRoom("'+ data.chatUniqId + '");\'>X</a></span>'+
            '<span class="preview">...</span>'+
            '</li>');

        $('.wrapper_chat .container_chat .right .chats_container').append('<div> <div class="chat" data-chat="' + data.chatUniqId + '"></div></div>');

        let chat = new ChatWindow($, socket, data.chatUniqId);
        chat.setName(data.firstName + ' '+ data.lastName);
        chats.set(data.chatUniqId, chat);
    }

    function closeDashboardChatFn(chatId, closedBy) {
        let chat = chats.get(chatId);
        if (!chat) {
            return false;
        }
        chat.closeChatFun(closedBy);
        chats.delete(chatId);
    }

    function makeActiveChatFn(chatId){
        let chat = chats.get(chatId);
        if (!chat) {
            return false;
        }

        $('.chat').removeClass('active-chat');
        $('.left .person').removeClass('active');
        chat.makeActiveChatFun();
    }

    function toggleIAmWorkingFn(chatId) {
        let chat = chats.get(chatId);
        if (!chat) {
            return false;
        }
        chat.toggleIAmWorkingFun()
    }

    function turnOffIAmWorkingFn(chatId) {
        let chat = chats.get(chatId);
        if (!chat) {
            return false;
        }
        chat.turnOffIAmWorkingFun()
    }

    function setAvailabilityFn(availability, changeSwitchPosition) {
        let available = availability === true;
        $('#available_circle').css("background-color", available ? 'green':'red');
        if (changeSwitchPosition === true) {
            $("#operator_on_of_switch").prop('checked', !available);
        } else {
            socket.emit('setAvailability', {isAvailable: available});
        }
/*
        let el = $('.online_operator_' + data.userId + ' i');
        if (data.available === true) {
            el.removeClass('offline');
            el.addClass('online');
        } else {
            el.removeClass('online');
            el.addClass('offline');
        }
        */
    }

    function executeLoopFunctionFn() {
        if (!socket.connected) {
            return;
        }

        chats.forEach(function(chat){
            chat.checkIAmWorkingAndSend();
        });

    }

    return {
        executeLoopFunction: executeLoopFunctionFn,
        showInfoMessage    : showInfoMessageFn,
        showLoader         : showLoaderFn,
        hideLoader         : hideLoaderFn,
        createChatWindow   : createChatWindowFn,
        messageGuest       : messageGuestFn,
        messageMe          : messageMeFn,
        closeDashboardChat : closeDashboardChatFn,
        makeActiveChat     : makeActiveChatFn,
        toggleIAmWorking   : toggleIAmWorkingFn,
        turnOffIAmWorking  : turnOffIAmWorkingFn,
        setAvailability    : setAvailabilityFn
    };

    function ChatWindow($, socket, chatIdInit){
        let chatId = chatIdInit;
        let isReadonly = false;
        let iAmWorking = false;
        let lastIAmWorkingSendTime = 0;

        let personName = '';
        let chatBox = $(".chat[data-chat=" + chatId + "]");
        let personBox = $(".person[data-chat=" + chatId + "]");

        function shorter(data) {
            if (!data) {
                return data;
            }

            if (data.length > 25) {
                return  data.substr(0,25) +"...";
            }
            return data;
        }

        this.setName = function (name) {
            personName = name;
        };

        this.messageMeFun=function (message){
            chatBox.append('<div class="bubble me">' + message + '</div>');
            chatBox.animate({scrollTop: chatBox[0].scrollHeight}, 'normal');
        };

        this.messageGuestFun=function (message){
            chatBox.append('<div class="bubble you">' + message + '</div>');
            personBox.find(".preview").html(shorter(message));
            let badge = personBox.find(".badge");
            badge.html(parseInt(badge.html() || 0) + 1);
            let curDate = new Date();
            personBox.find(".time").html(curDate.getHours() + ' ' + curDate.getMinutes());
            chatBox.animate({scrollTop: chatBox[0].scrollHeight}, 'normal');

            if (!(personBox.hasClass('active'))) {
                personBox.addClass('new_message');

                newMessage.play();
            }
        };

        this.closeChatFun = function (closedBy){
            let infoStr = '';

            if (closedBy === 'system') {
                infoStr = 'ჩატი დაიხურა კლიენტის სისტემიდან გასვლის გამო';
            } else if (closedBy === user.userName) {
                infoStr = 'თქვენ დახურეთ ჩატი';
            } else if (closedBy === 'guest') {
                infoStr = 'ჩატი დაიხურა კლიენტის მიერ';
            } else if (closedBy === 'redirect') {
                infoStr = 'ჩატი გადამისამართდა';
            } else {
                infoStr = 'ჩატი დასრულდა';
            }
            showInfoMessageFn(infoStr);
            personBox.remove();
            chatBox.remove();

        };

        this.makeActiveChatFun = function () {
            $('.right .top .name').html(personName.length > 14? personName.substring(0,12)+'...':personName);
            chatBox.addClass('active-chat');

            let src = iAmWorking?"assets/chat/images/autoremind_on.png" : "assets/chat/images/autoremind_off.png";
            $('#im_working_checkbox').attr("src", src);
            personBox.addClass('active');
            personBox.removeClass('new_message');
            personBox.find(".badge").html('0');
/*
            if (isReadonly) {
                $('.right').addClass('readonly');
            } else {
                $('.right').removeClass('readonly');
            }*/
            chatBox.animate({scrollTop: chatBox[0].scrollHeight}, 'normal');
        };

        this.toggleIAmWorkingFun = function () { //ImWorking
            iAmWorking = !iAmWorking;
            let src = iAmWorking?"assets/chat/images/autoremind_on.png" : "assets/chat/images/autoremind_off.png";
            $('#im_working_checkbox').attr("src", src);
        };

        this.checkIAmWorkingAndSend = function() {
            if (iAmWorking && Date.now() - lastIAmWorkingSendTime > imWorkingDelay ) {
                socket.emit('operatorIsWorking', {chatUniqId: chatId});
                lastIAmWorkingSendTime = Date.now();
            }
        };

        this.turnOffIAmWorkingFun = function() {
            if (iAmWorking) {
                this.toggleIAmWorkingFun();
            }
        }
    }
}
