/**
 * Created by jedi on 2017-05-20
 */

'use strict';

function Chat($, socket) {
    let me = this;
    let chatUniqId = localStorage.getItem("chatUniqId") || '';
    let firstName = '';
    let lastName = '';
    let lastWriteTime = 0;
    let lastWorkingTime = 0;

    let elChatbox = $("#chat-body-ul");
    let scroolDiv = $("#scrooldiv");
    let meTemplate = $.validator.format('<li class="mar-btm">' +
            // '<div class="media-right">' +
            // '<img src="/assets/images/me.png" class="img-circle img-sm" alt="Profile Picture"> ' +
            // '</div> ' +
            '<div class="media-body pad-hor speech-right"> ' +
            '<div class="speech"> ' +
            '<a href="#" class="media-heading">{2}</a> ' +
            '<p>{3}</p> ' +
            // '<p class="speech-time"> ' +
            // '<i class="fa fa-clock-o fa-fw"></i> {1} ' +
            '</p> ' +
            '</div> ' +
            '</div> ' +
            '</li>');
    let othTemplate = $.validator.format('<li class="mar-btm">' +
            // '<div class="media-left">' +
            // '<img src="/assets/images/you.png" class="img-circle img-sm" alt="Profile Picture">' +
            // '</div>' +
            '<div class="media-body pad-hor">' +
            '<div class="speech"> ' +
            '<a href="#" class="media-heading">{1}</a>' +
            '<p>{2}</p>' +
            // '<p class="speech-time">' +
            // '<i class="fa fa-clock-o fa-fw"></i> {0}' +
            '</p>' +
            '</div>' +
            '</div>' +
            '</li>');

    function addMyMessageFn(id, time, message, dontScrool) {
        // console.log(data);
        time = time || (new Date()).toISOString();

        elChatbox.append(meTemplate(id, time.substr(11, 8), firstName + ' ' + lastName, message));
        //setTimeout(function () {
        //    redAlert(id);
        //}, 3000);
        if (dontScrool !== true) {
            this.scrollDown();
        }
    }

    function addOtherMessageFn(data, dontScrool) {
        // console.log(data);
        let time = data.messageDate || (new Date()).toISOString();
        elChatbox.append(othTemplate(time.substr(11, 8), data.sender, data.message));
        if (dontScrool !== true) {
            this.scrollDown();
        }
    }

    function scrollDownFn() {
        scroolDiv.animate({scrollTop: scroolDiv[0].scrollHeight}, 'normal');
    }

    function operatorIsWorkingShowFn() {
        $('#operator_is_working').show();
        lastWorkingTime = Date.now();
        setTimeout(chat.operatorIsWorkingHide, 5000);
    }

    function operatorIsWritingShowFn() {
        $('#operator_is_writing').show();
        lastWriteTime = Date.now();
        setTimeout(chat.operatorIsWritingHide, 3000);
    }

    function operatorIsWritingHideFn() {
        if (Date.now() - lastWriteTime > 2000) {
            $('#operator_is_writing').hide();
        }
    }

    function operatorIsWorkingHideFn() {
        if (Date.now() - lastWorkingTime > 4900) {
            $('#operator_is_working').hide();
        }
    }

    function banUserFn(text) {
        $('#asarchevi').hide();
        $('.panel').hide();
        $('.user_ban').html(text);
    }

    function closeChatFn() {
        delete localStorage.chatUniqId;
        elChatbox.html('');
        $('#asarchevi').show();
        $('.panel').hide();
        $('#begin_btn').attr({disabled: false});
        socket.emit('clientGetServices');
    }

    function cleanChatWindowFn() {
        elChatbox.html('');
    }

    function setUserInformationFn(fN, lN) {
        firstName = fN || '';
        lastName = lN || '';
        $('.panel-title').text(firstName + ' ' + lastName);
    }

    function getChatUniqIdFn() {
        return chatUniqId;
    }

    function setChatUniqIdFn(chatUniqIdParam) {
        localStorage.chatUniqId = chatUniqIdParam;
        chatUniqId = chatUniqIdParam;
    }

    function getChatUniqObjectFn() {
        return {
            chatUniqId: chatUniqId
        };
    }

    return {
        closeChat: closeChatFn,
        getChatUniqId: getChatUniqIdFn,
        getChatUniqObject: getChatUniqObjectFn,
        setChatUniqId: setChatUniqIdFn,
        cleanChatWindow: cleanChatWindowFn,
        scrollDown: scrollDownFn,

        addMyMessage: addMyMessageFn,
        addOtherMessage: addOtherMessageFn,

        setUserInformation: setUserInformationFn,
        banUser: banUserFn,
        operatorIsWorkingShow: operatorIsWorkingShowFn,
        operatorIsWorkingHide: operatorIsWorkingHideFn,
        operatorIsWritingHide:operatorIsWritingHideFn,
        operatorIsWritingShow: operatorIsWritingShowFn
    };
}