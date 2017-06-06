/**
 * Created by jedi on 2017-04-21
 */

'use strict';

function ChatRoom(initParams) {
    initParams = initParams || {};
    if (!(this instanceof ChatRoom)) return new ChatRoom(initParams);

    this.chatRoomId    = initParams.hasOwnProperty('chatRoomId')  ? initParams.chatRoomId   : null;
    this.chat          = initParams.hasOwnProperty('chat')        ? initParams.chat         : null;
    if (initParams.hasOwnProperty('chat')) {
        let chat = initParams.chat;
        this.chatUniqId    = chat.hasOwnProperty('chatUniqId')  ? chat.chatUniqId   : null;
        this.chatId        = chat.hasOwnProperty('chatId')      ? chat.chatId       : null;
        this.guestUserId   = chat.hasOwnProperty('guestUserId') ? chat.guestUserId  : null;
        this.serviceId     = chat.hasOwnProperty('serviceId')   ? chat.serviceId    : null;
    } else {
        this.chatUniqId    = initParams.hasOwnProperty('chatUniqId')  ? initParams.chatUniqId   : null;
        this.chatId        = initParams.hasOwnProperty('chatId')      ? initParams.chatId       : null;
        this.guestUserId   = initParams.hasOwnProperty('guestUserId') ? initParams.guestUserId  : null;
        this.serviceId     = initParams.hasOwnProperty('serviceId')   ? initParams.serviceId    : null;
    }
    this.users         =  new Map();
    this.guests        = []
}

ChatRoom.prototype.getInsertGuestObject = function () {
    return {
        chat_id       : this.chatId,
        online_user_id: this.guestUserId
    };
};

ChatRoom.prototype.getInsertUserObject = function (userId, person_mode, person_join_mode_id) {
    return {
        chat_id    : this.chatId,
        person_id  : userId,
        person_mode: person_mode,
        person_join_mode_id: person_join_mode_id
    };
};

ChatRoom.prototype.addUser = function (userId, userMode, user) {
    if (!userId) return false;
    if (!!user){
        user.addChat(this.chatUniqId);
    }
    if (this.users.has(userId)) return false;
    this.users.set(userId, userMode || 1);
};

ChatRoom.prototype.removeUser = function (user) {
    if (!user) return false;

        let st = this.users.get(user.userId);
        if (st && isFinite(st) && parseInt(st) === 1) {
            this.users.delete(user.userId);
            user.removeChat(this.chatUniqId );
        }
};

ChatRoom.prototype.isAlreadyInTheRoom = function (userId) {
    return this.users.has(userId);
};

module.exports = ChatRoom;
