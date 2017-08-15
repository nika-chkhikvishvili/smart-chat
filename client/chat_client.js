/**
 * Created by jedi on 2017-05-20
 */

'use strict';

function ChatClient($, socket) {
    let me = this;
    let chatUniqId = localStorage.getItem("chatUniqId") || '';
    let firstName = '';
    let lastName = '';
    let lastWriteTime = 0;
    let lastWorkingTime = 0;
    let lastMeWritingTime = Date.now();

    let infoMessagePanel = $("#info_message_panel");
    let chatCloseInfoShowed = false;
    let chatCloseWarningShowed = false;
    let conversationStarted = false;
    let warningBeep = new Audio('/assets/audio/warning_beep.mp3');

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

    function resetWarnings(){
        lastMeWritingTime = Date.now();
        chatCloseInfoShowed = false;
        chatCloseWarningShowed = false;
    }

    function addMyMessageFn(id, time, message, dontScrool) {
        // console.log(data);
        time = time || (new Date()).toISOString();

        resetWarnings();
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
        warningBeep.play();
        conversationStarted = true;
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
        setTimeout(chat.operatorIsWorkingHide, 60000);
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
        conversationStarted = false;
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
        resetWarnings();
    }

    function getChatUniqObjectFn() {
        return {
            chatUniqId: chatUniqId
        };
    }

    function executeClientLoopFunctionFn() {
        // if (!socket.connected) {
        //     infoMessagePanel.find('.modal-title').html('კავშირის პრობლემა');
        //     infoMessagePanel.find('.modal-body').html('სერვერთან კავშირი გაწყდა, გთხოვთ დაელოდოთ');
        //     infoMessagePanel.modal();
        // } else {
        //
        // }

        if (conversationStarted) {
            let chatCloseTime = localStorage.getItem('chatCloseTime') || 80000;

            if (Date.now() - lastMeWritingTime > 60000) {
                infoMessagePanel.find('.modal-body').html(Math.round((chatCloseTime +lastMeWritingTime -Date.now())/1000)  +  ' წამში ჩატი დაიხურება პასიურობის გამო');
                if (!chatCloseInfoShowed) {
                    chatCloseInfoShowed = true;
                    infoMessagePanel.find('.modal-title').html('უმოქმედობა');
                    infoMessagePanel.modal();
                    warningBeep.play();
                }
            }

            if (!chatCloseWarningShowed && Date.now() - lastMeWritingTime > chatCloseTime) {
                chatCloseWarningShowed = true;
                infoMessagePanel.find('.modal-title').html('უმოქმედობა');
                infoMessagePanel.find('.modal-body').html('ჩატი დაიხურა უმოქმედობის გამო');
                infoMessagePanel.modal();
                socket.emit('clientCloseChat');
                closeChatFn();
            }
        }

    }

    return {
        executeClientLoopFunction: executeClientLoopFunctionFn,
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