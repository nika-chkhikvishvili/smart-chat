'use strict';

/**
 * Created by jedi on 2017-08-02
 */

function DashboardChat($, socket) {

    let me = this;
    let closeChatWindow = new Audio('/assets/chat/audio/button-2.mp3');
    let newChatWindowAudio = new Audio('/assets/chat/audio/new_chat.ogg');
    let newMessage = new Audio('/assets/chat/audio/button-09.mp3');
    let infoMessagePanel = $("#info_message_panel");
    let chats= new Map();

    function showInfoMessageFn(str = '', title = '') {
        infoMessagePanel.find('.modal-title').html(title);
        infoMessagePanel.find('.modal-body').html(str);
        infoMessagePanel.modal();
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

        if (data.hasOwnProperty('playAudio') && !!data.playAudio) {
            newChatWindowAudio.play();
        }

        $('.wrapper_chat .container_chat .left .people').append('<li class="person ' + ro + '"  data-type="' + ro + '"  data-chat="' + data.chatUniqId + '">'+
            '<img src="https://cdn1.iconfinder.com/data/icons/social-messaging-productivity-1/128/profile2-48.png" alt="" />'+
            '<span class="name">'+ data.firstName + ' '+ data.lastName+'</span>'+
            '<span class="new_message_icon"></span>'+
            '<span class="only_view" style="color: darkred;">დათვალიერების რეჟიმი</span>'+
            '<span class="time"></span>'+
            '<span class="only_view close_readonly"><a href=\'javascript:leaveReadOnlyRoom("'+ data.chatUniqId + '");\'>X</a></span>'+
            '<span class="preview">...</span>'+
            '</li>');

        $('.wrapper_chat .container_chat .right .chats_container').append('<div> <div class="chat" data-chat="' + data.chatUniqId + '"></div></div>');

        chats.set(data.chatUniqId, new ChatWindow($, socket, data.chatUniqId));
    }

    function closeChatFn(chatId, closedBy) {
        let chat = chats.get(chatId);
        if (!chat) {
            return false;
        }
        chat.close(closedBy);
        chats.delete(chatId);
    }

    return {
        showInfoMessage:showInfoMessageFn,
        createChatWindow: createChatWindowFn,
        messageGuest: messageGuestFn,
        messageMe: messageMeFn,
        closeChat: closeChatFn,
    };


    function ChatWindow($, socket, chatId){
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

        this.messageMeFun=function (message){
            chatBox.append('<div class="bubble me">' + message + '</div>');
            chatBox.animate({scrollTop: chatBox[0].scrollHeight}, 'normal');
        };



        this.messageGuestFun=function (message){
            chatBox.append('<div class="bubble you">' + message + '</div>');
            personBox.find(".preview").html(shorter(message));
            personBox.find(".time").html(new Date().toISOString().substr(11, 5));
            chatBox.animate({scrollTop: chatBox[0].scrollHeight}, 'normal');

            if (!(personBox.hasClass('active'))) {
                personBox.addClass('new_message');
                newMessage.play();
            }
        };

        this.closeChatFun = function (closedBy){
            let infoStr = '';
            if (closedBy === 'me') {
                infoStr = 'თქვენ დახურეთ ჩატი';
            } else if (closedBy === 'guest') {
                infoStr = 'ჩატი დაიხურა კლიენტის მიერ';
            } else {
                infoStr = 'ჩატი დასრულდა';
            }
            showInfoMessageFn(infoStr);
            this.personBox.remove();
            this.chatBox.remove();
        };
    }

}
