/**
 * Created by jedi on 2017-04-20
 */

'use strict';

function User(user) {

    user = user || {};
    if (!(this instanceof User)) {
        return new User(user);
    }
    this.app         = user.app;
    this.userId      = user.userId      || null;
    this.isValid     = user.isValid     || null;
    this.userName    = user.userName    || null;
    this.firstName   = user.firstName   || null;
    this.lastName    = user.lastName    || null;
    this.photo       = user.photo       || null;
    this.isAdmin     = user.isAdmin     || null;
    this.statusId    = user.statusId    || null;
    this.isOnline    = user.isOnline    || false;
    this.repoId      = user.repoId      || null;
    this.available   = user.available !== false;
    this.sockets     = new Set();
    // this.tokens      = {};
    this.chatRooms = new Map();
}

User.prototype.addSocket = function (socket) {
    if (!socket) {
        return;
    }
    this.sockets.add(socket.id);

    if (!this.isOnline) {
        let undata = {type_id: 2, user_id: this.userId, state_id: 1};
        this.app.connection.query('INSERT INTO xlog_available_history SET ?', undata, function (err) {if (err) {return app.databaseError(null, err);}});
    }
    this.isOnline = true;
};

User.prototype.removeSocket = function (app, socket) {
    if (!socket) {
        return;
    }
    this.sockets.delete(socket.id);
    this.isOnline = this.sockets.size > 0;
    if (!this.isOnline) {
        app.onlineUsersByRepos[this.repoId].delete(this.userId);
        app.sendActiveListByRepo(this.repoId);
        let undata = {type_id: 2, user_id: this.userId, state_id: 0};
        this.app.connection.query('INSERT INTO xlog_available_history SET ?', undata, function (err) {if (err) {return app.databaseError(null, err);}});
    }
};

// User.prototype.addToken = function (token) {
//     if (!token) return ;
//     if (!this.tokens.hasOwnProperty(token)) this.tokens[token] = null;
// };

User.prototype.getLimited = function () {
    return {
        userId: this.userId,
        firstName: this.firstName,
        lastName: this.lastName,
        userName: this.userName,
        openChats: this.chatRooms.size,
        available:this.available
    }
};

User.prototype.addChat = function (chatId) {
    if (!this.chatRooms.has(chatId)) {
        this.chatRooms.set(chatId, null);
    }
};

User.prototype.canTakeMore = function () {
    return this.isAvailable() && (this.chatRooms.size < 5);
};

User.prototype.setAvailability = function (available) {
    available = (available === true);
    if (this.available === available) {
        return ;
    }
    this.available = available;

    let undata = {
        type_id: 1,
        user_id: this.userId,
        state_id: this.isAvailable() ? 1 : 0
    };

    this.app.connection.query('INSERT INTO xlog_available_history SET ?', undata, function (err) {
        if (err) {
            return app.databaseError(null, err);
        }
        app.connection.query('UPDATE persons SET availability = ? WHERE person_id = ?', [undata.state_id, undata.user_id], function(err) {
            if (err) {return app.databaseError(null, err);}

        });
    });
};

User.prototype.isAvailable = function () {
    return this.available === true;
};

User.prototype.openedChatRoomsSize = function () {
    return this.chatRooms.size;
};

User.prototype.sendUserState = function (app) {
    app.io.emit('userAvailabilityChanged', {
        userId: this.userId,
        available: this.isAvailable(),
        openChats: this.openedChatRoomsSize()
    });
};

User.prototype.sendMessageToUser = function (app, s, message) {
    this.sockets.forEach(function (socketId) {
        app.io.sockets.sockets[socketId].emit(s, message);
    });
};

module.exports = User;
