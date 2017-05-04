/**
 * Created by jedi on 2017-04-20
 */

'use strict';

function User(user) {

    user = user || {};
    if (!(this instanceof User)) return new User(user);
    this.userId    = user.hasOwnProperty('userId')    ? user.userId    : null;
    this.firstName = user.hasOwnProperty('firstName') ? user.firstName : null;
    this.lastName  = user.hasOwnProperty('lastName')  ? user.lastName  : null;
    this.photo     = user.hasOwnProperty('photo')     ? user.photo     : null;
    this.isAdmin   = user.hasOwnProperty('isAdmin')   ? user.isAdmin   : null;
    this.statusId  = user.hasOwnProperty('statusId')  ? user.statusId  : null;
    this.sockets = {};
    this.tokens = {};
}

User.prototype.addSocket = function (socketId) {
    if (!socketId) return ;
    if (!this.sockets.hasOwnProperty(socketId)) this.sockets[socketId] = null;
};

User.prototype.addToken = function (token) {
    if (!token) return ;
    if (!this.tokens.hasOwnProperty(token)) this.tokens[token] = null;
};

User.prototype.getLimited = function () {
    return {
        personId  : this.personId ,
        firstName  : this.firstName ,
        lastName  : this.lastName
    }
};

module.exports = User;
