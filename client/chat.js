/**
 * Created by jedi on 2017-05-20
 */

'use strict';

function Chat($, socket) {
    var me = this;
    var chatUniqId = localStorage.getItem("chatUniqId") || '';
    var firstName = '';
    var lastName = '';
    var lastWriteTime = 0;

    var elChatbox = $("#chat-body-ul");
    var scroolDiv = $("#scrooldiv");
    var meTemplate = $.validator.format('<li class="mar-btm">' +
            '<div class="media-right">' +
            '<img src="http://bootdey.com/img/Content/avatar/avatar2.png" class="img-circle img-sm" alt="Profile Picture"> ' +
            '</div> ' +
            '<div class="media-body pad-hor speech-right"> ' +
            '<div class="speech"> ' +
            '<a href="#" class="media-heading">{2}</a> ' +
            '<p>{3}</p> ' +
            '<p class="speech-time"> ' +
            '<i class="fa fa-clock-o fa-fw"></i> {1} ' +
            '</p> ' +
            '</div> ' +
            '</div> ' +
            '</li>');
    var othTemplate = $.validator.format('<li class="mar-btm">' +
            '<div class="media-left">' +
            '<img src="http://bootdey.com/img/Content/avatar/avatar1.png" class="img-circle img-sm" alt="Profile Picture">' +
            '</div>' +
            '<div class="media-body pad-hor">' +
            '<div class="speech"> ' +
            '<a href="#" class="media-heading">{1}</a>' +
            '<p>{2}</p>' +
            '<p class="speech-time">' +
            '<i class="fa fa-clock-o fa-fw"></i> {0}' +
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
            me.scroolDownFn();
        }
    }

    function addOtherMessageFn(data, dontScrool) {
        // console.log(data);
        var time = data.messageDate || (new Date()).toISOString();
        elChatbox.append(othTemplate(time.substr(11, 8), data.sender, data.message));
        if (dontScrool !== true) {
            me.scroolDownFn();
        }
    }

    function scrollDownFn() {
        scroolDiv.animate({scrollTop: scroolDiv[0].scrollHeight}, 'normal');
    }

    function operatorIsWorkingShowFn() {
        $('#operator_is_working').show();
        setTimeout(me.operatorIsWorkingHide, 10000);
    }

    function operatorIsWorkingHide() {
        $('#operator_is_working').hide();
    }

    function operatorIsWritingShowFn() {
        $('#operator_is_writing').show();
        lastWriteTime = Date.now();
        setTimeout(me.operatorIsWritingHide, 3000);
    }

    function operatorIsWritingHide() {
        if (Date.now() - lastWriteTime > 3000) {
            $('#operator_is_writing').hide();
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
        operatorIsWritingShow: operatorIsWritingShowFn
    };
}