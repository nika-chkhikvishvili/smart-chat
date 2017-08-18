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
    let warningBeep = new Audio('/assets/audio/warning_beep.ogg');

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
        resetWarnings();
        elChatbox.append('<div class="operator_is_working">გთხოვთ დაელოდოთ</div>');
        this.scrollDown();
        lastWorkingTime = Date.now();
        setTimeout(chat.operatorIsWorkingHide, parseInt(auto_answering.repeat_auto_answering) * 1000 - 5000);
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
            $('.operator_is_working').hide();
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
            let chatCloseTime = localStorage.getItem('chatCloseTime') ;

            if (!chatCloseTime && sys_control_params.hasOwnProperty('passive_client_time') && !!sys_control_paramspassive_client_time) {
                chatCloseTime = parseInt(sys_control_params.passive_client_time) * 1000;
            }
            if (!chatCloseTime) {
                chatCloseTime = 120000;
            }
            chatCloseTime = parseInt(chatCloseTime);

            if (Date.now() - lastMeWritingTime > chatCloseTime - 60000) {
                let str = auto_answering.passive_client_geo || '{time} წამში ჩატი დაიხურება პასიურობის გამო';
                let repl = Math.round((chatCloseTime + lastMeWritingTime -Date.now())/1000);
                infoMessagePanel.find('.modal-body').html( str.replace('{time}', repl ));
                if (!chatCloseInfoShowed) {
                    chatCloseInfoShowed = true;
                    infoMessagePanel.find('.modal-title').html('უმოქმედობა');
                    infoMessagePanel.modal();
                    let playPromise = warningBeep.play();
                    // playPromise.then(function() {
                    //     // Automatic playback started!
                    // }).catch(function(error) {
                    //     // Automatic playback failed.
                    //     // Show a UI element to let the user manually start playback.
                    // });
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