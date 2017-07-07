/**
 * Created by jedi on 2017-04-20
 */

'use strict';

function User(user) {

    user = user || {};
    if (!(this instanceof User)) {
        return new User(user);
    }
    this.userId      = user.userId      || null;
    this.isValid     = user.isValid     || null;
    this.userName    = user.userName    || null;
    this.firstName   = user.firstName   || null;
    this.lastName    = user.lastName    || null;
    this.photo       = user.photo       || null;
    this.isAdmin     = user.isAdmin     || null;
    this.statusId    = user.statusId    || null;
    this.isOnline    = user.isOnline    || null;
    this.sockets     = new Set();
    // this.tokens      = {};
    this.chatRooms = new Map();
}

User.prototype.addSocket = function (socket) {
    if (!socket) {
        return;
    }
    this.sockets.add(socket.id);
    this.isOnline = true;
};

User.prototype.removeSocket = function (socket) {
    if (!socket) {
        return;
    }
    this.sockets.delete(socket.id);
    this.isOnline = this.sockets.size > 0;
};

// User.prototype.addToken = function (token) {
//     if (!token) return ;
//     if (!this.tokens.hasOwnProperty(token)) this.tokens[token] = null;
// };

User.prototype.getLimited = function () {
    return {
        personId: this.personId,
        firstName: this.firstName,
        lastName: this.lastName
    }
};

User.prototype.addChat = function (chatId) {
    if (!this.chatRooms.has(chatId)) {
        this.chatRooms.set(chatId, null);
    }
};

User.prototype.canTakeMore = function () {
    return this.chatRooms.size < 5;
};

module.exports = User;
