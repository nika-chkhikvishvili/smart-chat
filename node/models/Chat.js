/**
 * Created by jedi on 2017-04-21
 */

'use strict';

var randomStringGenerator = require("randomstring");


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
    this.users        = new Map();
    this.guests       = new Set();
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
    if (!user) return false;

    let st = this.users.get(user.userId);
    if (st && isFinite(st) && parseInt(st) === 1) {
        this.users.delete(user.userId);
        user.removeChat(this.chatUniqId );
    }
};

Chat.prototype.isAlreadyInTheRoom = function (userId) {
    return this.users.has(userId);
};

Chat.prototype.closeChat = function (app) {
    this.chatStatusId = 3;
    let chatUniqId = this.chatUniqId;
    this.users.forEach(function(userId){
        let user = app.users.get(userId);
        user.removeChat(chatUniqId);
    });
};



module.exports = Chat;
