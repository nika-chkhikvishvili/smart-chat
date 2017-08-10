/**
 * Created by jedi on 2017-04-21
 */

'use strict';

let randomStringGenerator = require("randomstring");


function Chat(initParams) {
    if (!(this instanceof Chat)) {
        return new Chat(initParams);
    }

    initParams = initParams || {};

    this.chatUniqId   = initParams.hasOwnProperty('chatUniqId')   ? initParams.chatUniqId      : randomStringGenerator.generate();
    this.chatId       = initParams.hasOwnProperty('chatId')       ? initParams.chatId          : null;
    this.guestUserId  = initParams.hasOwnProperty('guestUserId')  ? initParams.guestUserId     : null;
    this.guestUser    = initParams.hasOwnProperty('guestUser')    ? initParams.guestUser       : null;
    this.serviceId    = initParams.hasOwnProperty('serviceId')    ? parseInt(initParams.serviceId)    : null;
    this.chatStatusId = initParams.hasOwnProperty('chatStatusId') ? parseInt(initParams.chatStatusId) : null;
    this.guestUserLeaveTime = Date.now();
    this.users        = new Map();
}

Chat.prototype.getInsertObject = function () {
    return {
        online_user_id: this.guestUserId,
        service_id: this.serviceId,
        chat_uniq_id: this.chatUniqId
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
    if (this.users.has(user.userId)) return false;
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

Chat.prototype.closeChat = function (app) {
    this.chatStatusId = 3;
    let chatUniqId = this.chatUniqId;
    this.users.forEach(function(status, userId){
        let user = app.users.get(userId);
        user.chatRooms.delete(chatUniqId);
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
