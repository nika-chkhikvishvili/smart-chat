/**
 * Created by jedi on 2017-04-21
 */

'use strict';

let uuid4 = require("uuid4");
let Message = require('./Message');

function Chat(initParams) {
    if (!(this instanceof Chat)) {
        return new Chat(initParams);
    }

    initParams = initParams || {};
    this.chatUniqId   = initParams.hasOwnProperty('chatUniqId')   ? initParams.chatUniqId      : uuid4();
    this.chatId       = initParams.hasOwnProperty('chatId')       ? initParams.chatId          : null;
    this.guestUserId  = initParams.hasOwnProperty('guestUserId')  ? initParams.guestUserId     : null;
    this.guestUser    = initParams.hasOwnProperty('guestUser')    ? initParams.guestUser       : null;
    this.serviceId    = initParams.hasOwnProperty('serviceId')    ? parseInt(initParams.serviceId)    : null;
    this.chatStatusId = initParams.hasOwnProperty('chatStatusId') ? parseInt(initParams.chatStatusId) : 0;
    this.language     = initParams.hasOwnProperty('language')     ? initParams.language : 'ka_GE';
    this.guestUserLeaveTime = Date.now();
    this.users        = new Map();
}

Chat.prototype.getInsertObject = function () {
    return {
        online_user_id: this.guestUserId,
        service_id: this.serviceId,
        chat_uniq_id: this.chatUniqId,
        language: this.language
    };
};


Chat.prototype.getInsertGuestObject = function () {
    return {
        chat_id        : this.chatId,
        online_user_id : this.guestUserId
    };
};

Chat.prototype.getInsertUserObject = function (userId, person_mode, person_join_mode_id) {
    return {
        chat_id    : this.chatId,
        person_id  : userId,
        person_mode: person_mode,
        person_join_mode_id: person_join_mode_id
    };
};

Chat.prototype.addUser = function (user, userMode) {
    if (!user) return false;
    user.addChat(this.chatUniqId);
    this.users.set(user.userId, userMode || 1);
};

Chat.prototype.removeUser = function (user) {
    if (!user || !this.users.has(user.userId)) return false;
    this.users.delete(user.userId);
    user.chatRooms.delete(this.chatUniqId);
};

Chat.prototype.isAlreadyInTheRoom = function (userId) {
    return this.users.has(userId);
};

Chat.prototype.removeUserFromChatAndNotify = function (user) {
    if (!user || !this.users.has(user.userId)) return false;
    const chat = this;
    chat.users.delete(user.userId);
    user.removeUserFromChatAndNotifyUsers(chat.chatUniqId);
};

Chat.prototype.closeChatAndNotifyUsers = function (app, socket) {
    let chat = this;

    app.connection.query('UPDATE  chats SET chat_status_id = 3 WHERE chat_id = ?', [chat.chatId], function (err) {
        if (err) {
            return app.databaseError(socket, err);
        }

        let message = new Message({chatUniqId: chat.chatUniqId, messageType: 'close'});

        chat.chatStatusId = 3;

        if (!socket) {
            message.sender = 'system';
        } else {
            if (socket.hasOwnProperty('user')) {
                //TODO სავარაუდოდ ზედმეტია, რადგან removeUserFromChatAndNotify-მ უნდა დააყენოს შემოწმების რიგში
                setTimeout(()=>app.checkAvailableOperatorForServiceOrServiceForOperator(null, socket.user), 1000);
                message.sender = socket.user.userName;
            } else {
                message.sender = 'guest';
                setTimeout(()=> app.checkAvailableOperatorForServiceOrServiceForOperator(chat.serviceId, null), 1000);
            }
        }

        app.sendMessageToRoom(message);
        chat.users.forEach(function(status, userId) {
            chat.removeUserFromChatAndNotify(app.users.get(userId));
        });

        app.chats.delete(chat.chatUniqId);
    });
};

Chat.prototype.guestLeave = function (socket) {
    this.guestUser.removeSocket(socket.id);
    if (this.guestUser.sockets.size === 0 ){
        this.guestUserLeaveTime = Date.now();
    }
};

Chat.prototype.isAvailable = function () {
    if (this.guestUserLeaveTime === -1 ) return true;
    return  Date.now() - this.guestUserLeaveTime < 30000 ;
};

Chat.prototype.addGuestSocket = function (socket) {
    this.guestUser.addSocket(socket.id);
    this.guestUserLeaveTime = -1;
};

module.exports = Chat;
