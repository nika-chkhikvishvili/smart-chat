/**
 * Created by jedi on 2017-04-21
 */

function ChatRoom(initParams) {
    initParams = initParams || {};
    if (!(this instanceof ChatRoom)) return new ChatRoom(initParams);

    this.chatRoomId    = initParams.hasOwnProperty('chatRoomId')  ? initParams.chatRoomId   : null;
    this.chat          = initParams.hasOwnProperty('chat')        ? initParams.chat         : null;
    if (initParams.hasOwnProperty('chat')) {
        var chat = initParams.chat;
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
    this.users         = [];
    this.guests        = []
}

ChatRoom.prototype.getInsertGuestObject = function () {
    return {
        chat_id       : this.chatId,
        online_user_id: this.guestUserId
    };
};

ChatRoom.prototype.getInsertUserObject = function (userId, person_mode) {
    return {
        chat_id    : this.chatId,
        person_id  : userId,
        person_mode: person_mode || null
    };
};

ChatRoom.prototype.addUser = function (userId) {
    if (!userId) return ;
    if (!this.users || !Array.isArray(this.users)) this.users = [];

    var isAdded = false;

    this.users.forEach(function (chatUserId) {
        isAdded = isAdded || ( userId === chatUserId);
    });

    if (!isAdded) {
        this.users.push(userId);
    }
};


module.exports = ChatRoom;
